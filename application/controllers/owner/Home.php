<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Owner_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'uadmin';
	private $current_page = 'uadmin/';
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'product_model',
			'order_model',
		));
	}
	public function index()
	{
		$day = (int) date('d');
		$month = (int) date('m');
		$year = (int) date('Y');

		$store_id = $this->ion_auth->store_id();

		$this->data["products"] = $this->product_model->count_product($store_id);
		$this->data["orders"] = $this->order_model->orders(null, null, $store_id, $day, $month, $year)->result();

		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Group";
		$this->data["header"] = "Group";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("admin/dashboard/content");
	}
}
