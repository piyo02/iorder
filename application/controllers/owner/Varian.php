<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Varian extends Owner_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'owner';
	private $current_page = 'owner/varian/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Varian_services');
		$this->services = new Varian_services;
		$this->load->model(array(
			'varian_model',
			'product_model',
		));
	}
	public function index($id = null)
	{
		$table = $this->services->get_table_config($this->current_page);
		$table["rows"] = $this->varian_model->varian_by_product_id($id)->result();
		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah Varian Produk",
			"modal_id" => "add_varian_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => $this->services->get_form_data($id),
			'data' => NULL
		);

		$add_menu = $this->load->view('templates/actions/modal_form', $add_menu, true);
		$btn_back = array(
			"name" => 'Kembali',
			"type" => "link",
			"url" => site_url("owner/product"),
			"button_color" => "success",
			"title" => "Group",
			"data_name" => "name",
		);
		$btn_back = $this->load->view('templates/actions/link', $btn_back, true);

		$this->data["header_button"] =  $add_menu . ' ' . $btn_back;
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
			$data['product_id'] = $this->input->post('product_id');
			$data['varian'] = $this->input->post('varian');

			if ($this->varian_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->varian_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->varian_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->varian_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->varian_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page . 'index/' . $data['product_id']));
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['product_id'] = $this->input->post('product_id');
			$data['varian'] = $this->input->post('varian');

			$data_param['id'] = $this->input->post('id');

			if ($this->varian_model->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->varian_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->varian_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->varian_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->varian_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page . 'index/' . $data['product_id']));
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->varian_model->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->varian_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->varian_model->errors()));
		}
		redirect(site_url($this->current_page . 'index/' . $this->input->post('product_id')));
		redirect(site_url($this->current_page));
	}
}
