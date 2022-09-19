<?php

defined('BASEPATH') or exit('No direct script access allowed');

abstract class Custom_Controller extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url'));
        if ($this->session->userdata('backEndLogin') == "") {
            redirect('admin');
        }

        $userData = $this->session->userdata('backEndLogin');

        $this->login_user_id = $userData['id'];

        $this->load->library('encrypt');
        $this->load->library('upload');
        $this->load->helper('file');
    }

    public function genRandomToken() {

        $acess_token = substr(str_shuffle(MD5(microtime())), 0, 5);

        return $acess_token;
    }

    public function dateFormate($date) {
        $dateArr = explode('/', $date);
        return $dateArr['2'] . '-' . $dateArr['1'] . '-' . $dateArr['0'];
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

    function getGeoLocations($address) {

        //$address = 'BTM 2nd Stage, Bengaluru, Karnataka 560076';

        $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=AIzaSyA8wogPKTV_zbvDAvINeWNydzYg_r2LOWI&sensor=false');
        $geo = json_decode($geo, true); // Convert the JSON to an array

        $locations = array();
        if (isset($geo['status']) && ($geo['status'] == 'OK')) {
            $locations['lat'] = $geo['results'][0]['geometry']['location']['lat']; // Latitude
            $locations['lng'] = $geo['results'][0]['geometry']['location']['lng']; // Longitude
        }
        return $locations;
    }

}
