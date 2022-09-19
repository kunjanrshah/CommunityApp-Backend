<?php
//error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class SearchCommitteeUsers extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $user_id = $this->user_id;

            $data = $this->request_paramiters;
            
            if (!empty($data)) {
                // $users = $this->model_name->getCommitteeUsersBySearch($request);
                // $total_users = $this->model_name->getTotalCommitteeUsersBySearch($request);
                if (isset($data['start']) && $data['length'] != '-1') {
                    $start = $data['start'];
                    $length = $data['length'];
                }else {
                    $start = 0;
                    $length = 25;
                }
                $users = $this->Users_model->get_committee_member($start, $length, "id", "DESC",$data['filter_by']);
                $total_users = $this->Users_model->get_committee_member("", "", "id", "DESC",$data['filter_by']);
                if (!empty($users)) {
                    $succes = array('success' => true, 'message' => $this->config->item('user_data'), 'total_records' => count($total_users), 'members' => $users);
                    echo json_encode($succes);
                    exit;
                } else {
                    $error = array('success' => false, 'message' => $this->config->item('user_data_not_found'));
                    echo json_encode($error);
                    exit;
                }
            } else {
                $error = array('success' => false, 'message' => $this->config->item('required_missing'));
                echo json_encode($error);
                exit;
            }
        }
    }

}
