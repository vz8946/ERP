import cn.com.infosec.icbc.ReturnValue;

import java.io.*;

public class Signature
{
	private ReturnValue rv = new ReturnValue();
	
	public Signature()
	{
		super();
	}
	
	public String sign(String srcStr, String keyPath, String privatePasswd) throws Exception{
		
		FileInputStream fileKey=null;
		try{
			
			byte[] byteSrc = srcStr.getBytes();   
			fileKey  = new FileInputStream(keyPath);
			byte[] PriK = new byte[fileKey.available()];            
			fileKey.read(PriK);
			fileKey.close();
			char[] keyPass = privatePasswd.toCharArray();
			byte[] sign = rv.sign(byteSrc,byteSrc.length,PriK,keyPass);
			if (sign == null)    return "error";
			byte[] tmpSign = rv.base64enc(sign);
			return new String(tmpSign);
		}catch(IOException ex){
			if(fileKey!=null)
				fileKey.close();
			throw ex;
		}
		
	}
	
	public int verifySign(String signStr, String srcStr,String certPath) throws Exception{
		
		FileInputStream fileCert = null;
		try{
			int i = 0;
			byte[] byteSrc = srcStr.getBytes();
			byte[] sign = signStr.getBytes();
			byte[] tmpSign = rv.base64dec(sign);
			fileCert = new FileInputStream(certPath);
			byte[] cert = new byte[fileCert.available()];
			fileCert.read(cert);
			fileCert.close();
			int verifySign = rv.verifySign(byteSrc,byteSrc.length,cert,tmpSign);
			if(verifySign==0) i = 1;
			return i;
		}catch(IOException ex){
			if(fileCert!=null){
				fileCert.close();
			}
			throw ex;
		}      	
	}
}
