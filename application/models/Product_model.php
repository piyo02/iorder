<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends MY_Model
{
  protected $table = "product";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('product_id');
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
    if (!$this->delete_foreign($data_param, ['taste_model', 'item_model', 'hold_order_model'])) {
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
   * product
   *
   * @param int|array|null $id = id_products
   * @return static
   * @author madukubah
   */
  public function product($id = NULL)
  {
    if (isset($id)) {
      $this->where($this->table . '.id', $id);
    }

    $this->limit(1);
    $this->order_by($this->table . '.id', 'desc');

    $this->products();

    return $this;
  }
  // /**
  //  * products
  //  *
  //  *
  //  * @return static
  //  * @author madukubah
  //  */
  // public function products(  )
  // {

  //     $this->order_by($this->table.'.id', 'asc');
  //     return $this->fetch_data();
  // }

  /**
   * products
   *
   *
   * @return static
   * @author madukubah
   */
  public function products($start = 0, $limit = NULL)
  {
    $this->select($this->table . '.*');
    $this->select('category.name AS category_name');
    // $this->select('taste');
    if (isset($limit)) {
      $this->limit($limit);
    }
    $this->join(
      'category',
      'category.id = product.category_id',
      'inner'
    );
    // $this->join(
    //   'taste',
    //   'taste.product_id = product.id',
    //   'inner'
    // );
    $this->offset($start);
    $this->order_by($this->table . '.id', 'asc');
    // $this->order_by('taste.product_id', 'asc');
    return $this->fetch_data();
  }
}
