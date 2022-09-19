<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetCommittee extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $Committee = $this->model_name->getCommittee($date);
        $deletedids = $this->model_name->getDeleted('committees');
        $lastUpdated = $this->model_name->getLastUpdated('committees');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        
        if ($date!='' && isset($Committee)) {
            $succes = array('success' => true, 'message' => $this->config->item('Committee_retried'), 'data' => $Committee,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($Committee))
        {
            $succes = array('success' => true, 'message' => $this->config->item('Committee_retried'), 'data' => $Committee,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('Committee_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}