<?php

class Native_model extends CI_Model {

    function __construct() {

        parent::__construct();

        $this->table = "native";
    }

    function get_datatables($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "") {

        //define columns for searching
        $aColumns = array("native.id", "native.native");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
        $this->db->select("native.id,native.native,d.name as distinct,native.updated_at as updated");
        $this->db->from($this->table);
        $this->db->join('distincts d', 'd.id = native.distinct_id', 'left');
        $this->db->where('native.status','1');


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
        $query = "select id,native FROM $this->table where native like '%" . $searchStr . "%'";
        $exeQue = $this->db->query($query);
        return $resulArr = $exeQue->result_array();
    }

    function getRecordId($record, $distinct_id) {
        $this->db->select("id,native");
        $this->db->from($this->table);
        $this->db->where('native', $record);
        $this->db->where('distinct_id', $distinct_id);
        $post = $this->db->get()->row_array();
        if (!empty($post)) {
            return $post['id'];
        } else {
            $param = array();
            $param['distinct_id'] = $distinct_id;
            $param['native'] = $record;
            return $this->add_record($param);
        }
    }

    function getNativeByDistinct($id) {
        $this->db->select("id,native");
        $this->db->from($this->table);
        $this->db->where('distinct_id', $id);
        $posts = $this->db->get()->result_array();
        return $posts;
    }

    function get_record($id) {

        return $this->db->get_where($this->table, array('id' => $id))->row_array();
    }

    function add_record($params) {
        $this->incCount();
        $this->db->insert($this->table, $params);

        return $this->db->insert_id();
    }

    function update_record($id, $params) {
        $this->incCount();
        $this->db->where('id', $id);

        return $this->db->update($this->table, $params);
    }

    function delete_record($id) {
        $this->incCount();
        // $this->db->delete($this->table, array('id' => $id));
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['status'=>'0']);
    }

    function delete_multiple($ids) {
        $this->incCount();
        $this->db->where_in('id', $ids);
        // $this->db->delete($this->table);
        $this->db->update($this->table, ['status'=>'0']);
    }

    function incCount(){
        $this->db->query('UPDATE `updated_counts` SET `counts`= counts+1,updated_at="'.date('Y-m-d H:i:s').'" where table_name = "'.$this->table.'"');
    }

}
