<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product extends Owner_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'owner';
	private $current_page = 'owner/product/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Product_services');
		$this->services = new Product_services;
		$this->load->model(array(
			'product_model',
		));
	}
	public function index()
	{
		$store_id = $this->ion_auth->store_id();
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . '/index';
		$pagination['total_records'] = $this->product_model->count_product($store_id);
		$pagination['limit_per_page'] = 10;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config($this->current_page);
		$table["rows"] = $this->product_model->products($pagination['start_record'], $pagination['limit_per_page'], $store_id)->result();
		$table = $this->load->view('templates/tables/plain_table_image', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah Produk",
			"modal_id" => "add_product_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => $this->services->get_form_data($store_id),
			'data' => NULL
		);

		$add_menu = $this->load->view('templates/actions/modal_form_multipart', $add_menu, true);

		$this->data["header_button"] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Produk";
		$this->data["header"] = "Produk";
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
			$data['category_id'] = $this->input->post('category_id');
			$data['price'] = $this->input->post('price');
			$data['qty'] = $this->input->post('qty');
			$data['store_id'] = $this->input->post('store_id');

			$this->load->library('upload'); // Load librari upload
			$config = $this->services->get_photo_upload_config($data['name']);

			$this->upload->initialize($config);
			// echo var_dump($data); return;
			if ($_FILES['image']['name'] != "")
				if ($this->upload->do_upload("image")) {
					$data['image'] = $this->upload->data()['file_name'];
				} else {
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->upload->display_errors()));
					redirect(site_url($this->current_page));
				}
			if ($this->product_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->product_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->product_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->product_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->product_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['name'] = $this->input->post('name');
			$data['category_id'] = $this->input->post('category_id');
			$data['price'] = $this->input->post('price');
			$data['qty'] = $this->input->post('qty');

			$this->load->library('upload'); // Load librari upload
			$config = $this->services->get_photo_upload_config($data['name']);

			$this->upload->initialize($config);
			// echo var_dump( $_FILES ); return;
			if ($_FILES['image']['name'] != "") //if image not null
				if ($this->upload->do_upload("image")) {
					$data['image'] = $this->upload->data()["file_name"];
					if ($this->input->post('image_old') != 'default.jpg')
						if (!@unlink($config['upload_path'] . $this->input->post('image_old')));
				} else {
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->upload->display_errors()));
					redirect(site_url($this->current_page));
				}

			$data_param['id'] = $this->input->post('id');
			if ($this->product_model->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->product_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->product_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->product_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->product_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$this->load->library('upload'); // Load librari upload
		$config = $this->services->get_photo_upload_config($data['name']);

		$data_param['id'] 	= $this->input->post('id');
		if ($this->product_model->delete($data_param)) {
			if (!@unlink($config['upload_path'] . $this->input->post('image'))) return;
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->product_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->product_model->errors()));
		}
		redirect(site_url($this->current_page));
	}
}
