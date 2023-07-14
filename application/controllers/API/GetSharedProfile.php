<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetSharedProfile extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        // print_r($data);die;
        $sharedProfile = $this->model_name->getSharedProfile($data['id']);
        $sharingProfile = $this->model_name->getSharingProfile($data['id']);
        if(!empty($sharedProfile) || !empty($sharingProfile))
        {
            $succes = array('success' => true, 'message' => 'data retrived', 'members' => $sharedProfile, 'membersharing' => $sharingProfile);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => 'No records found.');
            echo json_encode($error);
            exit;
        }
    }
}