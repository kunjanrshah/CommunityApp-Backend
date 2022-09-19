<?php

/**

 *

 * User: satish4820

 * Date: 9/04/2018

 * Time: 10:55 PM

 */
error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class RequestAction extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $data = $this->request_paramiters;

            $relationship_id = $data['relationship_id'];

            $user_id = $this->user_id;
            if ($data['relationship_status'] == "DELETE") {
                $relationshipId = $this->model_name->deleteRelation($relationship_id);
            } else {
                $params = array(
                    'relationship_status' => $data['relationship_status'],
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $relationshipId = $this->model_name->updateRelation($relationship_id, $params);
            }

            if ($relationshipId) {
                if ($data['relationship_status'] != "DELETE") {
                    $user = $this->model_name->getUsersById($user_id);
                    $relation = $this->model_name->getRelation($relationship_id);

                    $text_msg = $user['first_name'] . " " . $user['last_name'] . " " . $data['relationship_status'] . " request for " . $relation['relation'] . ".";
                    /* send notification to android */
                    $androidToken = $this->model_name->getUserAccessToken($relation['user_id'], "Android");
                    if (!empty($androidToken)) {
                        $android_devicetoken = array_column($androidToken, 'device_token');
                        $message = array("notification" => $text_msg, 'relationship_id' => $relationship_id);
                        $this->model_name->send_android_notification($message, $android_devicetoken);
                    }
                }

                $relations = $this->model_name->getRelations($user_id);
                if ($data['relationship_status'] == "ACCEPTED") {
                    $response = array('success' => true, 'message' => $this->config->item('request_accepted'), 'data' => $relations);
                } elseif ($data['relationship_status'] == "DELETE") {
                    $response = array('success' => true, 'message' => $this->config->item('request_deleted'), 'data' => $relations);
                } else {
                    $response = array('success' => true, 'message' => $this->config->item('request_rejected'), 'data' => $relations);
                }
                echo json_encode($response);
                exit;
            }
        }
    }

}
