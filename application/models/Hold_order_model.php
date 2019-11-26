<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hold_order_model extends MY_Model
{
  protected $table = "hold_order";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('menu_id');
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
    // if (!$this->delete_foreign($data_param, ['menu_model'])) {
    //   $this->set_error("gagal"); //('group_delete_unsuccessful');
    //   return FALSE;
    // }
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
   * order
   *
   * @param int|array|null $id = id_orders
   * @return static
   * @author madukubah
   */
  public function order($id = NULL)
  {
    if (isset($id)) {
      $this->where($this->table . '.id', $id);
    }

    $this->limit(1);
    $this->order_by($this->table . '.id', 'desc');

    $this->orders();

    return $this;
  }
  // /**
  //  * orders
  //  *
  //  *
  //  * @return static
  //  * @author madukubah
  //  */
  // public function orders(  )
  // {

  //     $this->order_by($this->table.'.id', 'asc');
  //     return $this->fetch_data();
  // }

  /**
   * orders
   *
   *
   * @return static
   * @author madukubah
   */
  public function orders($start = 0, $limit = NULL)
  {
    if (isset($limit)) {
      $this->limit($limit);
    }
    $this->offset($start);
    $this->order_by($this->table . '.id', 'asc');
    return $this->fetch_data();
  }
  public function order_by_user_id($user_id = nulll)
  {
    $this->select('hold_order.*');
    // $this->select('*');
    $this->select('product.name AS product_name');
    $this->select('product.price AS product_price');
    $this->select(" CONCAT( '" . base_url() . 'uploads/product/' . "' , " . "product.image )  as _image");
    if (isset($user_id)) {
      $this->where($this->table . '.customer_id', $user_id);
    }
    $this->join(
      'product',
      'product.id = hold_order.product_id',
      'inner'
    );
    $this->join(
      'varian',
      'varian.id = hold_order.varian_id',
      'left'
    );
    $this->orders();

    return $this;
  }
}
