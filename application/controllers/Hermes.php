<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('CURL_COOKIE_TEMP_PATH', '/var/www/html/space/tmp/cookies/');
include_once 'application/controllers/Base.php';

class Hermes extends Base {
	private $_user_agant = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:47.0) Gecko/20100101 Firefox/50.0';
	private $encodings = array("utf-8", "big5", "big-5", "ascii", "shift_jis", "gb2312", "iso-8859-1", "iso-8859-2", "iso-8859-3", "iso-8859-4", "iso-8859-5", "iso-8859-6", "iso-8859-7", "iso-8859-8", "iso-8859-9", "iso-8859-10", "iso-8859-13", "iso-8859-14", "iso-8859-15", "iso-8859-16", "windows-1251", "windows-1252", "windows-1254");

	private $realm_currency = array(
		'JPY',
		'TWD',
		'EUR',
		'GBP',
		'AUD',
		'CNY',
		'SGD',
		'CHF',
		'CAD',
		'INR',
		'MYR',
		'XBT',
	);

	public function __construct() {
		parent::__construct();
		
		$this->load->library('CurlRequest', "", "curl");
	}

	public function showRate() {
		$realm = ($r = $this->input->get('r')) ? $r : "";
		$ret = array();
	
		if ($realm) {
			$this->_linkSpaceDB();
			$ret = SpaceModel::get($realm);
		}
		
		$view_data['realm'] = $realm;
		$view_data['rateData'] = $ret;

		$this->load->view('show_rate', $view_data);
		//print_r($ret);exit;
	}

	public function getRateSomething() {
		if(!$this->input->is_cli_request()) {
			print_r("nonono");exit;
		}
		else {
			$_now = time();		
			$datetime = date("Y-m-d H:i:s", $_now);

			//$rate_url = "http://rate.bot.com.tw/xrt?Lang=zh-TW";
			$currency = array();

			foreach($this->realm_currency as $realm) {
				$rate_url = "http://www.xe.com/currencyconverter/convert/?Amount=1&From=USD&To={$realm}";

				$rate_html = $this->curl->get($rate_url, $this->_user_agant, $curl_info);

				$rate_doc = new DOMDocument();
				$enc = mb_detect_encoding($rate_html, $this->encodings);
				@$rate_doc->loadHTML(mb_convert_encoding($rate_html, 'HTML-ENTITIES', $enc));
				$rate_xpath = new DOMXPath($rate_doc);

				$selector = $rate_xpath->query("//span[@class='uccResultAmount']");
				$currency[$realm] = $selector[0]->textContent;
			}

			$this->_linkSpaceDB();
			$ret = SpaceModel::insertCurrency($currency, $datetime);
			//var_dump($ret);exit;
		}

	}

}
