<?php

/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class GetUpdatedVersion extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {
            
            $data = $this->request_paramiters;
            //echo "<pre>";print_r($data);die;
            //echo get_class($this->config);die;
            if(!isset($data['version'])){
                $error = array('success' => false, 'message' => $this->config->item('required_missing'));
                echo json_encode($error);
                exit;
            }
            
            $appDataInfo = $this->model_name->getAppVersionData();
            //echo "<pre>";print_r($appDataInfo);die;
            // if($data['insert'] == '1'){
            //     if(empty($appDataInfo)){
            //         // Insert data
            //         $appData = $this->model_name->setAppVersionData($data['version'],1);
            //         $message = $this->config->item('app_version_insert');
                    
            //     } else {
            //         // Update data
            //         $appData = $this->model_name->setAppVersionData($data['version'],0);
            //         $message = $this->config->item('app_version_update');
            //     }
                    
            // } else {
            // }
            
            // if(!empty($appDataInfo)){
                
            //     if($appDataInfo[0]['version'] == $data['version']){
            //         $appData = '1';
            //         $message = $this->config->item('app_version_match');
            //     } else {
            //         $appData = '0';
            //         $message = $this->config->item('app_version_not_match');
            //     }
            // }

            if(!empty($appDataInfo)){
                
                if($appDataInfo[0]['version'] == $data['version']){
                    $appData = '1';
                    $message = $this->config->item('app_version_match');
                    $succes = array('success' => true);
                } else {
                    $appData = '0';
                    $message = $this->config->item('app_version_not_match');
                    $succes = array('success' => false);
                }
            }

            echo json_encode($succes);
            exit;

            
            // if (!empty($appDataInfo) || (empty($appDataInfo) && $appData )) {
            //     $succes = array('success' => true, 'message' => $message, 'data' => $appData);
                 
            //     echo json_encode($succes);
            //     exit;
            // } else {
            //     $error = array('success' => false, 'message' => $this->config->item('record_not_found'));
            //     echo json_encode($error);
            //     exit;
            // }
            
        }
    }

}
