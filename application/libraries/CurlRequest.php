<?php
class CurlRequest {

  public static function get($url, $user_agent, &$curl_info, $coustom_options = array()) {
    $header = array(
      'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'Accept-Language: zh-tw,zh;q=0.8,en-us;q=0.5,en;q=0.3',
      'Connection: keep-alive',
      'Cache-Control: max-age=0',
    );
    $cookie_file = (is_dir(CURL_COOKIE_TEMP_PATH)) ? CURL_COOKIE_FILENAME : 'cookie.tmp';
    $options = array(
      CURLOPT_HTTPGET         => true,
      CURLOPT_SSL_VERIFYPEER  => 0,
      CURLOPT_SSL_VERIFYHOST  => 0,
      CURLOPT_POST            => false,
      CURLOPT_USERAGENT       => $user_agent,
      CURLOPT_COOKIEFILE      => $cookie_file,
      CURLOPT_COOKIEJAR       => $cookie_file,
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_HEADER          => true,
      CURLOPT_FOLLOWLOCATION  => false,
      CURLOPT_ENCODING        => 'gzip, deflate',
      CURLOPT_AUTOREFERER     => true,
      CURLOPT_CONNECTTIMEOUT  => 90,
      CURLOPT_TIMEOUT         => 90,
      CURLOPT_MAXREDIRS       => 10,
      CURLOPT_HTTPHEADER      => $header,
    );
		if ($coustom_options) {
			foreach($coustom_options as $key => $co) {
				if (array_key_exists($key, $options)) {
					$options[$key] = $co;
				}
			}
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $html = curl_exec($ch);
    $_info = curl_getinfo($ch);
    $errno = curl_errno($ch);
    $errmsg = curl_error($ch);
    curl_close($ch);
    $hdr = self::http_parse_headers(substr($html, 0, $_info['header_size']));
    $html = substr($html, $_info['header_size']);
    $curl_info = array('errno' => $errno, 'errmsg' => $errmsg) + $_info + array('header' => $hdr);
    return $html;
  }
  public static function http_parse_headers($response) {
    $headers = array();
    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
    foreach (explode("\r\n", $header_text) as $i => $row) {
      if ($i === 0) {
        $headers['http_code'] = $row;
      }
      else {
        list ($key, $value) = explode(': ', $row);
        $headers[$key] = $value;
      }
    }
    return $headers;
  }
}
