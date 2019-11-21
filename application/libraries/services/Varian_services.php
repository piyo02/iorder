<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Varian_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function select_product()
  {
    $this->load->model('product_model');
    $products = $this->product_model->products()->result();
    $select[] = "-- Pilih Produk --";
    foreach ($products as $key => $product) {
      $select[$product->id] = $product->name;
    }
    return $select;
  }
  public function get_table_config($_page, $start_number = 1)
  {
    $select = $this->select_product();
    $table["header"] = array(
      'product_name' => 'Nama Produk',
      'taste' => 'Varian Produk',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Edit',
        "type" => "modal_form",
        "modal_id" => "edit_",
        "url" => site_url($_page . "edit/"),
        "button_color" => "primary",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "product_id" => array(
            'type' => 'select',
            'label' => "Produk",
            'options' => $select,
          ),
          "taste" => array(
            'type' => 'text',
            'label' => "Varian Produk",
          ),
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
        'field' => 'description',
        'label' => 'description',
        'rules' =>  'trim|required',
      ),
    );

    return $config;
  }
  public function get_form_data()
  {
    $select = $this->select_product();
    $form_data = array(
      "product_id" => array(
        'type' => 'select',
        'label' => "Produk",
        'options' => $select,
      ),
      "taste" => array(
        'type' => 'text',
        'label' => "Varian Produk",
        'value' => "",
      ),
    );
    return $form_data;
  }
}
