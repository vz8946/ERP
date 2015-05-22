// Decompiled by Jad v1.5.7f. Copyright 2000 Pavel Kouznetsov.
// Jad home page: http://www.geocities.com/SiliconValley/Bridge/8617/jad.html
// Decompiler options: packimports(3)
// Source File Name:   KeyManager.java
package gfbank.payment.merchant;
import java.io.*;
import java.util.*;
import java.security.*;

class KeyManager
{

    public static void createKeyPair(String keyname)
        throws Exception
    {
        try
        {
            KeyPairGenerator keyGen = KeyPairGenerator.getInstance("DSA");
            keyGen.initialize(1024, new SecureRandom());
            KeyPair pair = keyGen.generateKeyPair();
            PrivateKey priv = pair.getPrivate();
            PublicKey pub = pair.getPublic();
            FileOutputStream ostream = new FileOutputStream(keyname + ".private");
            ObjectOutputStream p = new ObjectOutputStream(ostream);
            p.writeObject(priv);
            p.close();
            ostream = new FileOutputStream(keyname + ".public");
            p = new ObjectOutputStream(ostream);
            p.writeObject(pub);
            p.close();
            System.out.println(priv.getAlgorithm());
            System.out.println(priv.getFormat());
            ostream.close();
        }
        catch(Exception e)
        {
            System.err.println("\u751F\u6210\u5BC6\u5319\u9519\u8BEF\uFF1A" + e.toString());
            throw e;
        }
    }

    public static KeyPair getKeyPair(String keyname)
    {
        try
        {
            FileInputStream istream = new FileInputStream(keyname + ".private");
            ObjectInputStream p = new ObjectInputStream(istream);
            PrivateKey priv = (PrivateKey)p.readObject();
            p.close();
            istream = new FileInputStream(keyname + ".public");
            p = new ObjectInputStream(istream);
            PublicKey pub = (PublicKey)p.readObject();
            p.close();
            KeyPair pair = new KeyPair(pub, priv);
            return pair;
        }
        catch(Exception e)
        {
            System.err.println("in KeyManager.getKeyPair() Caught exception " + e.toString());
        }
        return null;
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

    public static PublicKey getPublicKey(String keyname)
    {
        try
        {
            FileInputStream istream = new FileInputStream(keyname + ".public");
            ObjectInputStream p = new ObjectInputStream(istream);
            PublicKey pub = (PublicKey)p.readObject();
            p.close();
            return pub;
        }
        catch(Exception e)
        {
            System.err.println("in KeyManager.getPublicKey()  Caught exception " + e.toString());
        }
        return null;
    }

    public static void main(String args[])
    {
        KeyManager keyManager = new KeyManager();
        System.err.println("\u5F00\u59CB\u751F\u6210\u5BC6\u5319\u5BF9....");
        String keyaddr = "";
        try
        {
            keyaddr = PayCfg.getValue("merchid");
            createKeyPair("merkey" + keyaddr);
        }
        catch(Exception _ex)
        {
            System.err.println("\u8BF7\u68C0\u67E5\u51FA\u9519\u539F\u56E0\u540E\u518D\u8BD5");
            System.exit(1);
        }
        KeyPair pair = getKeyPair("merkey" + keyaddr);
        System.err.println(pair);
        System.err.println("\u751F\u6210\u5BC6\u5319\u5BF9\u6210\u529F");
    }

    KeyManager()
    {
    }
}
