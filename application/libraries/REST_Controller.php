<?php
//muslimghanchisamaj.in
defined('BASEPATH') or exit('No direct script access allowed');

abstract class REST_Controller extends CI_Controller {

    public function __construct() {

		error_reporting(0);
        $this->flag = FALSE;

        parent::__construct();

        //$this->load->library('encryption');
        //$this->load->library('Cwupload');
        $this->load->library('upload');

        //$this->load->library('AES');

        $this->load->model('API/API_model', 'model_name');
        $this->load->model('Users_model');

        //$api_key = $this->model_name->getSettingData("API_KEY");

        $api_key = "q1fgdfggfw2e2rt3y5u6i8iug12fh123yhhddaf";

        $post_body = file_get_contents('php://input');

        $headers  = getallheaders();
        

        $request_data = $this->jsond($post_body);
        
        $data = $this->input->post();
        if (count($data)) {
            $request_data = $data;
        } 
        // print_r(count($request_data));
        // print_r(count($data));die;
        

        /*         * ***** Value store device table Start ****** */
        $deviceDetail['device_type'] = (isset($headers['Devicetype']))?$headers['Devicetype']:"";
        $deviceDetail['device_token'] = (isset($headers['Devicetoken']))?$headers['Devicetoken']:"";
        $deviceDetail['int_udid'] = (isset($headers['Intudid']))?$headers['Intudid']:"";
        $deviceDetail['created_on'] = date('Y-m-d h:i:s');
        $deviceDetail['access_token'] = (isset($request_data['access_token'])) ? $request_data['access_token']:"";
        $deviceDetail['updated_on'] = date('Y-m-d H:i:s');
        /*         * ***** Value store device table End ****** */

        $this->deviceDetails = $deviceDetail;

        $access_token = (isset($request_data['access_token']))?$request_data['access_token']:"";
        $user_id = (isset($request_data['user_id']))?$request_data['user_id']:"";

        if ($user_id != "") {
            if($access_token != "") {
                $this->db->update("tbl_devices", $deviceDetail, array('access_token' => $access_token));
                $checkaccess_token = $this->db->get_where('tbl_devices', array('user_id' => $user_id, 'access_token' => $access_token))->row_array();
               
                if (empty($checkaccess_token)) {
                    $error = array('success' => false, 'errorcode' => '-13', 'message' => 'Please login again.');
                    echo json_encode($error);
                    exit;
                }
            }else{
                $error = array('success' => false, 'errorcode' => '-13', 'message' => 'Access token required.');
                echo json_encode($error);
                exit;
            }
        }

        if (isset($headers['Apikey'])) {

            $param = $headers;

            $Apikey = $param['Apikey'];

            if (!empty($Apikey)) {

                if ($Apikey == $api_key) {

                    $this->flag = true;
                } else {

                    $error = array('success' => false, 'errorcode' => '-101', 'message' => 'API key not match');

                    print_r($this->json($error));

                    exit;
                }
            } else {

                $error = array('success' => false, 'errorcode' => '-100', 'message' => 'API key is Blank');

                print_r($this->json($error));

                exit;
            }
        } else {
            //$this->flag=true;
            $error = array('success' => false, 'errorcode' => '-10', 'message' => 'Apikey IS MISSING...Required Parameter Missing');

            print_r($this->json($error));

            exit;
        }


        unset($request_data['access_token']);
        if(isset($request_data['repeat_password'])) {
            unset($request_data['repeat_password']);
        }
        if(isset($request_data['user_id'])) {
            $this->user_id = $request_data['user_id'];
            unset($request_data['user_id']);
        }
        else{
            $this->user_id = "";
        }

        $this->request_paramiters = $request_data;
    }

     function json($data) {

        if (is_array($data)) {

            return json_encode($data);
        }
    }

     function jsond($data) {

        return json_decode(stripslashes($data), true);
    }

    public function genRandomToken() {

        global $DBH;

        $access_token = substr(str_shuffle(MD5(microtime())), 0, 5);

        $checkDevice = $this->db->get_where('tbl_devices', array('access_token' => $access_token));

        //$checkDevice = Device::model()->findByAttributes(array('varaccess_token' => $acess_token));

        if (!empty($checkDevice)) {

            $acessToken = substr(str_shuffle(MD5(microtime())), 0, 5);
        } else {
            $acessToken = access_token;
        }

        return $acessToken;
    }

    public function uploadFile($config) {

        $field = $config['field'];
        $upload_conf = array(
            'upload_path' => $config['upload_path'],
            'allowed_types' => (isset($config['allowed_types'])) ? $config['allowed_types'] : 'gif|jpg|jpeg|png|pdf',
            'max_size' => (isset($config['max_size'])) ? $config['max_size'] : '',
            'encrypt_name' => true,
        );

        $this->upload->initialize($upload_conf);

        if (!$this->upload->do_upload($field)) {
            $error['status'] = "error";
            $error['msg'] = $this->upload->display_errors();
            return $error;
        } else {
            $upload_data = $this->upload->data();

            if (isset($config['thumb_path']) && $config['thumb_path'] != "") {
                $resize_conf = array(
                    'upload_path' => $config['thumb_path'],
                    'source_image' => $upload_data['full_path'],
                    'new_image' => $upload_data['file_path'] . '../thumb/' . $upload_data['file_name'],
                    'width' => (isset($config['width'])) ? $config['width'] : $this->config->item('thumb_widht'),
                    'height' => (isset($config['height'])) ? $config['height'] : $this->config->item('thumb_height')
                );
                $this->load->library('image_lib');
                // initializing
                $this->image_lib->initialize($resize_conf);
                // do it!
                if (!$this->image_lib->resize()) {
                    // if got fail.
                    $error['status'] = "error";
                    $error['msg'] = $this->image_lib->display_errors();
                    return $error;
                } else {
                    $upload_img = $upload_data['file_name'];
                }
                $success['status'] = "success";
                $success['name'] = $upload_img;
                return $success;
            }
            $success['status'] = "success";
            $success['name'] = $upload_data['file_name'];
            return $success;
        }
    }

    public function uploadBase64Image($image,$path){
//        $image_parts = explode(";base64,", $image);
//        $image_type_aux = explode("image/", $image_parts[0]);
//        $image_type = ($image_type_aux[1] != "")?$image_type_aux[1]:"png";
//        $image_base64 = base64_decode($image_parts[1]);
//        $file_name = uniqid() . '.'.$image_type;
//        $file = $path .'/'. $file_name;
//        file_put_contents($file, $image_base64);
//
//        return $file_name;

        if (!empty($image) && !empty($path)) {
            $file_name = uniqid() . '.png';
            $binary = base64_decode($image);
            $file = fopen($path .'/'. $file_name, 'w');
            fwrite($file, $binary);
            fclose($file);
        }
        return $file_name;

    }

    public function uploadImage($image, $path) {
        if (!empty($base) && !empty($filename)) {
            $filename = time() . common::randomPassword() . $filename;
            $binary = base64_decode($base);
            $file = fopen(Yii::app()->params['paths']['usersPath'] . $filename, 'w');
            fwrite($file, $binary);
            fclose($file);
        }
        return $filename;
    }

    function getGeoLocations($address) {

        $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=AIzaSyA8wogPKTV_zbvDAvINeWNydzYg_r2LOWI&sensor=false');
        $geo = json_decode($geo, true); // Convert the JSON to an array

        $locations = array();
        if (isset($geo['status']) && ($geo['status'] == 'OK')) {
            $locations['lat'] = $geo['results'][0]['geometry']['location']['lat']; // Latitude
            $locations['lng'] = $geo['results'][0]['geometry']['location']['lng']; // Longitude
        }
        return $locations;
    }

}
