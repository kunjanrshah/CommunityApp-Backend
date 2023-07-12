<?php
// https://https://yadav.samajapp.in/API/UpdateAddress

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class UpdateAddress extends REST_Controller
{
    function index() 
    {
        $data = $this->request_paramiters;

        $this->db->select("*");
        $this->db->from('users');
        $this->db->where('head_id','0');
        $users = $this->db->get()->result_array();

        if( $users && !empty( $users ) ){
        	foreach ( $users as $user_arr ) {

        		$this->db->where( 'head_id', $user_arr['id'] );
        		$this->db->update( 'users', [
        			'state_id' => $user_arr['state_id'],
        			'city_id' => $user_arr['city_id'],
        			'area' => $user_arr['area'],
        			'pincode' => $user_arr['pincode'],
        			'address' => $user_arr['address'],
        			'home_lat' => $user_arr['home_lat'],
        			'home_lng' => $user_arr['home_lng'],
        			'is_rented' => $user_arr['is_rented'],
        			'gotra_id' => $user_arr['gotra_id'],
        			'native_place_id' => $user_arr['native_place_id'],
        			'distinct_id' => $user_arr['distinct_id'],
        			'local_address' => $user_arr['local_address']
        		] );

        	}
        }

        die();
    }
}