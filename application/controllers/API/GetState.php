<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetState extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $state = $this->model_name->getStates($date);
        $deletedids = $this->model_name->getDeleted('states');
        $lastUpdated = $this->model_name->getLastUpdated('states');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($state)) {
            $succes = array('success' => true, 'message' => $this->config->item('state_retried'), 'data' => $state,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($state)){
            $succes = array('success' => true, 'message' => $this->config->item('state_retried'), 'data' => $state,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('state_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}