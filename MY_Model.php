<?php

/**
 *  MY_Model Class
 * rich tools for modeling and working with your database table 
 * basic CRUD methods
 * @package	CodeIgniter
 * @subpackage	Model Class
 * @category	Class
 * @author  Arafat  Thabet  <arafat.733011506@gmail.com>
 * @version 2.0.0
 */
class MY_Model extends CI_Model
{

    /**
     * The model's default table name.
     *
     * @var string;
     */
    protected   $table = '';

    /**
     * The model's default primary key.
     *
     * @var string
     */
    protected $primaryKey    = 'id';
    protected $createdField  = ''; //created_at
    protected $updatedField  = '';  //updated_at
    protected $deletedField  = '';  //deleted

    protected $useSoftDeletes = false;


    public function __construct()
    {
        parent::__construct();
    }
    // get first row
    public function getFirst()
    {
        return $this->db->limit(1)->order_by($this->primaryKey, "asc")->get($this->table)->row();
    }
        // get last row

    public function getLast()
    {
        return $this->db->limit(1)->order_by($this->primaryKey, "desc")->get($this->table)->row();
    }
    public function findAll($limit = null, $offset = 0)
    {
        if ($limit > 0)
            $this->db->limit($limit);
        if ($offset >= 0)
            $this->db->offset($offset);
        return $this->db->get($this->table)->result();
    }


    public function find($id)
    {
        if (is_int($id) && !is_array($id)) {
            $this->db->where($this->primaryKey, intval($id));
            return $this->db->get($this->table)->row();
        } elseif (is_array($id)) {
            $this->db->where_in($this->primaryKey, $id);
            return $this->db->get($this->table)->result();
        } else
            return [];
    }
    public function getWhere($where)
    {
        return $this->db->where($where)->get($this->table)->result();
    }
    public function findWhere($where)
    {
        return $this->db->where($where)->get($this->table)->row();
    }
    public function getOne($field_val, $field_name = null)
    {
        if (empty($field_name))
            $this->db->where($this->primaryKey, $field_val);
        else
            $this->db->where($field_name, $field_val);

        return $this->db->get($this->table)->result();
    }
    // ------------------------------------------------------------------------

    /**
     *
     * Insert Values to DataBase
     *
     * @access    public
     * @param array $data [data => array of data to insert]
     * @return    bool
     */
    public  function insert($data = array())
    {
        if (!is_array($data) or (!$this->table)) {
            return false;
        }
        $items = array();

        $fields = $this->db->list_fields($this->table);
        // Check if table contain data item
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                if (is_array($data[$field])) {

                    continue;
                }
                $items[$field] = $data[$field];
            }
            if ($field == $this->createdField)
                $data[$this->createdField] = date('Y-m-d H:i:s');
        }

        if ($this->db->insert($this->table, $items)) {

            return $this->db->insert_id();
        }
        return false;
    }
    // ------------------------------------------------------------------------
    /**
     *
     * update table in DataBase
     *
     * @access    public
     * @params
    [data => array of data to insert]
    [ params => parameter of sql
    forexample >>>>  array("name"=>$name, "id"=>$id)
    if you want to use the PRIMARY KEY only you can set parameter to object  and it will be update by the Primary key of table  ]
     * @return    bool
     */
    public  function update($data = array(), $params = array())
    {
        if (!is_array($params)) {
            $key = Smart::PrimaryKey($this->table);
            if ($key) {
                $params = array(
                    $key => $params,
                );
            }
        }
        if (!is_array($data) or !is_array($params) or (!$this->table)) {
            return false;
        }
        $fields = $this->db->list_fields($this->table);
        // Check if table contain data item
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                if (is_array($data[$field])) {

                    continue;
                }
                $items[$field] = $data[$field];
            }
            if ($field == $this->updatedField)
                $data[$this->updatedField] = date('Y-m-d H:i:s');
        }
        return $this->db->update($this->table, $data, $params);
    }
    // ------------------------------------------------------------------------
    /**
     *
     * Delete row from DataBase
     *
     * @access    public
     * @params
    params => parameter of sql
    forexample
    array("name"=>$name, "id"=>$id)
    if you want to use the PRIMARY KEY only you can set parameter to object  and it will be delete by the Primary key of table
     * @return    bool
     */
    public  function delete($params = array())
    {
        if (!is_array($params)) {
            $key = $this->primaryKey;
            if ($key) {
                $params = array(
                    $key => $params,
                );
            }
        }
        if (!is_array($params) or (!$this->table)) {
            return false;
        }
        if ($this->deletedField && $this->useSoftDeletes == true)
            return $this->softDelete($params);
        return $this->db->delete($this->table, $params);
    }
    public  function softDelete($where = array())
    {
        if (!is_array($where)) {
            $key = $this->primaryKey;
            if ($key) {
                $params = array(
                    $key => $where,
                );
            }
        }
        if (!is_array($where) or (!$this->table)) {
            return false;
        }
        $data["{$this->deletedField}"] = 1;

        return $this->update($data, $where);
    }
    // ------------------------------------------------------------------------

    /**
     *
     * Insert  OR Update Values to table
     *
     * @access    public
     * @param array $data [data => array of data to insert OR update]
     * @param int $id      [id => if id ? record will be update table : insert values to table]
     *     [if id is integer ? id will be a primary Key of table OR Array ( "id"=> value ,"name"=>value,etc)]
     * @return    int
     */
    public  function save($data = array(), $id = false)
    {
        if ($id) {
            $this->update($data, $id);
            return $id;
        } else {
            return $this->insert($data);
        }
        return $this->insert($data);
    }
}
