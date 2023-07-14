<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetNative extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $native = $this->model_name->getNative($date);
        $deletedids = $this->model_name->getDeleted('native');
        $lastUpdated = $this->model_name->getLastUpdated('native');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }

        if ($date!='' && isset($native)) {
            $succes = array('success' => true, 'message' => $this->config->item('native_retried'), 'data' => $native,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($native)) {
            $succes = array('success' => true, 'message' => $this->config->item('native_retried'), 'data' => $native,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('native_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}