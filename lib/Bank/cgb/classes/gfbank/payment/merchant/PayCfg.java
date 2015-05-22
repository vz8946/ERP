package gfbank.payment.merchant;

import java.util.*;
import java.io.*;

public class PayCfg {

  public PayCfg() {
  }
  public static String getValue(String key)throws Exception{
    if(!isPropLoaded){
      try{
        prop=new Properties();
        prop.load(new FileInputStream(cfgfile));
        isPropLoaded=true;
      }
      catch(Exception e){
        System.err.println("in PayCfg{}  Caught exception " + e.toString());
        throw e;
      }
    }
    return prop.getProperty(key);
  }
  private static String cfgfile="D:\\PHPnow\\htdocs\\project\\lib\\Bank\\cgb\\conf\\PLQuery.properties";
  //private static String cfgfile="/www/web/99vk/lib/Bank/cgb/conf/PLQuery.properties";
  private static Properties prop=null;
  private static boolean isPropLoaded=false;
}