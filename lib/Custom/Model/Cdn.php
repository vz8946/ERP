<?php

class Custom_Model_Cdn
{
	private $_url = 'http://pushdx.dnion.com/cdnUrlPush.do';
	private $_username = '1jiankang';
	private $_password = '1jiankang';
	
	public function cleanAll($domain, $type, $img, $url) 
	{
	    if ($type == 'img_folder' || $type == 'img_file') {
	        for ( $i = 1; $i <= 5; $i ++ ) {
	            $urls[] = urlencode("http://img{$i}.1jiankang.com{$img}");
	        }
	        $urls[] = urlencode("http://images.1jiankang.com{$img}");
            $urls[] = urlencode("http://img.1jiankang.com{$img}");
	    }
	    else if ($type == 'full_url') {
	        $urls[] = urlencode($url);
	    }
	    else {
    	    $typeURL['index'] = "http://{$domain}";
    	    $typeURL['goodsgallery'] = "http://{$domain}/gallery-(.*)";
    	    $typeURL['groupgoodsgallery'] = "http://{$domain}/group-goods";
    	    $typeURL['goodsshow'] = "http://{$domain}/goods-(.*)";
    	    $typeURL['groupgoodsshow'] = "http://{$domain}/groupgoods-(.*)";
    	    $typeURL['helppage'] = "http://{$domain}/help-(.*)";
    	    $typeURL['special'] = "http://{$domain}/special-(.*)";
    	    $typeURL['css'] = "http://images.1jiankang.com/scripts/";
    	    $typeURL['js'] = "http://images.1jiankang.com/styles/";
    	    $urls[] = urlencode($typeURL[$type]);
	    }
	    
	    if ($type == 'css' || $type == 'js' || $type == 'img_folder') {
	        $type = 0;
	    }
	    else    $type = 1;
	    
	    foreach ( $urls as $url ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this -> _url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username={$this -> _username}&password={$this -> _password}&type={$type}&url={$url}&decode=y");
            $output = curl_exec($ch);
            curl_close($ch);
            
            $lines = explode(chr(13).chr(10), $output);
            $rk = substr($lines[3], 4, 1);
            
            if ($rk != '0') return $rk;
        }
        
        return $rk;
    }
}