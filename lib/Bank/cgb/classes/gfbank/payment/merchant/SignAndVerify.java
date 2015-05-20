// Decompiled by Jad v1.5.7f. Copyright 2000 Pavel Kouznetsov.
// Jad home page: http://www.geocities.com/SiliconValley/Bridge/8617/jad.html
// Decompiler options: packimports(3)
// Source File Name:   SignAndVerify.java
package gfbank.payment.merchant;
import java.io.PrintStream;
import java.security.*;
import java.io.*;
import java.util.*;
import sun.security.provider.Sun;

public class SignAndVerify
{
	//static String propfile = "PLQuery.properties";
	static String orderid_path="";
	static int    orderid_length=0;
	static String merchantprivatekey="";
	static String gdbpublickey="";
	static boolean proploaded = false;

    public SignAndVerify()
    {
    }

    public static boolean loadprop()throws Exception
    {
    	try{
    		if(proploaded)
    			return true;
      	        //ResourceBundle rb = ResourceBundle.getBundle("PLQuery");
		orderid_path = PayCfg.getValue("orderid_path");
		orderid_length = Integer.parseInt(PayCfg.getValue("orderid_length"));
		merchantprivatekey = PayCfg.getValue("merchantprivatekey");
		gdbpublickey = PayCfg.getValue("gdbpublickey");

		proploaded = true;
	}catch(Exception e) {
          System.err.println("in SignAndVerify.loadprop()  Caught exception " + e.toString());
          throw e;
	}
        return true;
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

    public static byte[] hexToByte(String s)
    {
        int i = s.length() / 2;
        byte abyte0[] = new byte[i];
        for(int j = 0; j < i; j++)
        {
            String s1 = s.substring(j * 2, j * 2 + 2);
            abyte0[j] = (byte)Integer.parseInt(s1, 16);
        }

        return abyte0;
    }

    public static void main(String args[])
    {
        try
        {
            packetSign(new String[] {
                "0001", "aaaaaaaaaaaaaaaaaa", " ", " "
            });
            return;
        }
        catch(Exception _ex)
        {
            return;
        }
    }

    public static void packetSign(String as[])
        throws Exception
    {
        String s = sign_md(as[1], as[0]);
        as[3] = s;
        System.out.println(s);
    }

    public static void packetVerify(String as[])
        throws Exception
    {
    	/*
        if(verify_md(as[1], as[2], as[0]))
        {
            as[3] = "0";
            return;
        } else
        {
            as[3] = "1";
            return;
        }*/
        as[3] = verify_md(as[1], as[2], as[0]);
    }

    public static String sign(String s, String s1)
        throws Exception
    {
        String s2 = null;
        try
        {
            java.security.PrivateKey privatekey = KeyManager.getPrivateKey(s1);
            Signature signature = Signature.getInstance("SHA/DSA");
            signature.initSign(privatekey);
            signature.update(s.getBytes());
            byte abyte0[] = signature.sign();
            System.out.println("sig:" + abyte0.length);
            s2 = byteToHex(abyte0);
        }
        catch(Exception exception)
        {
            System.err.println("Exception in sign:" + exception.toString());
            throw new Exception();
        }
        return s2;
    }

    public static String sign_md(String s, String s1)
        throws Exception
    {
        String s2 = null;
        try
        {
            if(s == null)
            {
                System.err.println("Error:in sign_md:innuf is null");
                return "6";
            }

            try{
            	loadprop();
            }catch(Exception e){
                System.err.println("in SignAndVerify.sign_md()  Caught exception " + e.toString());
            	return "9";
            }

            try{
            //System.err.println(merchantprivatekey);
            java.security.PrivateKey privatekey = KeyManager.getPrivateKey(merchantprivatekey);
            Sun sun = new Sun();
            Security.addProvider(sun);
            Signature signature = Signature.getInstance("DSA");
            signature.initSign(privatekey);
            MessageDigest messagedigest = MessageDigest.getInstance("SHA");
            byte abyte0[] = messagedigest.digest(s.getBytes());
            //System.err.println("*****: after message digest");
            signature.update(abyte0);
            byte abyte1[] = signature.sign();
            s2 = byteToHex(abyte1);
            }catch(Exception e){
        	return "1";
            }

            /*Properties prop=new Properties();
            Date date=new Date();
            long time=date.getTime();
            String value=new Long(time).toString()+"    #"+date;
            prop.put(s.substring(15, 15 + orderid_length) , value);
            PrintWriter writer=new PrintWriter(new FileOutputStream(orderfile,true),true);
            prop.list(writer);
            writer.close();
            */
            FileOutputStream f = new FileOutputStream(orderid_path+ "/" + s.substring(15, 15 + orderid_length));
            f.close();


            //System.err.println("*****: after dsa.sign()");
        }
        catch(Exception exception)
        {
            System.err.println("Exception in sign_md:" + exception.toString());
            exception.printStackTrace();
            //throw new Exception();
            return "4";
        }
        return s2;
    }

    public static boolean verify(String s, String s1, String s2)
        throws Exception
    {
        try
        {
            if(s == null || s1 == null || s2 == null)
            {
                System.err.println("Error:in verify:innuf or sign or filename is null");
                throw new Exception();
            } else
            {
                java.security.PublicKey publickey = KeyManager.getPublicKey(s2);
                Signature signature = Signature.getInstance("SHA/DSA");
                signature.initVerify(publickey);
                signature.update(s.getBytes());
                byte abyte0[] = hexToByte(s1);
                return signature.verify(abyte0);
            }
        }
        catch(Exception exception)
        {
            System.err.println("Exception in verify:" + exception.toString());
        }
        throw new Exception();
    }

    public static String verify_md(String s, String s1, String s2)
        throws Exception
    {
        try
        {
            if(s == null || s1 == null)
            {
                System.err.println("Error:in verify_md:innuf or sign or filename is null");
                return "6";
            } else
            {
            	boolean ret = false;
            	try{

            	try{
            		loadprop();
         	}catch(Exception e){
         		return "9";
       		}

                java.security.PublicKey publickey = KeyManager.getPublicKey(gdbpublickey);
                Signature signature = Signature.getInstance("SHA/DSA");
               	signature.initVerify(publickey);

                MessageDigest messagedigest = MessageDigest.getInstance("SHA");
                byte abyte0[] = messagedigest.digest(s.getBytes());
                signature.update(abyte0);
                byte abyte1[] = hexToByte(s1);

                ret = signature.verify(abyte1);
        	}catch(Exception e)
        	{
        		return "3";
        	}

                if(ret)
                {
          	      	try{
            			File f = new File(orderid_path+ "/" + s.substring(0, orderid_length));
            			f.delete();
            		} catch (Exception e) {}
            		return "0";
        	}
        	else
	        	return "7";
            }
        }
        catch(Exception exception)
        {
            System.err.println("Exception in SignAndVerify.verify_md():" + exception.toString());
        }
        //throw new Exception();
        return "4";
    }
}
