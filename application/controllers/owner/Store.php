<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Store extends Owner_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'owner';
	private $current_page = 'owner/store/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Store_services');
		$this->services = new Store_services;
		$this->load->model(array(
			'store_model',
		));
	}
	public function index()
	{
		$user_id = $this->ion_auth->get_user_id();
		$form_data = $this->services->get_form_data_readonly($user_id);
		$form_data = $this->load->view('templates/form/plain_form_readonly', $form_data, TRUE);

		$this->data["user"] =  $this->store_model->store_by_user_id($user_id)->row();
		$this->data["contents"] =  $form_data;

		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Data Toko ";
		$this->data["header"] = "Data Toko ";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("user/store/content");
	}


	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');

			if ($this->store_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->store_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->store_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->store_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->store_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}
	public function upload_photo()
	{
		$this->load->library('upload'); // Load librari upload
		$config = $this->services->get_photo_upload_config($this->input->post('id'));
		// var_dump($config['upload_path'] . $this->input->post('image_old'));
		// die;
		$this->upload->initialize($config);
		// echo var_dump( $_FILES ); return;
		if ($_FILES['image']['name'] != "") //if image not null
		{
			if ($this->upload->do_upload("image")) {
				$data['image'] = $this->upload->data()["file_name"];

				$data_param['id'] = $this->input->post('id');
				$this->store_model->update($data, $data_param);
				if ($this->input->post('image_old') != 'default.jpg')
					if (!@unlink($config['upload_path'] . $this->input->post('image_old')));
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->ion_auth->messages()));
				redirect(site_url($this->current_page));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->upload->display_errors()));
				redirect(site_url($this->current_page));
			}
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->upload->display_errors()));
			redirect(site_url($this->current_page));
		}
	}
	public function edit()
	{
		$user_id = $this->ion_auth->get_user_id();

		$this->data["page_title"] = "Edit Profile Toko";
		$this->form_validation->set_rules('name',  'Nama Toko', 'trim|required');
		$this->form_validation->set_rules('email',  'Email', 'trim|required');
		$this->form_validation->set_rules('address', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('phone', 'Nomor Telepon', 'trim|required');
		$this->form_validation->set_rules('facebook_url', 'Akun Facebook', 'trim|required');
		$this->form_validation->set_rules('instagram_url', 'Akun Instagram', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'name' => $this->input->post('name'),
				'address' => $this->input->post('address'),
				'phone' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
				'facebook_url' => $this->input->post('facebook_url'),
				'instagram_url' => $this->input->post('instagram_url'),
			);

			if ($this->input->post('id') != null) {
				$data_param['id'] = $this->input->post('id');
				if ($this->store_model->update($data, $data_param)) {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->store_model->messages()));
					redirect(site_url('owner/store'));
				} else {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->store_model->errors()));
					redirect(site_url('owner/store'));
				}
			} else {
				$data['user_id'] = $this->input->post('user_id');
				if ($this->store_model->create($data)) {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->store_model->messages()));
					redirect(site_url('owner/store'));
				} else {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->store_model->errors()));
					redirect(site_url('owner/store'));
				}
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->store_model->errors() ? $this->store_model->errors() : $this->session->flashdata('message')));
			if (!empty(validation_errors()) || $this->store_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Edit Toko ";
			$this->data["header"] = "Edit Toko ";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$form_data = $this->services->get_form_data($user_id);
			$form_data = $this->load->view('templates/form/plain_form', $form_data, TRUE);

			$this->data["user"] =  $this->store_model->store_by_user_id($user_id)->row();
			$this->data["contents"] =  $form_data;

			$edit_photo = array(
				"name" => "Ganti Foto",
				"modal_id" => "edit_photo_",
				"button_color" => "primary",
				"url" => site_url($this->current_page . "upload_photo/"),
				"form_data" => array(
					"image" => array(
						'type' => 'file',
						'label' => "Foto",
						'value' => "",
					),
					"id" => array(
						'type' => 'hidden',
						'label' => "Id",
						'value' => $this->data["user"]->id,
					),
					"image_old" => array(
						'type' => 'hidden',
						'label' => "Id",
						'value' => $this->data["user"]->image_old,
					),
					'data' => NULL
				),
			);

			$edit_photo = $this->load->view('templates/actions/modal_form_multipart', $edit_photo, true);

			$this->data["edit_photo"] =  $edit_photo;

			$this->render("user/store/content_form");
		}
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->store_model->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->store_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->store_model->errors()));
		}
		redirect(site_url($this->current_page));
	}
}
