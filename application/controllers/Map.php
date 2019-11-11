<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {

	public function index() {
		var_dump("index");
	}

	public function ramen() {
		$viewData = [];

		$viewData['key'] = "AIzaSyB4gprq7HbMpcHdMzkMGJ2dAwSJCROKh7k";

		$this->load->view('ramen', $viewData);
	}

}
