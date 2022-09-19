<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
// error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class CreateEvent extends REST_Controller
{
    function index()
    {
        $this->load->model('Events_model');
        $data = $this->request_paramiters;
        $inserted =0;
        if (isset($data['params']) && $this->isJson($data['params'])) {
            $evData = json_decode($data['params'],true);
            if (isset($evData) && !empty($evData)) {
                $params['title'] = $evData['title'];
                $params['description'] = $evData['description'];
                $params['location'] = $evData['location'];
                $params['lat'] = $evData['lat'];
                $params['lng'] = $evData['lng'];
                $params['event_date'] = date('Y-m-d', strtotime($evData['event_date']));    
                $params['created_dt'] = time();
                $params['created_by'] = $evData['id'];
                // print_r($params);die;
                $event_id = $this->Events_model->add_event($params);
                // phprint_r($event_id);die;
                
                // $event_id = 1;
                if ($event_id) {
                    // echo "string";die;
                    $youtube = $evData['youtube'];
                    $youtubeArr = array();
                    if (!empty($youtube)) {
                        for ($i = 0; $i < sizeof($youtube); $i++) {
                            if ($youtube[$i] != "") {
                                $y['event_id'] = $event_id;
                                $y['type'] = "YOUTUBE";
                                $y['url'] = $youtube[$i];
                                $y['created_dt'] = time();
                                $y['created_by'] = $this->login_user_id;
                                $youtubeArr[] = $y;
                            }
                        }
                    }

                    $galleryArr = array();
                    if (!empty($_FILES['gallery']['name']) && $_FILES['gallery']['name'][0] != "") {
                        $imageCount = sizeof($_FILES['gallery']['name']);
                        for ($j = 0; $j < $imageCount; $j++) {
                            $g = array();
                            $g['event_id'] = $event_id;
                            $g['type'] = "IMAGE";
                            if ($_FILES['gallery']['name'][$j] != "") {

                                $_FILES['oneimages']['name'] = $_FILES['gallery']['name'][$j];
                                $_FILES['oneimages']['type'] = $_FILES['gallery']['type'][$j];
                                $_FILES['oneimages']['tmp_name'] = $_FILES['gallery']['tmp_name'][$j];
                                $_FILES['oneimages']['error'] = $_FILES['gallery']['error'][$j];
                                $_FILES['oneimages']['size'] = $_FILES['gallery']['size'][$j];

                                $config = array(
                                    'upload_path' => realpath('./uploads/events/'),
                                    'field' => 'oneimages',
                                    'allowed_types' => 'gif|jpg|jpeg|png',
                                );

                                $response = $this->uploadFile($config);
                                if ($response['status'] == "success") {
                                    $g['url'] = $response['name'];
                                }
                            }
                            $g['created_dt'] = time();
                            $g['created_by'] = $this->login_user_id;
                            $galleryArr[] = $g;
                        }
                    }

                    $media = array_merge($youtubeArr, $galleryArr);
                    // print_r($media);die;

                    if (!empty($media)) {
                        $this->db->insert_batch('event_media', $media);
                    }
                    $inserted = 1;
                }
            }
        }
        // print_r($data);die;
        // $date = '';
        // if (isset($data['date'])) {
        //     $date = $data['date'];
        // }
        // $this->Users_model->get_search_datatables($data);
        // $users = $this->Events_model->add_event($data);
        

        // print_r($users);die;
        
        if(!empty($inserted)){
            $succes = array('success' => true, 'message' => $this->config->item('event_create'));
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('data_not_found'));
            echo json_encode($error);
            exit;
        }
    }

    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}