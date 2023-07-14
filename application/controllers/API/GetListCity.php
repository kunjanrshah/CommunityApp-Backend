<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetListCity extends REST_Controller
{
    function index()
    {
        // echo "string";die;
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $city = $this->model_name->getListCity($date);
        $deletedids = $this->model_name->getDeleted('cities');
        $lastUpdated = $this->model_name->getLastUpdated('cities');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($city)) {
            $succes = array('success' => true, 'message' => $this->config->item('data_retried'), 'data' => $city,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($city)){
            $succes = array('success' => true, 'message' => $this->config->item('data_retried'), 'data' => $city,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('data_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}