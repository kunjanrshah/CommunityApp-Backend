<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetSurname extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $surname = $this->model_name->getSurname($date);
        $deletedids = $this->model_name->getDeleted('sub_casts');
        $lastUpdated = $this->model_name->getLastUpdated('sub_casts');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($surname)) {
            $succes = array('success' => true, 'message' => $this->config->item('surname_retried'), 'data' => $surname,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($surname)){
            $succes = array('success' => true, 'message' => $this->config->item('surname_retried'), 'data' => $surname,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('surname_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}