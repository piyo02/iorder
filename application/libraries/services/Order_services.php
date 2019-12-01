<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Order_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_table_config_no_action($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Pesanan',
      // 'varian' => 'Varian Produk',
      'quantity' => 'Banyak Pesanan',
      'cost' => 'Total Harga',
    );
    $table["number"] = $start_number;
    return $table;
  }
  public function get_table_config_customer_no_action($_page, $start_number = 1)
  {
    $table["header"] = array(
      'code' => 'Kode Pesanan',
      'timestamp' => 'Tanggal',
      'message' => 'Keterangan',
      'status' => 'Status',
    );
    $table["number"] = $start_number;
    return $table;
  }
  public function get_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'code' => 'Kode Pesanan',
      'timestamp' => 'Tanggal',
      'message' => 'Keterangan',
      'status' => 'Status',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Lihat Pesanan',
        "type" => "link",
        "url" => site_url($_page . 'detail_order/'),
        "button_color" => "primary",
        "param" => "id",
        "title" => "Group",
        "data_name" => "name",
      ),
      array(
        "name" => 'Edit',
        "type" => "modal_form",
        "modal_id" => "edit_",
        "url" => site_url($_page . "edit/"),
        "button_color" => "success",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "status" => array(
            'type' => 'select',
            'label' => "Status",
            'options' => array(
              0 => 'Pesanan Baru',
              1 => 'Sedang dibuat',
              2 => 'Sudah diantar',
              3 => 'Sudah dibayar',
            ),
          ),
        ),
        "title" => "Group",
        "data_name" => "name",
      ),
      // array(
      //   "name" => 'X',
      //   "type" => "modal_delete",
      //   "modal_id" => "delete_",
      //   "url" => site_url($_page . "delete/"),
      //   "button_color" => "danger",
      //   "param" => "id",
      //   "form_data" => array(
      //     "id" => array(
      //       'type' => 'hidden',
      //       'label' => "id",
      //     ),
      //   ),
      //   "title" => "Group",
      //   "data_name" => "code",
      // ),
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
}
