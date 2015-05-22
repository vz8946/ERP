import com.bocnet.common.security.*;
import java.io.*;
import java.security.*;

public class custom
{
	public String sign(String srcStr, String keyPath, String privatePasswd) throws Exception {
		
		PKCS7Tool tool = PKCS7Tool.getSigner(keyPath, privatePasswd, privatePasswd);
		
		byte[] byteSrc = srcStr.getBytes();
		String signature = tool.sign(byteSrc);
		
		return signature;
	}
	
	public String verify(String signStr, String srcStr, String cerPath) throws Exception {
	    PKCS7Tool tool = PKCS7Tool.getVerifier(cerPath);
	    
	    byte[] byteSrc = srcStr.getBytes();   
	    String dn = null;
	    try {
	        tool.verify(signStr, byteSrc, dn);
	    }
	    catch(Exception ex){
	        return "0";
	    }
	    
	    return "1";
	}
}
