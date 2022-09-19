<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Common

{

    public function __construct(){		
		/*if(!$this->session->userdata('is_logged_in')){

            redirect('admin/login');

		}*/		

    }

	function checkduplicate(){

	
		$this->load->model('admin/Users_model');

		$returvalue = $this->Common_model->fetch_record($this->input->post('primaryid'),$this->input->post('fieldname'),$this->input->post('fieldvalue'),$this->input->post('table'),$this->input->post('returtype'));

		$return_arry = array();

		if($returvalue) :

		 $return_arry['status']='1';

		 else:

		 $return_arry['status']='0';

		 endif;		 

		 echo json_encode($return_arry);

	}	

	function mwb_redirect($link){		

		$arr = array(base_url('admin').$link);

		$str1 = '';

		foreach($arr as $str)

		   $str1 .= preg_replace('/(\w{3,})(?=.*?\\1)\W*/', '', $str);		

		redirect($str1);

	}

}