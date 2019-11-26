<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Order extends Owner_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'owner';
	private $current_page = 'owner/order/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Order_services');
		$this->services = new Order_services;
		$this->load->model(array(
			'order_model',
			'item_model',
		));
	}
	public function index()
	{
		$day = (int) date('d');
		$month = (int) date('m');
		$year = (int) date('Y');

		$store_id = $this->ion_auth->store_id();
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . '/index';
		$pagination['total_records'] = $this->order_model->record_count();
		$pagination['limit_per_page'] = 10;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config($this->current_page);
		$table["rows"] = $this->order_model->orders($pagination['start_record'], $pagination['limit_per_page'], $store_id, $day, $month, $year)->result();
		$table["status"] = ['Pesanan baru', 'Sedang dibuat', 'Sudah diantar', 'Sudah dibayar'];
		$table = $this->load->view('templates/tables/plain_table_status', $table, true);
		$this->data["contents"] = $table;
		$form_filter = array(
			"form_data" => array(
				"name" => array(
					'type' => 'select',
					'label' => "",
					'options' => array()
				),
			)
		);

		$form_filter = $this->load->view('templates/form/plain_form_horizontal', $form_filter, true);

		// $this->data["header_button"] =  $form_filter;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Pesanan";
		$this->data["header"] = "Pesanan";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}
	public function detail_order($order_id)
	{
		$table = $this->services->get_table_config_no_action($this->current_page);
		$table["rows"] = $this->item_model->item_by_order_id($order_id)->result();
		// var_dump($table["rows"]);
		// die;
		$table["status"] = ['Pesanan baru', 'Sedang dibuat', 'Sudah diantar', 'Sudah dibayar'];
		$table = $this->load->view('templates/tables/plain_table_status', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => 'Kembali',
			"type" => "link",
			"url" => site_url($this->current_page),
			"button_color" => "primary",
			"title" => "Group",
			"data_name" => "name",
		);

		$add_menu = $this->load->view('templates/actions/link', $add_menu, true);

		$this->data["header_button"] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Group";
		$this->data["header"] = "Group";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');

			if ($this->order_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->order_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->order_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->order_model->errors() ? $this->order_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->order_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		if ($this->form_validation->run() === TRUE) {
			$data['status'] = $this->input->post('status');
			// $data['description'] = $this->input->post('description');

			$data_param['id'] = $this->input->post('id');

			if ($this->order_model->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->order_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->order_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->order_model->errors() ? $this->order_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->order_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->order_model->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->order_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->order_model->errors()));
		}
		redirect(site_url($this->current_page));
	}
}
