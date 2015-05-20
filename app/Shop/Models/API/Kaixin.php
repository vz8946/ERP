<?php
 class Shop_Models_API_Kaixin
 {
     /**
      * 开心网联合登录
      */
     public $kaixin_params=array(
             "appid"=>"100053350",
             "callback"=>"http://www.1jiankang.com/auth/kaixincallback",
             "appkey"=>"76637768929215f5092a0f857bbe118b",
             "appsecret"=>"e00db840bde265f5ef7b8ab9e4bd20ec"
     );
     /**
      * @ignore
      */
     public $client_id;
     /**
      * @ignore
      */
     public $client_secret;
     /**
      * @ignore
      */
     public $access_token;
     /**
      * @ignore
      */
     public $refresh_token;
     /**
      * Contains the last HTTP status code returned.
      *
      * @ignore
      */
     public $http_code;
     /**
      * Contains the last API call.
      *
      * @ignore
      */
     public $url;
     /**
      * Set up the API root URL.
      *
      * @ignore
      */
     public $host = "https://api.kaixin001.com/";
     /**
      * Set timeout default.
      *
      * @ignore
      */
     public $timeout = 30;
     /**
      * Set connect timeout.
      *
      * @ignore
      */
     public $connecttimeout = 30;
     /**
      * Verify SSL Cert.
      *
      * @ignore
      */
     public $ssl_verifypeer = FALSE;
     /**
      * Respons format.
      *
      * @ignore
      */
     public $format = 'json';
     /**
      * Decode returned json data.
      *
      * @ignore
      */
     public $decode_json = TRUE;
     /**
      * Contains the last HTTP headers returned.
      *
      * @ignore
      */
     public $http_info;
     /**
      * Set the useragnet.
      *
      * @ignore
      */
     public $useragent = 'KX PHPSDK OAuth2 v2.0';
     
     /**
      * print the debug info
      *
      * @ignore
      */
     public $debug = FALSE;
     
     /**
      * boundary of multipart
      * @ignore
      */
     public static $boundary = '';
     
     /**
      * params of file
      * @ignore
      */
     
     public static $params_file = array(
             'pic',
             'image',
             'attachment',
             'attachment1',
             'attachment2',
             'attachment3',
             'attachment4',
     );
     
     /**
      * Set API URLS
     */
     /**
      * @ignore
     */
     public function accessTokenURL()  { return 'https://api.kaixin001.com/oauth2/access_token'; }
     
     /**
      * @ignore
      */
     public function authorizeURL()    { return 'https://api.kaixin001.com/oauth2/authorize'; }
     /**
      * authorize接口
      *
      * 对应API：{@link https://api.kaixin001.com/Oauth2/authorize}
      *
      * @param string $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
      * @param string $response_type 支持的值包括 code 和token 默认值为code
      * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
      * @param string $display 授权页面类型 可选范围:
      *  - default		默认授权页面
      *  - mobile		支持html5的手机
      *  - popup			弹窗授权页
      * @return array
      */
     public function getAuthorizeURL($client_id, $url, $response_type = 'code', $state = NULL, $display = NULL )
     {
         $params = array();
         $params['client_id'] = $client_id;
         $params['redirect_uri'] = $url;
         $params['response_type'] = $response_type;
         $params['state'] = $state;
         $params['display'] = $display;
         return $this->authorizeURL() . "?" . http_build_query($params);
     }
     /**
      * access_token接口
      *
      * 对应API：{@link https://api.kaixin001.com/Oauth2/access_token}
      *
      * @param string $type 请求的类型,可以为:code, password, token
      * @param array $keys 其他参数：
      *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
      *  - 当$type为password时： array('username'=>..., 'password'=>...)
      *  - 当$type为token时： array('refresh_token'=>...)
      * @return array
      */
     public function getAccessToken( $type = 'code', $keys )
     {
         $params = array();
         $params['client_id'] = $this->kaixin_params['appkey'];
         $params['client_secret'] = $this->kaixin_params['appsecret'];
         if ( $type === 'token' ) {
             $params['grant_type'] = 'refresh_token';
             $params['refresh_token'] = $keys['refresh_token'];
         } elseif ( $type === 'code' ) {
             $params['grant_type'] = 'authorization_code';
             $params['code'] = $keys['code'];
             $params['redirect_uri'] = $keys['redirect_uri'];
         } elseif ( $type === 'password' ) {
             $params['grant_type'] = 'password';
             $params['username'] = $keys['username'];
             $params['password'] = $keys['password'];
         } else {
             throw new Exception("wrong auth type");
         }
     
         $response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
         $token = json_decode($response, true);
         if ( is_array($token) && !isset($token['error']) ) {
             $this->access_token = $token['access_token'];
             $this->refresh_token = $token['refresh_token'];
         } else {
             throw new Exception("get access token failed." . $token['error']);
         }
         return $token;
     }
     /**
      * Format and sign an OAuth / API request
      *
      * @return string
      * @ignore
      */
     public function oAuthRequest($url, $method, $parameters, $multi = false)
     {
         if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
             $url = "{$this->host}{$url}.{$this->format}";
         }
     
         switch ($method) {
             case 'GET':
                 $parameters['access_token'] = $this->access_token;
                 $url = $url . '?' . http_build_query($parameters);
                 return $this->http($url, 'GET');
             default:
                 $headers = array();
                 $parameters['access_token'] = $this->access_token;
                 if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
                     $body = http_build_query($parameters);
                 } else {
                     $body = self::build_http_query_multi($parameters);
                     $headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
                 }
                 return $this->http($url, $method, $body, $headers);
         }
     }
     /**
      * Make an HTTP request
      *
      * @return string API results
      * @ignore
      */
     public function http($url, $method, $postfields = NULL, $headers = array())
     {
         $this->http_info = array();
         $ci = curl_init();
         /* Curl settings */
         curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
         curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
         curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
         curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
         curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
         curl_setopt($ci, CURLOPT_ENCODING, "");
         curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
         curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
         curl_setopt($ci, CURLOPT_HEADER, FALSE);
     
         switch ($method) {
             case 'POST':
                 curl_setopt($ci, CURLOPT_POST, TRUE);
                 if (!empty($postfields)) {
                     curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                     $this->postdata = $postfields;
                 }
                 break;
             case 'DELETE':
                 curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                 if (!empty($postfields)) {
                     $url = "{$url}?{$postfields}";
                 }
         }
     
         if ( isset($this->access_token) && $this->access_token ) {
             $headers[] = "Authorization: OAuth2 ".$this->access_token;
         }
     
         $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
         curl_setopt($ci, CURLOPT_URL, $url );
         curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
         curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
     
         $response = curl_exec($ci);
         $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
         $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
         $this->url = $url;
     
         if ($this->debug) {
             echo "=====post data======\r\n";
             var_dump($postfields);
     
             echo '=====info====='."\r\n";
             print_r( curl_getinfo($ci) );
     
             echo '=====$response====='."\r\n";
             print_r( $response );
         }
         curl_close ($ci);
         return $response;
     }
     /**
      * Get the header info to store.
      *
      * @return int
      * @ignore
      */
     public function getHeader($ch, $header)
     {
         $i = strpos($header, ':');
         if (!empty($i)) {
             $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
             $value = trim(substr($header, $i + 2));
             $this->http_header[$key] = $value;
         }
         return strlen($header);
     }
     /**
      * @ignore
      */
     public static function build_http_query_multi($params)
     {
         if (!$params) return '';
     
         uksort($params, 'strcmp');
     
         $pairs = array();
     
         self::$boundary = $boundary = uniqid('------------------');
         $MPboundary = '--'.$boundary;
         $endMPboundary = $MPboundary. '--';
         $multipartbody = '';
     
         foreach ($params as $parameter => $value) {
             if( in_array($parameter, self::$params_file) && $value{0} == '@' ) {
                 $url = ltrim( $value, '@' );
                 if(!empty($url))
                 {
                     $content = file_get_contents( $url );
                     $array = explode( '?', basename( $url ) );
                     $filename = $array[0];
     
                     $filename = $_FILES[$parameter]['name'];
                     $multipartbody .= $MPboundary . "\r\n";
                     $mime = self::get_image_mime($url);
                     $multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
                     $multipartbody .= "Content-Type: ".$mime."\r\n\r\n";
                     $multipartbody .= $content. "\r\n";
                 }
             } else {
                 $multipartbody .= $MPboundary . "\r\n";
                 $multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                 $multipartbody .= $value."\r\n";
             }
     
         }
     
         $multipartbody .= $endMPboundary;
         return $multipartbody;
     }
     /**
      * GET wrappwer for oAuthRequest.
      *
      * @return mixed
      */
     public function get($url, $parameters = array())
     {
         $response = $this->oAuthRequest($url, 'GET', $parameters);
         if ($this->format === 'json' && $this->decode_json) {
             return json_decode($response, true);
         }
         return $response;
     }
 }