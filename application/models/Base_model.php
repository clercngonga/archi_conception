<?php

    class Base_model extends CI_Model{

        protected $table;

        // Insert data
        public function add($params){
            $this->db->insert($this->table, $params);
            $id = $this->db->insert_id();
            if($id){
                log_activity($this->table, $id, 'add_success');
                return $id;
            } else {
                return $false;
            }
        }

        // Update data
        public function update($params, $id){
            $this->db->where($this->table ."_id", $id);
            $update = $this->db->update($this->table, $params);
            if($update){
                log_activity($this->table, $id, 'update_success');
                return $update;
            } else {
                return false;
            }
        }

        // Get element
        /*
        Format data to use this method :

        id : integer
        where : array()
        join : array(array())
        sum : string (field_name)
        count : bool (true or false)
        group_by : string (field_name)
        order_by : array('field', 'type') Ex: array('field' => 'id', 'type' => 'desc')
        limit : array('start', 'end') 
        or_where : array();
        select : string

        */
        public function get($id = null, $where = null, $join = null, $sum = null, $count = null, $group_by = null, $order_by = null, $limit = null, $or_where = null, $select = null){ 
            if(!empty($join)){
                foreach($join as $j){
                    if(!empty($j['type'])){
                        $this->db->join($j['table'], $j['join'], $j['type']);
                    } else {
                        $this->db->join($j['table'], $j['join']);
                    }
                }
            }

            !empty($id) ? $this->db->where($this->table . "_id", $id) : '';
            !empty($where) ? $this->db->where($where) : '';
            !empty($or_where) ? $this->db->or_where($or_where) : '';
            !empty($sum) ? $this->db->select_sum($sum) : '';
            !empty($count) ? $this->db->select('count(*) AS number') : '';
            !empty($group_by) ? $this->db->group_by($group_by) : '';
            !empty($order_by) ? $this->db->order_by($order_by['field'], $order_by['type']) : '';
            !empty($limit) ? $this->db->limit($limit['start'], $limit['end']) : '';
            !empty($select) ? $this->db->select($select) : '';
            return $this->db->get($this->table)->result_array();

        }

        public function delete($id){
            $this->db->where($this->table . "_id", $id);
            $delete = $this->db->delete($this->table);
            if($delete){
                log_activity($this->table, $id, 'delete_success');
                return $delete;
            } else {
                return false;
            } 
        }

    }

?>