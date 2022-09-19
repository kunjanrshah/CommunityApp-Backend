<?php

/**

 *

 * User: satish4820

 * Date: 9/04/2018

 * Time: 10:55 PM

 */
error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class SendRequest extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            $user_id = $this->user_id;
            $data['user_id'] = $this->user_id;
            $data['relationship_status'] = "REQUESTED";
            $data['created_at'] = date('Y-m-d H:i:s');

//            $relation = $this->model_name->checkRelation($data['user_id'], $data['to_user_id']);
//            if (empty($relation)) {
                $relationshipId = $this->model_name->addRelation($data);
//            } else {
//                unset($data['relationship_status']);
//                $data['updated_at'] = date('Y-m-d H:i:s');
//                $relationshipId = $this->model_name->updateRelation($relation['id'], $data);
//            }

            if ($relationshipId) {


                $user = $this->model_name->getUsersById($user_id);

                $text_msg = $user['first_name'] . " " . $user['last_name'] . " has sent request for " . $data['relation'] . ". Please Accept it";
                /* send notification to android */
                $androidToken = $this->model_name->getUserAccessToken($data['to_user_id'], "Android");
                if (!empty($androidToken)) {
                    $android_devicetoken = array_column($androidToken, 'device_token');
                    $message = array("notification" => $text_msg, 'relationship_id' => $relationshipId);
                    $this->model_name->send_android_notification($message, $android_devicetoken);
                }

                $response = array('success' => true, 'message' => $this->config->item('request_sent'), 'data' => array());
                echo json_encode($response);
                exit;
            }
        }
    }

}
