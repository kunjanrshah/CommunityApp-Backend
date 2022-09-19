<?php

class Mossad_model extends CI_Model {

    function __construct() {

        parent::__construct();

        $this->table = "mosaads";
    }

    function get_datatables($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "") {

        //define columns for searching
        $aColumns = array("id", "name");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
        $this->db->select("id,name");
        $this->db->from($this->table);


        // Addtitional Filtering
        $sWhere = "";
        if ($searchParamVal != "") {
            $sWhere = "(";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }


        if (!empty($sWhere)) {
            $this->db->where($sWhere);
        }

        if (!empty($searchWhere)) {
            $this->db->where($searchWhere);
        }

        // $this->db->group_by('shortId');
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        if (!empty($orderBy) && !empty($orderByVal)) {
            $this->db->order_by($orderBy, $orderByVal);
        }

        $posts = $this->db->get()->result_array();

        //  echo $this->db->last_query();exit;

        if (count($posts)) {
            return $posts;
        } else {
            return array();
        }
    }

    function getAutoCompleteData($searchStr) {
        $query = "select id,name FROM $this->table where name like '%" . $searchStr . "%' and status = 1";
        $exeQue = $this->db->query($query);
        return $resulArr = $exeQue->result_array();
    }

    function getRecordId($record) {
        $this->db->select("id,name");
        $this->db->from($this->table);
        $this->db->where('name', $record);
        $post = $this->db->get()->row_array();
        if (!empty($post)) {
            return $post['id'];
        } else {
            $param = array();
            $param['name'] = $record;
            $param['created_on'] = date('Y-m-d H:i:s');
            return $this->add_record($param);
        }
    }

    function get_record($id) {

        return $this->db->get_where($this->table, array('id' => $id))->row_array();
    }

    function add_record($params) {

        $this->db->insert($this->table, $params);

        return $this->db->insert_id();
    }

    function update_record($id, $params) {

        $this->db->where('id', $id);

        return $this->db->update($this->table, $params);
    }

    function delete_record($id) {
        $this->db->delete($this->table, array('id' => $id));
    }

    function delete_multiple($ids) {
        $this->db->where_in('id', $ids);
        $this->db->delete($this->table);
    }

}
