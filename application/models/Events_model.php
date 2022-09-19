<?php
/**
 *
 * User: satish4820
 * Date: 3/8/2018
 * Time: 9:41 PM
 */

class Events_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function getRows() {
        $this->db->select('e.*');
        $this->db->from('events e');
        $this->db->order_by('e.event_date', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_event_datatables($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "") {

        //define columns for searching
        $aColumns = array("e.title", "e.description", "e.location", "e.event_date");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
        $this->db->select("e.id,e.title,e.description,e.location,e.event_date");
        $this->db->from('events e');


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

    function get_user_datatables($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "") {

        //define columns for searching
        $aColumns = array("u.first_name", "u.last_name", "u.email_address", "u.gender","c.city","u.mobile");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
        $this->db->select("u.id,u.first_name,u.last_name,u.email_address,u.gender,c.city,u.mobile");
        $this->db->from('users u');
        $this->db->join('cities c','u.city_id=c.id','LEFT');
        $this->db->where('u.status','1');
        $this->db->where('u.deleted','0');


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

    function get_event($id) {
        return $this->db->get_where('events', array('id' => $id))->row_array();
    }

    function add_event($params) {
        // print_r($params);die;
        $this->db->insert('events', $params);
        return $this->db->insert_id();
    }

    function update_event($id, $params) {
        $this->db->where('id', $id);
        return $this->db->update('events', $params);
    }

    function delete_event($id) {
        $this->db->delete('events', array('id' => $id));
    }

    function get_medias($id,$media_type=""){
        $this->db->select("em.id,em.event_id,em.url,em.type");
        $this->db->from('event_media em');
        $this->db->where('em.event_id',$id);
        if($media_type != ""){
            $this->db->where('em.type',$media_type);
        }
        return $this->db->get()->result_array();
    }

    function delete_event_gallery($id){
        $this->db->delete('event_media', array('id' => $id));
    }

    function delete_youtube_url($id){
        $this->db->delete('event_media', array('event_id' => $id,'type'=>"YOUTUBE"));
    }

    function get_shared_users($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "") {

        //define columns for searching
        $aColumns = array("u.first_name", "u.last_name", "u.email_address", "u.gender","c.city","u.mobile");

        //$this->db->select("ue.id,ue.user_id,u.first_name,u.last_name,u.email_address,u.gender,c.city,u.mobile");
        $this->db->select("ue.user_id as id,u.first_name,u.last_name,u.email_address,u.gender,c.city,u.mobile");
        $this->db->from('user_events ue');
        $this->db->join('users u','u.id = ue.user_id','left');
        $this->db->join('cities c','c.id = u.city_id','left');


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

        $this->db->group_by('ue.user_id');
        $posts = $this->db->get()->result_array();

        if (count($posts)) {
            return $posts;
        } else {
            return array();
        }
    }

    function deleteSharedEvent($user_id,$event_id){
        $this->db->delete('user_events', array('event_id' => $event_id,'user_id'=>$user_id));
    }
}