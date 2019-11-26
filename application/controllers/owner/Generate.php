<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Generate extends Owner_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'owner';
	private $current_page = 'owner/generate/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Qrcode_services');
		$this->services = new Qrcode_services;
		$this->load->model(array(
			'group_model',
			'customer_model',
		));
	}
	public function index()
	{
		$user_id = $this->ion_auth->get_user_id();
		$store_id = $this->ion_auth->store_id($user_id);

		$table = $this->services->get_table_config_no_action($this->current_page);
		$table["rows"] = $this->customer_model->qrcode()->result();
		$table = $this->load->view('templates/tables/plain_table_image', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah QR Code",
			"modal_id" => "add_group_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => array(
				"store_id" => array(
					'type' => 'hidden',
					'label' => "store_id",
					'value' => $store_id,
				),
				"name" => array(
					'type' => 'text',
					'label' => "QR Code",
					'value' => "",
				),
				'group_id' => array(
					'type' => 'select',
					'label' => "Group",
					'options' => array(
						3 => 'Pemilik Toko',
						4 => 'Pelanggan',
					),
				),
				"url" => array(
					'type' => 'text',
					'label' => "URL",
					'value' => "",
				),
			),
			'data' => NULL
		);

		$add_menu = $this->load->view('templates/actions/modal_form', $add_menu, true);

		$this->data["header_button"] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "QR Code";
		$this->data["header"] = "QR Code";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}


	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules('name', 'QR Code', 'trim|required');
		if ($this->form_validation->run() === TRUE) {
			$name = $this->input->post('name');

			$this->load->library('ciqrcode');

			$config['cacheable']	= true;
			$config['cachedir']		= './uploads/';
			$config['errorlog']		= './uploads/';
			$config['imagedir']		= './uploads/qrcode/';
			$config['quality']		= true;
			$config['size']			= '1024';
			$config['black']			= array(224, 255, 255);
			$config['white']			= array(70, 130, 180);
			$this->ciqrcode->initialize($config);

			$image_name = $name . '.png';

			$data['image'] = $image_name;
			$data['group_id'] =  $this->input->post('group_id');
			$data['store_id'] =  $this->input->post('store_id');
			$id = $this->customer_model->create_qr($data);
			if ($id) {
				$params['data'] = 'http://www.localhost/iorder/auth/qrcode?id=' . $id;
				$params['level'] = 'H';
				$params['size'] = 10;
				$params['savename'] = FCPATH . $config['imagedir'] . $image_name;
				$this->ciqrcode->generate($params);

				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->customer_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->customer_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->customer_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->customer_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
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
			$data['description'] = $this->input->post('description');

			$data_param['id'] = $this->input->post('id');

			if ($this->group_model->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->group_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->group_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->group_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->group_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->group_model->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->group_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->group_model->errors()));
		}
		redirect(site_url($this->current_page));
	}
}
