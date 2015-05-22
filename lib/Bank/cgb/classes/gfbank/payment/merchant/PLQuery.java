package gfbank.payment.merchant;
import java.net.*;
import java.io.*;
import java.util.*;
import java.security.*;
import sun.security.provider.Sun;


class DoQuery extends Thread
{
	java.io.PrintStream out ;
	String urlreq;

	public DoQuery(String req, PrintStream out)
	{
		this.urlreq = req;

		this.out = out;
	}

	public void run()
	{
		try {
			out.println("send request: " + this.urlreq);

			URL url=new URL(this.urlreq);
			DataInputStream dis;
			String line;
			dis=new DataInputStream(url.openStream());

			for(int i=0; (line=dis.readLine())!=null; i++ )
			{
				out.println(line);
				String oo = "The order doesn't exist";
				if(line.regionMatches(0, oo, 0, oo.length()))
				{
					getridof(line);
				}
			}

			dis.close();
		}
		catch (MalformedURLException e){
			System.err.println(e);
		}
		catch (IOException e){
			System.err.println(e);
		}
	}
	public void getridof(String line)
	{
		try{
			String orderid = line.substring(line.length() - PLQuery.orderid_length);
			out.println("removing orderid:[" + orderid + "]");
			File f = new File(PLQuery.path + "/" + orderid);
			if(f.exists())
			{
				f.delete();
				out.println("orderid invalid,removed:[" + orderid + "]");
			}
			else
			{
				out.println("can't remove orderid[" + orderid + "], file no found:[" + PLQuery.path + "/" + orderid + "]");
			}
		}catch(Exception e){
			e.printStackTrace(System.err);
		}
	}
}

public class PLQuery
{
	public static String logfile;
	public static String urlreq;
	public static String path;
	public static String merchid;
	public static String returl;
	public static String merchantprivatekey;
	public static int    timeout;
	public static int    clearlog_day;
	public static int    order_max_exist_time;
	public static int    orderid_length;

	public static java.io.PrintStream out;

	public static boolean init()
	{
		try{
			logfile = PayCfg.getValue("logfile");
			urlreq  = PayCfg.getValue("gfbankqueryurl");
			merchid = PayCfg.getValue("merchid");
			path    = PayCfg.getValue("orderid_path");
			returl  = PayCfg.getValue("merchantreturl");
			merchantprivatekey = PayCfg.getValue("merchantprivatekey");
			timeout = Integer.parseInt(PayCfg.getValue("timeout"));
			if(timeout < 1)
				timeout = 1;
			clearlog_day         = Integer.parseInt(PayCfg.getValue("clearlog_day"));
			order_max_exist_time = Integer.parseInt(PayCfg.getValue("order_max_exist_time"));
			if(order_max_exist_time < 1)
				order_max_exist_time = 1;
			orderid_length       = Integer.parseInt(PayCfg.getValue("orderid_length"));
		}catch(Exception e){
			System.err.println(e);
			return false;
		}

		try{
			int d = (new java.util.GregorianCalendar(TimeZone.getDefault())).get(Calendar.DAY_OF_MONTH);
			if(d==clearlog_day)
				out = new PrintStream(new FileOutputStream(logfile));
			else
				out = new PrintStream(new FileOutputStream(logfile, true));
		}catch(Exception e){
			e.printStackTrace(System.err);
			System.err.println("无法打开日志文件");
			return false;
		}

		return true;
	}

	public static void main(String args[])
	{
		if(!init())
			return;

		for(;;)
		{

			travel();

			try{
				Object syn = new Object();
				synchronized(syn)
				{
					syn.wait(timeout * 60 * 1000);
				}
			} catch(Exception e) {
				e.printStackTrace(System.err);
			}
		}
	}

	public static void travel()
	{
		long t = new java.util.Date().getTime();
		File dir = new File(path);
		out.println();
		out.println("Travel path:" + path + " (" + new java.sql.Date(t)  + " " + new java.sql.Time(t) + ")");
		String[] filenames = dir.list();

		for(int i = 0; i < filenames.length; i++)
		{
			out.print(filenames[i]);
			File f = new File(path + "/" + filenames[i]);
			long tfile = f.lastModified();
			long tnow = new java.util.Date().getTime();
			out.print(" \t" + new java.sql.Date(tfile) + " " + new java.sql.Time(tfile));
			out.print(" \t" + new java.sql.Date(tnow)  + " " + new java.sql.Time(tnow));
			out.print(" \t" + (tnow - tfile)/60000);

			if((tnow - tfile) / 60000 > order_max_exist_time * 60)
			{
				out.println(" \t" + "已存在" + (tnow - tfile) / (60000 * 60) + "小时, 删除");
				f.delete();
				continue;
			}
			else
				out.println("");

			if(filenames[i].length() != orderid_length)
			{
				out.println("error: file name length !=" + orderid_length + ", filename=" + filenames[i]);
				continue;
			}

			if((tnow - tfile)/60000 > timeout)
			{
				String signdata = filenames[i] + merchid + returl;
				out.println("signdata:[" + signdata + "]");
				String rs = sign_md(signdata, merchantprivatekey);
				if(rs == null || rs.length() < 10)
				{
					out.println("签名失败：signdata=[" + signdata + "], retcode=" + rs);
					continue;
				}
				DoQuery doquery = new DoQuery(urlreq + "?orderid=" + filenames[i]
					+ "&merchid=" + merchid + "&returl=" + returl + "&sign=" + rs, out);
				doquery.start();
			}
		}
	}

	public static String sign_md(String s, String pk)
	{
        System.err.println("sign_md " + s + " " + pk);
        String s2 = null;
        try
        {
            if(s == null || pk == null)
            {
                System.err.println("Error:in sign_md:innuf or filename is null");
                return "6";
            }
            try{
            java.security.PrivateKey privatekey = getPrivateKey(pk);
            Sun sun = new Sun();
            Security.addProvider(sun);
            Signature signature = Signature.getInstance("DSA");
            signature.initSign(privatekey);
            MessageDigest messagedigest = MessageDigest.getInstance("SHA");
            byte abyte0[] = messagedigest.digest(s.getBytes());
            System.err.println("*****: after message digest");
            signature.update(abyte0);
            byte abyte1[] = signature.sign();
            s2 = byteToHex(abyte1);
            }catch(Exception e){
        	return "1";
            }

            System.err.println("*****: after dsa.sign()");
        }
        catch(Exception exception)
        {
            System.err.println("Exception in sign_md:" + exception.toString());
            exception.printStackTrace();
            return "4";
        }
        return s2;
	}

        public static PrivateKey getPrivateKey(String keyname)
  	{
        try
        {
            FileInputStream istream = new FileInputStream(keyname + ".private");
            ObjectInputStream p = new ObjectInputStream(istream);
            PrivateKey priv = (PrivateKey)p.readObject();
            p.close();
            return priv;
        }
        catch(Exception e)
        {
            System.err.println("in KeyManager.getPrivateKey()  Caught exception " + e.toString());
        }
        return null;
	}

	public static String byteToHex(byte abyte0[])
	{
        StringBuffer stringbuffer = new StringBuffer();
        for(int i = 0; i < abyte0.length; i++)
        {
            String s = Integer.toHexString(abyte0[i] & 0xff);
            if(s.length() != 2)
                stringbuffer.append('0').append(s);
            else
                stringbuffer.append(s);
        }

        return new String(stringbuffer);
	}
}