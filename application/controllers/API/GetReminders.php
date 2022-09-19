<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetReminders extends REST_Controller
{
    function index() 
    {
        $data = $this->request_paramiters;
        $getReminders = $this->model_name->getRemindersById($data['id']);
        if($getReminders){
            $succes = array('success' => true, 'message' => 'Data Retrived Successfully','data'=>$getReminders);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => 'No Reminders Found');
            echo json_encode($error);
            exit;
        }
    }
}