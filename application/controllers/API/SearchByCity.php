<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class SearchByCity extends REST_Controller
{
    function index()
    {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            
            $sLimit = "";
            $start = 0;
            $length = 10;
            $draw = 1;
            $filterby = $data['filter_by'];
            $alpha = $data['alpha'];
            
            if (isset($data['start']) && $data['length'] != '-1') {
                $start = $data['start'];
                $length = $data['length'];
            }else {
                $start = 0;
                $length = 25;
            }
            
            $sub_community_id = $data['sub_community_id'];
            $whereCondition = "";
            if(!empty($sub_community_id)){
                $whereCondition.=" users.sub_community_id='".$sub_community_id."'";
            }
           
            $sWhere = $data['search']['value'];
            $dataList = $this->Users_model->get_datatables_for_api("", $sWhere, $start, $length, "first_name", "ASC",$filterby,$alpha, $whereCondition);
            //$dataListCount = $this->Users_model->get_datatables_for_api_count("", $sWhere, "", "", "first_name", "ASC",$filterby,$alpha);
            $dataListTotal = $this->Users_model->get_datatables_for_api("", $sWhere, "", "", "", "",$filterby,$alpha, $whereCondition);
           
            $totalMem = 0;
           
            foreach($dataListTotal as $d) {
                $totalMem = $totalMem + $d['member_count'];
            }
            
            $response = array('success' => true, "totalHead" => intval(count($dataListTotal)), 'totalMem' => $totalMem , "members" => $dataList);
            echo json_encode($response);
            exit;
        } 
    }
}