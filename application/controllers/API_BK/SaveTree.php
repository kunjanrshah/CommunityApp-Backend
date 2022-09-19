<?php

/**
 *
 * User: satish4820
 * Date: 2/28/2018
 * Time: 9:15 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class SaveTree extends REST_Controller {

    function index() {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;

            $user_id = $this->user_id;
            $tree = $data['familyTree'];

            $path = realpath('./uploads/users/tree/');
            if (!empty($tree)) {
                foreach ($tree as $child) {
                    $t_id = (!empty($child["id"])) ? $child["id"] : "";
                    if (!empty($child['profile_pic'])) {
                        $child['profile_pic'] = $this->uploadBase64Image($child['profile_pic'], $path);
                    }

                    if (!empty($child["delete"])) {
                        $this->model_name->deleteTree($t_id);
                    } elseif ($t_id != "") {
                        $child['updated_dt'] = time();
                        $child['updated_by'] = $user_id;
                        $this->model_name->updateTree($t_id, $child);
                    } else {
                        $child['created_dt'] = time();
                        $child['created_by'] = $user_id;
                        $child['user_id'] = $user_id;
                        $child = array_filter($child);
                        $this->model_name->addTree($child);
                    }
                }
            }


            $response = array('success' => true, 'message' => $this->config->item('tree_update'), 'data' => $user);


            echo json_encode($response);
            exit;
        }
    }

}
