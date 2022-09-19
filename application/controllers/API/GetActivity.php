<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetActivity extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $activity = $this->model_name->getActivity($date);
        $deletedids = $this->model_name->getDeleted('current_activity');
        $lastUpdated = $this->model_name->getLastUpdated('current_activity');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($activity)) {
            $succes = array('success' => true, 'message' => $this->config->item('activity_retried'), 'data' => $activity,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($activity)){
            $succes = array('success' => true, 'message' => $this->config->item('activity_retried'), 'data' => $activity,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('activity_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}