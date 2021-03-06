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
    if (!$this->delete_foreign($data_param, ['varian_model', 'item_model', 'hold_order_model'])) {
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
  public function products($start = 0, $limit = NULL, $store_id = NULL)
  {
    $this->select($this->table . '.*');
    $this->select('category.name AS category_name');
    $this->select(" CONCAT( '" . base_url() . 'uploads/product/' . "' , " . $this->table . ".image )  as _image");
    $this->select(" CONCAT( 'Rp. ' , " . $this->table . ".price ) as _price");
    $this->select($this->table . '.image as image_old');


    // $this->select('varian');
    if (isset($limit)) {
      $this->limit($limit);
    }
    $this->join(
      'category',
      'category.id = product.category_id',
      'inner'
    );
    if ($store_id)
      $this->where('product.store_id', $store_id);
    $this->offset($start);
    $this->db->order_by($this->table . '.category_id', 'asc');
    $this->db->order_by($this->table . '.name', 'asc');
    return $this->fetch_data();
  }
  public function count_product($store_id = null)
  {
    return count($this->products(null, null, $store_id)->result());
  }
  public function popularity_product($store_id = null)
  {
    $this->db->select('product.*');
    $this->db->select(" CONCAT( '" . base_url() . 'uploads/product/' . "' , " . $this->table . ".image )  as _image");
    $this->db->select('category.name AS category_name');
    $this->db->select('count(item.id) AS popularity');
    $this->db->from('product');
    $this->db->join(
      'item',
      'item.product_id = product.id',
      'join'
    );
    $this->db->join(
      'category',
      'category.id = product.category_id',
      'join'
    );
    if ($store_id)
      $this->db->where('product.store_id', $store_id);
    $this->db->group_by('product_id');
    $this->db->order_by('popularity', 'desc');
    return $this->db->get();
  }
}
