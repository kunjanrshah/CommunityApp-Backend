<?php
/**
 *
 * User: satish4820
 * Date: 2/28/2018
 * Time: 11:25 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetInactiveUsers extends REST_Controller
{

    function index()
    {

        if (($this->flag) == "1") {

            $users = $this->model_name->getInactiveUsers();

            if(!empty($users))
            {
                $userData = array();
                foreach($users as $user)
                {
                    $userData[] = $this->model_name->userResponse($user);
                }
                $succes = array('success' => true, 'message' => $this->config->item('user_data'), 'data' => $userData);
                echo json_encode($succes);
                exit;
            }else{
                $error = array('success' => false, 'message' => $this->config->item('user_data_not_found'));
                echo json_encode($error);
                exit;
            }

        }
    }
}