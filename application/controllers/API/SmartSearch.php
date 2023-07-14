<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class SmartSearch extends REST_Controller
{
    function index() {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            
            $sLimit = "";
            $start = 0;
            $length = 10;
            $draw = 1;
            
            
            if (isset($data['start']) && $data['length'] != '-1') {
                $start = $data['start'];
                $length = $data['length'];
            }else {
                $start = 0;
                $length = 25;
            }
            
            
            $filterby = $data['filter_by'];
            $sub_community_id = $data['sub_community_id'];
            
            $sWhere = $data['search']['value'];
            
            $whereCondition = "";
            
            if(!empty($sub_community_id)) {
                $whereCondition.=" users.sub_community_id='".$sub_community_id."'";
            }
            
            $dataList = $this->Users_model->get_global_search("", $sWhere, $start, $length, "id", "DESC",$filterby, $whereCondition);
            $dataListCount = $this->Users_model->get_global_search("", $sWhere, "", "", "id", "DESC",$filterby, $whereCondition);
            
            
            
            if(!empty($dataList)){
                $totalMem = 0;
                foreach($dataList as $d) {
                    if(@$d['members'] !=''){
						$members =$d['members'];
					}else{
						$members =0;
					}
                    $totalMem = $totalMem + $members;
                }
                $response = array('success' => true, 'total_records' => count($dataListCount),"members" => $dataList);
                echo json_encode($response);
                exit;
            }else{
                $error = array('success' => false, 'message' => $this->config->item('data_not_found'));
                echo json_encode($error);
                exit;
            }
            
            
        } 
    }
}