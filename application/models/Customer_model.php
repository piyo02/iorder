<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer_model extends MY_Model
{
  protected $table = "customer";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('customer_id');
  }

  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author madukubah
   */
  public function create($data)
  {
    // Filter the data passed
    $data = $this->_filter_data($this->table, $data);

    $this->db->insert($this->table, $data);
    $id = $this->db->insert_id($this->table . '_id_seq');

    if (isset($id)) {
      $this->set_message("berhasil");
      return $id;
    }
    $this->set_error("gagal");
    return FALSE;
  }
  public function create_qr($data)
  {
    // Filter the data passed
    $data = $this->_filter_data('qrcode', $data);

    $this->db->insert('qrcode', $data);
    $id = $this->db->insert_id('qrcode' . '_id_seq');

    if (isset($id)) {
      $this->set_message("berhasil");
      return $id;
    }
    $this->set_error("gagal");
    return FALSE;
  }
  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function update($data, $data_param)
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);

    $this->db->update($this->table, $data, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("gagal");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");
    return TRUE;
  }
  /**
   * delete
   *
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function delete($data_param)
  {
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if (!$this->delete_foreign($data_param, ['menu_model'])) {
      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil"); //('group_delete_successful');
    return TRUE;
  }

  /**
   * customer
   *
   * @param int|array|null $id = id_customers
   * @return static
   * @author madukubah
   */
  public function customer($id = NULL)
  {
    if (isset($id)) {
      $this->where($this->table . '.id', $id);
    }

    $this->limit(1);
    $this->order_by($this->table . '.id', 'desc');

    $this->customers();

    return $this;
  }
  // /**
  //  * customers
  //  *
  //  *
  //  * @return static
  //  * @author madukubah
  //  */
  // public function customers(  )
  // {

  //     $this->order_by($this->table.'.id', 'asc');
  //     return $this->fetch_data();
  // }

  /**
   * customers
   *
   *
   * @return static
   * @author madukubah
   */
  public function customers($start = 0, $limit = NULL)
  {
    if (isset($limit)) {
      $this->limit($limit);
    }
    $this->offset($start);
    $this->order_by($this->table . '.id', 'asc');
    return $this->fetch_data();
  }
  public function qrcode($id = null)
  {
    $this->db->select('*');
    $this->db->select('
                      CASE
                        WHEN qrcode.group_id = 1 THEN "Admin"
                        WHEN qrcode.group_id = 2 THEN "Uadmin"
                        WHEN qrcode.group_id = 3 THEN "Pemilik Toko"
                        WHEN qrcode.group_id = 4 THEN "Pelanggan"
                      END AS group_name', FALSE);
    $this->db->select(" CONCAT( '" . base_url() . 'uploads/qrcode/' . "' , " . "qrcode.image )  as _image");
    if ($id)
      $this->db->where('id', $id);
    $this->db->order_by('qrcode.id', 'asc');
    return $this->db->get('qrcode');
  }
}
