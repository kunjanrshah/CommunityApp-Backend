<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetGotra extends REST_Controller
{
    function index($date="")
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $gotra = $this->model_name->getGotraList($date);
        $deletedids = $this->model_name->getDeleted('gotra');
        $lastUpdated = $this->model_name->getLastUpdated('gotra');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        // print_r($deletedids);die;
        if ($date!='' && isset($gotra)) {
            $succes = array('success' => true, 'message' => $this->config->item('gotra_retried'), 'data' => $gotra,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($gotra)) {
            $succes = array('success' => true, 'message' => $this->config->item('gotra_retried'), 'data' => $gotra,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('native_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}