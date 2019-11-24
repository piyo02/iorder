<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Store_services
{
  // user var
  protected $id;
  protected $user_id;
  protected $name;
  protected $address;
  protected $email;
  protected $phone;
  protected $facebook_url;
  protected $instagram_url;
  protected $image;

  function __construct()
  {
    $this->id             = '';
    $this->user_id        = '';
    $this->name           = "";
    $this->address        = "";
    $this->email          = "";
    $this->phone          = "";
    $this->facebook_url   = "";
    $this->instagram_url  = '';
    $this->image          = '';
  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_photo_upload_config($user_id = null)
  {
    $filename = "STORE_" . $user_id . "_" . time();
    $upload_path = 'uploads/store/';

    $config['upload_path'] = './' . $upload_path;
    $config['image_path'] = base_url() . $upload_path;
    $config['allowed_types'] = "gif|jpg|png|jpeg";
    $config['overwrite'] = "true";
    $config['max_size'] = "2048";
    $config['file_name'] = '' . $filename;

    return $config;
  }

  public function get_table_config($_page, $start_number = 1)
  {
    // sesuaikan nama tabel header yang akan d tampilkan dengan nama atribut dari tabel yang ada dalam database
    $table["header"] = array(
      'username' => 'username',
      'group_name' => 'Group',
      'user_fullname' => 'Nama Lengkap',
      'phone' => 'No Telepon',
      'address' => 'Alamat',
      'email' => 'Email',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => "Detail",
        "type" => "link",
        "url" => site_url($_page . "detail/"),
        "button_color" => "primary",
        "param" => "id",
      ),
      array(
        "name" => "Edit",
        "type" => "link",
        "url" => site_url($_page . "edit/"),
        "button_color" => "primary",
        "param" => "id",
      ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_category_",
        "url" => site_url($_page . "delete/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "group_id" => array(
            'type' => 'hidden',
            'label' => "group_id",
          ),
        ),
        "title" => "User",
        "data_name" => "user_fullname",
      ),
    );
    return $table;
  }

  /**
   * get_form_data
   *
   * @return array
   * @author madukubah
   **/
  public function get_form_data_readonly($user_id = -1)
  {
    $this->user_id        = $user_id;
    $this->load->model('store_model');
    $store = $this->store_model->store_by_user_id($user_id)->row();
    if ($user_id != -1 && $store !== NULL) {
      $this->id             = $store->id;
      $this->name           = $store->name;
      $this->address        = $store->address;
      $this->email          = $store->email;
      $this->phone          = $store->phone;
      $this->facebook_url   = $store->facebook_url;
      $this->instagram_url  = $store->instagram_url;
      $this->image          = $store->image;
    }

    $_data["form_data"] = array(
      "id" => array(
        'type' => 'hidden',
        'label' => "ID",
        'value' => $this->form_validation->set_value('id', $this->id),
      ),
      "user_id" => array(
        'type' => 'hidden',
        'label' => "ID",
        'value' => $this->form_validation->set_value('user_id', $this->user_id),
      ),
      "name" => array(
        'type' => 'text',
        'label' => "Nama Toko",
        'value' => $this->form_validation->set_value('name', $this->name),
      ),
      "email" => array(
        'type' => 'text',
        'label' => "Email",
        'value' => $this->form_validation->set_value('email', $this->email),
      ),
      "address" => array(
        'type' => 'text',
        'label' => "Alamat",
        'value' => $this->form_validation->set_value('address', $this->address),
      ),
      "phone" => array(
        'type' => 'number',
        'label' => "Nomor Telepon",
        'value' => $this->form_validation->set_value('phone', $this->phone),
      ),
      "facebook_url" => array(
        'type' => 'text',
        'label' => "Akun Facebook",
        'value' => $this->form_validation->set_value('facebook_url', $this->facebook_url),
      ),
      "instagram_url" => array(
        'type' => 'text',
        'label' => "Akun Instagram",
        'value' => $this->form_validation->set_value('instagram_url', $this->instagram_url),
      ),
    );
    return $_data;
  }
  public function get_form_data($user_id = -1)
  {
    $this->user_id        = $user_id;
    $this->load->model('store_model');
    $store = $this->store_model->store_by_user_id($user_id)->row();

    if ($user_id != -1 && $store !== NULL) {
      $this->id             = $store->id;
      $this->name           = $store->name;
      $this->address        = $store->address;
      $this->email          = $store->email;
      $this->phone          = $store->phone;
      $this->facebook_url   = $store->facebook_url;
      $this->instagram_url  = $store->instagram_url;
      $this->image          = $store->image;
    }
    // echo var_dump($user);

    $_data["form_data"] = array(
      "id" => array(
        'type' => 'hidden',
        'label' => "ID",
        'value' => $this->form_validation->set_value('id', $this->id),
      ),
      "user_id" => array(
        'type' => 'hidden',
        'label' => "ID",
        'value' => $this->form_validation->set_value('user_id', $this->user_id),
      ),
      "name" => array(
        'type' => 'text',
        'label' => "Nama Toko",
        'value' => $this->form_validation->set_value('name', $this->name),
      ),
      "address" => array(
        'type' => 'text',
        'label' => "Alamat",
        'value' => $this->form_validation->set_value('address', $this->address),
      ),
      "email" => array(
        'type' => 'text',
        'label' => "Email",
        'value' => $this->form_validation->set_value('email', $this->email),
      ),
      "phone" => array(
        'type' => 'number',
        'label' => "Nomor Telepon",
        'value' => $this->form_validation->set_value('phone', $this->phone),
      ),
      "facebook_url" => array(
        'type' => 'text',
        'label' => "Akun Facebook",
        'value' => $this->form_validation->set_value('facebook_url', $this->facebook_url),
      ),
      "instagram_url" => array(
        'type' => 'text',
        'label' => "Akun Instagram",
        'value' => $this->form_validation->set_value('instagram_url', $this->instagram_url),
      ),
    );
    return $_data;
  }
}
