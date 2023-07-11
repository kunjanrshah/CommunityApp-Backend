<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetOccupation extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $occupation = $this->model_name->getOccupation($date);
        $deletedids = $this->model_name->getDeleted('occupation');
        $lastUpdated = $this->model_name->getLastUpdated('occupation');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($occupation)) {
            $succes = array('success' => true, 'message' => $this->config->item('occupation_retried'), 'data' => $occupation,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($occupation)){
            $succes = array('success' => true, 'message' => $this->config->item('occupation_retried'), 'data' => $occupation,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('occupation_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}