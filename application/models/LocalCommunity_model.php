<?php

class LocalCommunity_model extends CI_Model {

    function __construct() {

        parent::__construct();

        $this->table = "local_community";
    }

    function get_datatables($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "") {

        //define columns for searching
        $aColumns = array("local_community.id", "local_community.name");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
        $this->db->select("local_community.id,local_community.name,sub_community.name as sub_community,IF(local_community.updated_on != '', local_community.updated_on, local_community.created_on) as updated");
        $this->db->from($this->table);
        $this->db->join('sub_community', 'sub_community.id = ' . $this->table . '.sub_community_id', 'left');
        $this->db->where('local_community.status','1');

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

    function getRecordId($record, $sub_community_id) {
        $this->db->select("id,name");
        $this->db->from($this->table);
        $this->db->where('name', $record);
        $post = $this->db->get()->row_array();
        if (!empty($post)) {
            return $post['id'];
        } else {
            $param = array();
            $param['name'] = $record;
            $param['sub_community_id'] = $sub_community_id;
            $param['created_on'] = date('Y-m-d H:i:s');
            return $this->add_record($param);
        }
    }

    function getRecordsBySubcommunity($sub_community_id) {
        $this->db->select("id,name");
        $this->db->from($this->table);
        $this->db->where('sub_community_id', $sub_community_id);
        $this->db->where('status', 1);
        $posts = $this->db->get()->result_array();
        return $posts;
    }

    function getAdmins($local_community_id) {
        //$local = $this->get_record($local_community_id);
        $this->db->select("id,first_name,last_name,mobile,role,email_address");
        $this->db->from('users');
        $this->db->where('status', 1);
        //$this->db->where('(role = "SUB_ADMIN" AND sub_community_id = "' . $local['sub_community_id'] . '")');
        $this->db->where('(role = "LOCAL_ADMIN" AND local_community_id = "' . $local_community_id . '")');
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
