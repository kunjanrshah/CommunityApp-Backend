<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetListLocalCommunity extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $local = $this->model_name->getListLocalCommunity($date);
        $deletedids = $this->model_name->getDeleted('local_community');
        $lastUpdated = $this->model_name->getLastUpdated('local_community');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($local)) {
            $succes = array('success' => true, 'message' => $this->config->item('data_retried'), 'data' => $local,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($local)) {
            $succes = array('success' => true, 'message' => $this->config->item('data_retried'), 'data' => $local,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('data_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}