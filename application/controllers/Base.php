<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'models/inc.php';

class Base extends CI_Controller
{
	public function __construct() {
		parent::__construct();
	}

	protected function _errRtn($errcode) {
		$errmsgs = array (
		);
		return array(
			'errcode' => $errcode,
			'errmsg' => $errmsgs[$errcode],
		);
	}

	protected function _linkSpaceDB() {
		global $_space_db;
		$_space_db = null;
		$dbLink = "mysql:host=".DB_HOST.";dbname=".DB_DATABASE.";charset=utf8";
    $_space_db = new PDO($dbLink, DB_USERNAME, DB_PASSWORD);
		
		return ($_space_db) ? true : false;
	}

}
