<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_photo_upload_config($name = "")
  {
    // $filename = $name . "_" . time();
    $filename = $name . "_" . time();
    $upload_path = 'uploads/product/';

    $config['upload_path'] = './' . $upload_path;
    $config['image_path'] = base_url() . $upload_path;
    $config['allowed_types'] = "gif|jpg|png|jpeg";
    $config['overwrite'] = "true";
    // $config['max_size'] = "2048";
    $config['file_name'] = '' . $filename;

    return $config;
  }
  public function select_category()
  {
    $this->load->model('category_model');
    $categories = $this->category_model->categories()->result();
    $select[] = '-- Pilih Kategori --';
    foreach ($categories as $key => $category) {
      $select[$category->id] = $category->name;
    }
    return $select;
  }
  public function get_table_config($_page, $start_number = 1)
  {
    $select = $this->select_category();
    $table["header"] = array(
      'name' => 'Nama',
      'category_name' => 'Jenis',
      // 'varian' => 'Varian Rasa',
      'price' => 'Harga',
      'qty' => 'Stok',
      '_image' => 'Foto Produk',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Tambah Varian',
        "type" => "link",
        "url" => site_url("owner/varian/index/"),
        "button_color" => "primary",
        "param" => "id",
        "title" => "Group",
        "data_name" => "name",
      ),
      array(
        "name" => 'Edit',
        "type" => "modal_form_multipart",
        "modal_id" => "edit_",
        "url" => site_url($_page . "edit/"),
        "button_color" => "primary",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "image_old" => array(
            'type' => 'hidden',
            'label' => "Foto Produk",
          ),
          "name" => array(
            'type' => 'text',
            'label' => "Nama Produk",
          ),
          "category_id" => array(
            'type' => 'select',
            'label' => "Jenis",
            'options' => $select,
          ),
          "price" => array(
            'type' => 'number',
            'label' => "Harga Produk",
          ),
          "qty" => array(
            'type' => 'number',
            'label' => "Stok",
          ),
          "image" => array(
            'type' => 'file',
            'label' => "Foto Produk",
          ),
          // "varian" => array(
          //   'type' => 'text',
          //   'label' => "Varian Rasa",
          //   'value' => "",
          // ),
        ),
        "title" => "Group",
        "data_name" => "name",
      ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_",
        "url" => site_url($_page . "delete/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "image" => array(
            'type' => 'hidden',
            'label' => "foto",
          ),
        ),
        "title" => "Group",
        "data_name" => "name",
      ),
    );
    return $table;
  }
  public function validation_config()
  {
    $config = array(
      array(
        'field' => 'name',
        'label' => 'name',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'category_id',
        'label' => 'category',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'price',
        'label' => 'price',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'qty',
        'label' => 'qty',
        'rules' =>  'trim|required',
      ),
      // array(
      //   'field' => 'varian',
      //   'label' => 'varian',
      //   'rules' =>  'trim|required',
      // ),
    );

    return $config;
  }
  public function get_form_data()
  {
    $select = $this->select_category();
    $form_data = array(
      "name" => array(
        'type' => 'text',
        'label' => "Nama Produk",
        'value' => "",
      ),
      "category_id" => array(
        'type' => 'select',
        'label' => "Jenis",
        'options' => $select,
      ),
      "price" => array(
        'type' => 'number',
        'label' => "Harga Produk",
        'value' => "",
      ),
      "qty" => array(
        'type' => 'number',
        'label' => "Stok",
        'value' => "",
      ),
      "image" => array(
        'type' => 'file',
        'label' => "Foto Produk",
      ),
      // "varian" => array(
      //   'type' => 'text',
      //   'label' => "Varian Rasa",
      //   'value' => "",
      // ),
    );
    return $form_data;
  }
  public function get_table_hold_order_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'product_name' => 'Nama Produk',
      'product_price' => 'Harga',
      'quantity' => 'Banyak Pesanan',
      // '_image' => 'Foto Produk',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_",
        "url" => site_url($_page . "delete/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
        ),
        "title" => "Group",
        "data_name" => "product_name",
      ),
    );
    return $table;
  }
}
