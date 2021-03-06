<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product extends Customer_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'product';
	private $current_page = 'product/';
	private $store_id = null;
	public $user_id = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Product_services');
		$this->services = new Product_services;
		$this->load->library('services/Order_services');
		$this->order = new Order_services;
		$this->load->model(array(
			'group_model',
			'product_model',
			'category_model',
			'hold_order_model',
			'order_model',
			'item_model',
			'store_model',
		));
		$this->store_id = $this->session->userdata('store_id');
		$this->user_id = $this->session->userdata('user_id');
	}
	public function index()
	{
		$user_id = $this->user_id;
		$store_id = $this->store_id;

		$store = $this->store_model->store($store_id)->row();


		$this->data['user_id'] = $user_id;
		$this->data['store'] = $store;
		$this->data['categories'] = $this->category_model->categories(null, null, $store_id)->result();
		$this->data['popular_product'] = $this->product_model->popularity_product($store_id)->result();

		$this->data['products'] = $this->product_model->products(null, null, $store_id)->result();

		if ($this->hold_order_model->order_by_user_id($user_id)->result() == NULL)
			$this->data['qty_order'] = 0;
		else
			$this->data['qty_order'] = count($this->hold_order_model->order_by_user_id($user_id)->result());


		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Group";
		$this->data["header"] = "Group";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("customer/plain_content");
	}
	public function detail_order()
	{
		$user_id = $this->user_id;
		$store_id = $this->store_id;

		$store = $this->store_model->store($store_id)->row();

		$table = $this->services->get_table_hold_order_config($this->current_page);
		$table["rows"] = $this->hold_order_model->order_by_user_id($user_id)->result();
		// var_dump($table["rows"]);
		// die;
		if ($table["rows"] == null)
			redirect(site_url($this->current_page));

		$table = $this->load->view('templates/tables/plain_table_order', $table, true);
		$this->data["contents"] = $table;
		#######################################################

		$this->data['user_id'] = $user_id;
		$this->data['store'] = $store;
		// $this->data['hold_order'] = $this->hold_order_model->order_by_user_id($user_id)->result();

		if ($this->hold_order_model->order_by_user_id($user_id)->result() == NULL)
			$this->data['qty_order'] = 0;
		else
			$this->data['qty_order'] = count($this->hold_order_model->order_by_user_id($user_id)->result());

		$alert = $this->session->flashdata('alert');
		$this->data["url"] = base_url('product/order');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Group";
		$this->data["header"] = "Group";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("customer/cart");
	}
	public function list_order()
	{
		$user_id = $this->user_id;
		$store_id = $this->store_id;

		$store = $this->store_model->store($store_id)->row();

		if ($this->hold_order_model->order_by_user_id($user_id)->result() == NULL)
			$this->data['qty_order'] = 0;
		else
			$this->data['qty_order'] = count($this->hold_order_model->order_by_user_id($user_id)->result());

		$table = $this->order->get_table_config_customer_no_action($this->current_page);
		$table["rows"] = $this->order_model->order_by_customer_id($user_id)->result();
		$table["status"] = ['Pesanan baru', 'Sedang dibuat', 'Sudah diantar', 'Sudah dibayar'];
		if ($table["rows"] == null)
			redirect(site_url($this->current_page));

		$table = $this->load->view('templates/tables/plain_table_status', $table, true);
		$this->data["contents"] = $table;
		$this->data['user_id'] = $user_id;
		$this->data['store'] = $store;

		$this->render("customer/order");
	}
	public function order()
	{
		$user_id = $this->user_id;
		$store_id = $this->store_id;

		$data_order = [
			'customer_id' => $user_id,
			'store_id' => $store_id,
			'code' => "order_" . $user_id . '_' . date('d-m-Y'),
			'discount' => 0,
			'date' => date('Y-m-d'),
			'timestamp' => time(),
			'status' => 0,
			'message' => $this->input->post('message'),
		];
		$order_id = $this->order_model->create($data_order);
		$total =  $this->input->post('total_order');
		for ($i = 1; $i < $total; $i++) {
			$data[$i - 1]['order_id'] = $order_id;
			$data[$i - 1]['product_id'] = $this->input->post('product_id_' . $i);
			$data[$i - 1]['varian_id'] = $this->input->post('varian_id_' . $i);
			$data[$i - 1]['quantity'] = $this->input->post('quantity_' . $i);
			$data[$i - 1]['cost'] = $this->input->post('price_' . $i) * $this->input->post('quantity_' . $i);

			$data_param['id'] = $this->input->post('id_' . $i);
			$this->hold_order_model->delete($data_param);

			$product = $this->product_model->product($this->input->post('product_id_' . $i))->row();
			$data_product = [
				'qty' => $product->qty - $this->input->post('quantity_' . $i),
			];
			$param['id'] = $product->id;
			$this->product_model->update($data_product, $param);
		}
		if ($this->item_model->insert_batch($data)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->item_model->messages()));
			redirect(site_url($this->current_page));
		}
	}
	public function add_hold_order()
	{
		// if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
		$this->form_validation->set_rules('product_id', 'Produk', 'trim|required');
		$this->form_validation->set_rules('quantity', 'Jumlah', 'trim|required');
		if ($this->form_validation->run() === TRUE) {
			$data['customer_id'] = $this->input->post('user_id');
			$data['product_id'] = $this->input->post('product_id');
			$data['quantity'] = $this->input->post('quantity');

			if ($this->hold_order_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->hold_order_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->hold_order_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->hold_order_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->hold_order_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		// redirect(site_url($this->current_page));
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules('quantity', 'Jumlah', 'trim|required');
		if ($this->form_validation->run() === TRUE) {
			$data['quantity'] = $this->input->post('quantity');

			$data_param['id'] = $this->input->post('id');
			if ($this->hold_order_model->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->hold_order_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->hold_order_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->hold_order_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->hold_order_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page . 'detail_order'));
	}

	public function delete($id)
	{
		// if (!($_POST)) redirect(site_url($this->current_page));
		$data_param['id'] 	= $id;
		if ($this->hold_order_model->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->hold_order_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->hold_order_model->errors()));
		}
		redirect(site_url($this->current_page . 'detail_order'));
	}
}
