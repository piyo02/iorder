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
      // 'taste' => 'Varian Rasa',
      'price' => 'Harga',
      'qty' => 'Stok',
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
          // "taste" => array(
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
      //   'field' => 'taste',
      //   'label' => 'taste',
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
      // "taste" => array(
      //   'type' => 'text',
      //   'label' => "Varian Rasa",
      //   'value' => "",
      // ),
    );
    return $form_data;
  }
}
