<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Store_model extends CI_Model {
    public function __construct()	{
        $this->load->database();
    }
    public function call_list_data_to_doi($from_date,$to_date){
        $store = 'CALL list_data_to_doi (?,?)';
        $query = $this->db->query($store,array($from_date, $to_date));
        return $query->result();
    }
    public function call_list_data_by_id_to_doi($id_todoi,$from_date,$to_date){
        $store = 'CALL list_data_by_id_to_doi (?,?,?)';
        $query = $this->db->query($store,array($id_todoi, $from_date, $to_date));
        return $query->result();
    }

    public function call_list_data_by_id_to_doi_id_loai_kinh($id_todoi,$id_loai,$from_date,$to_date){
        $store = 'CALL list_data_by_id_to_doi_id_loai_kinh (?,?,?,?)';
        $query = $this->db->query($store,array($id_todoi, $id_loai, $from_date, $to_date));
        return $query->result();
    }
}