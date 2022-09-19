<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class StatusChange extends REST_Controller
{
    function index() 
    {
        $data = $this->request_paramiters;
        // print_r($data);die;
        // $json = ['one'=>'one11','youtube'=>['https://youtube.com','https://youtube.com']];
        // print_r(json_encode($json));die;

        $idList                                 = "";

        if( isset( $data['idList'] ) ){
            $idList                             = $data['idList'];
        }

        if( !empty( $idList ) ){
            $this->db->where('id IN ('.$data['idList'].')');
            $this->db->update('users', ['status'=>$data['status']]);    
        }
        

        //=================================================================================
        //========== Edited
        //=================================================================================

        $extra_info                             = 0;
        if( isset( $data['extra_info'] ) ){
            $extra_info                         = $data['extra_info'];    
        }
        
        if( $extra_info == '1' ){

            $profile_password                   = "";
            if( isset( $data['profile_password'] ) ){
                $profile_password               = $data['profile_password'];    
            }
            
            $relation_id                        = "";
            if( isset( $data['relation_id'] ) ){
                $relation_id                    = $data['relation_id'];
            }
            
            $head_id                            = "";
            if( isset( $data['head_id'] ) ){
                $head_id                        = $data['head_id'];
            }
            

            $update_data                                = [];

            if( !empty( $profile_password ) ){
                $update_data['profile_password']        = $profile_password;
                $update_data['plain_password']          = $profile_password;
                $update_data['password']                = sha1( $profile_password );
            }

            if( !empty( $relation_id ) ){
                $update_data['relation_id']             = $relation_id;
            }

            if( !empty( $head_id ) ){
                $update_data['head_id']                 = $head_id;
            }

            if( !empty( $update_data ) ){

                // UPdate datas
                $get_user_details                   = $this->db->query( "SELECT * FROM users WHERE id IN (" . $data['idList'] . ")" );
                $get_users                          = $get_user_details->result();

                if( $get_users && !empty( $get_users ) ){
                    foreach ( $get_users as $user ) {

                        if( $user->head_id == 0 ){

                            // Update other extra fileds for members
                            $this->db->where( 'head_id = "' . $user->id . '"' );
                            $this->db->update( 'users', $update_data );
                        }

                        // Update other extra fileds for main user
                        $this->db->where( 'id = "' . $user->id . '"' );
                        $this->db->update( 'users', $update_data );
                    }
                }

            }
            
        } else if( $extra_info == '2' ){
            
            $head_id                            = "";
            if( isset( $data['head_id'] ) ){
                $head_id                        = $data['head_id'];
            }

            $profile_password                   = "";
            if( isset( $data['profile_password'] ) ){
                $profile_password               = $data['profile_password'];    
            }

            if( !empty( $head_id ) ){

                $update_main_user                           = [];
                $update_main_user['head_id']                = '0';
                $update_main_user['relation_id']                = '1';
                
                if( !empty( $profile_password ) ){
                    $update_main_user['profile_password']   = $profile_password;
                    $update_main_user['plain_password']     = $profile_password;
                    $update_main_user['password']           = sha1( $profile_password );
                }

                $user_id                        = $data['id'];

                $this->db->where( 'id = "' . $head_id . '"' );
                $this->db->update( 'users', $update_main_user );

                $this->db->where( 'head_id = "' . $user_id . '"' );
                $this->db->update( 'users', [ 'head_id' => $head_id ] );

                /*$this->db->where( 'id = "' . $user_id . '"' );
                $this->db->update( 'users', [ 'head_id' => $head_id ] );*/
                $this->db->query( "UPDATE users SET `head_id`='" . $head_id . "' WHERE id=" . $user_id );
            }
        }

        //=================================================================================
        //========== End of Edited
        //=================================================================================

        if ( isset( $data['status'] ) && isset( $data['idList'] ) ) {
            $GetLocalAdmin = $this->db->query("SELECT device_token FROM tbl_devices WHERE user_id IN (".$data['idList'].")");
            $LocalAdminRes = $GetLocalAdmin->result();
            // print_r($LocalAdminRes);die;
            $allToken = [];
            foreach($LocalAdminRes as $row){
                if (strlen($row->device_token)>10) {
                    $allToken[] = $row->device_token;
                }
            }
            if (isset($data['id'])) {
                $userData = $this->db->query("SELECT * FROM users WHERE id =".$data['id']);
                $userData = $userData->row();
                // print_r($userData);die;
                if (!empty($allToken)) {
                    $text_msg = 'Your request for account approval is approved.';
                    $notify = [
                        'user_id'=>$userData->id,
                        'message'=>$text_msg,
                    ];
                    $this->model_name->send_android_notification_registration($notify, $allToken);
                }
            }
        }
        // $users = $this->model_name->statusChange($data);
        // print_r($users);die;
        
        if($data){
            $succes = array('success' => true, 'message' => $this->config->item('status_changed'));
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('data_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}