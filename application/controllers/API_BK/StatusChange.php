<?php
/**
 *
 * User: satish4820
 * Date: 2/26/2018
 * Time: 10:11 PM
 */
error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class StatusChange extends REST_Controller
{
    function index()
    {
        if (($this->flag) == "1") {
            $data = $this->request_paramiters;
            $idsArr = !empty($data['idList']) ? explode(",", $data['idList']) : array();

            if(!empty($idsArr))
            {
                $updateArr = array();
                foreach($idsArr as $id)
                {
                    $d['id'] = $id;
                    $d['status'] = $data['status'];
                    $updateArr[] = $d;
                }

                if(!empty($updateArr))
                {
                    $status = $this->db->update_batch('users', $updateArr, 'id');
                }

                if($data['status'] == "1") {
                    $admin = $this->model_name->getUsersById($this->user_id);

                    $text_msg = "Admin ".$admin['first_name']." ".$admin['last_name']." approved reqeust.";
                    /* send notification to android */
                    $androidToken = $this->model_name->getUserAccessToken($data['idList'], "Android");
                    if (!empty($androidToken)) {
                        $android_devicetoken = array_column($androidToken, 'device_token');
                        $message = array("notification" => $text_msg);
                        $this->model_name->send_android_notification($message, $android_devicetoken);
                    }
                }

                $response = array("success" => true, "message" => $this->config->item('status_change_success'));
                echo json_encode($response);
                exit;
            }
            else{
                $response = array("success" => false, "message" => $this->config->item('invalid_request'));
                echo json_encode($response);
                exit;
            }
        }
    }
}