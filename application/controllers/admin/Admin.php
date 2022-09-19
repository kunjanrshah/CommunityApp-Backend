<?php

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();


        $this->load->model('users_model', 'ci_model');
        $this->load->helper(array('form', 'url'));
    }

    function index() {
        ($this->session->userdata('backEndLogin') != "") ? redirect('admin/dashboard') : redirect(base_url());
    }

    function validate_credentials() {
        $UserName = $this->input->post('mobile');
        $Password = $this->input->post('password');
        //echo $UserName . $Password;
        if (!empty($UserName) && !empty($Password)) {
            $is_valid = $this->ci_model->validate($UserName, $Password);

            if (!empty($is_valid)) {
                $data = array(
                    'id' => $is_valid[0]['id'],
                    'sub_community_id' => $is_valid[0]['sub_community_id'],
                    'local_community_id' => $is_valid[0]['local_community_id'],
                    'first_name' => $is_valid[0]['first_name'],
                    'last_name' => $is_valid[0]['last_name'],
                    'profile_pic' => $is_valid[0]['profile_pic'],
                    'email' => $UserName,
                    'role' => $is_valid[0]['role'],
                    'is_logged_in' => true,
                );
                $this->session->set_userdata('backEndLogin', $data);
                //$_SESSION['userdata'] = $data;
                redirect('admin/users');
            } else { // incorrect username or password
                $this->session->set_flashdata('flash_message_error', 'Incorrect Email or Password.');
                redirect('admin');
            }
        } else {
            $this->session->set_flashdata('flash_message_error', 'Please enter email and password.');
            redirect('admin');
        }
    }

    function logout() {
        $this->session->unset_userdata('frontLogin');
        $this->session->unset_userdata('backEndLogin');
        redirect(base_url());
    }

}
