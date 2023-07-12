<?php


/**
 *
 * vanza.samajapp.in
 * Date: 3/1/2018
 * Time: 11:33 PM
 */

class API_model extends CI_Model {

    public function __construct() {

        parent::__construct();

        $this->load->database();

        $this->load->helper('url');

        $this->load->library('email');

        //$this->load->library('AES');
    }

    /* For Email */

    function send_email($subject, $to, $from, $body, $file) {

        $this->email->clear();

        $config['protocol'] = 'sendmail';

        $config['mailpath'] = '/usr/sbin/sendmail';

        $config['charset'] = 'iso-8859-1';

        $config['wordwrap'] = TRUE;

        $config['mailtype'] = 'html';

        $this->email->initialize($config);

        //$this->email->set_newline("\r\n");

        $this->email->from($from); // change it to yours

        $this->email->to($to); // change it to yours

        $this->email->subject($subject);

        $this->email->message($body);

        $this->email->attach($file);

        //$this->email->set_mailtype("html");

        if ($this->email->send()) {

            return '1';
        } else {

            return '0';
        }
    }

    function send_emailsimple($subject, $to, $from, $body) {

        $this->email->clear();

        $config['protocol'] = 'sendmail';

        $config['mailpath'] = '/usr/sbin/sendmail';

        $config['charset'] = 'iso-8859-1';

        $config['wordwrap'] = TRUE;

        $config['mailtype'] = 'html';

        $this->email->initialize($config);

        //$this->email->set_newline("\r\n");

        $this->email->from($from, 'Directory'); // change it to yours

        $this->email->to($to); // change it to yours

        $this->email->subject($subject);

        $this->email->message($body);

        //$this->email->set_mailtype("html");

        if ($this->email->send()) {

            return '1';
        } else {

            return '0';
        }
    }

    function addError($data) {

        $this->db->insert("error_logs", $data);

        return $this->db->insert_id();
    }

    /* For Email */

    function addRelation($data) {
        $this->db->insert("relationship", $data);

        return $this->db->insert_id();
    }

    function updateRelation($id, $params) {
        $this->db->where('id =' . $id);

        return $this->db->update("relationship", $params);
    }

    function deleteRelation($id) {
        return $this->db->delete('relationship', array('id' => $id));
    }

    function getRelation($id) {
        $this->db->select('r.*');

        $this->db->from("relationship r");

        $this->db->where("r.id", $id);

        $posts = $this->db->get()->row_array();

        return $posts;
    }

    function checkRelation($userId, $toUserId) {
        $this->db->select('r.*');

        $this->db->from("relationship r");

        $this->db->where("r.user_id", $userId);

        $this->db->where("r.to_user_id", $toUserId);

        $posts = $this->db->get()->row_array();

        return $posts;
    }

    function getRelations($user_id) {
        $this->db->select('r.*,tu.first_name as to_first_name,tu.last_name as to_last_name,fu.first_name as from_first_name,fu.last_name as from_last_name');

        $this->db->from("relationship r");

        $this->db->join('users tu', 'tu.id = r.to_user_id', 'left');

        $this->db->join('users fu', 'fu.id = r.user_id', 'left');

        $this->db->where("r.user_id", $user_id);

        $this->db->or_where("r.to_user_id", $user_id);

        $posts = $this->db->get()->result_array();

        if (!empty($posts)) {
            foreach ($posts as $k => $v) {
                if ($posts[$k]['user_id'] == $user_id) {
                    $posts[$k]['first_name'] = $posts[$k]['to_first_name'];
                    $posts[$k]['last_name'] = $posts[$k]['to_last_name'];
                } else {
                    $posts[$k]['first_name'] = $posts[$k]['from_first_name'];
                    $posts[$k]['last_name'] = $posts[$k]['from_last_name'];
                }
            }
        }
        return $posts;
    }

    function getNearByUsers($user_id, $params, $start = "", $length = "") {
        $miles = $params['km'];
        //$miles = $params['km'];

        // if ($params['nearBy'] == "Home") {
        //     //$select = "( 6373 * acos( cos( radians(" . $params['lat'] . ") ) * cos( radians( u.home_lat ) ) * cos( radians( u.home_lng ) - radians(" . $params['lng'] . ") ) + sin( radians(" . $params['lat'] . ") ) * sin( radians( u.home_lat ) ) ) ) AS distance,u.*";
        //     $select = "((ACOS(SIN('" . $params['lat'] . "' * PI() / 180) * SIN(u.home_lat * PI() / 180) + COS('" . $params['lat'] . "' * PI() / 180) * COS(u.home_lat * PI() / 180) * COS(('" . $params['lng'] . "' - u.home_lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance,u.*";
        // } elseif ($params['nearBy'] == "Office") {
        //     $select = "((ACOS(SIN('" . $params['lat'] . "' * PI() / 180) * SIN(u.office_lat * PI() / 180) + COS('" . $params['lat'] . "' * PI() / 180) * COS(u.office_lat * PI() / 180) * COS(('" . $params['lng'] . "' - u.office_lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance,u.*";
        // } else {
        //     $select = "((ACOS(SIN('" . $params['lat'] . "' * PI() / 180) * SIN(u.user_lat * PI() / 180) + COS('" . $params['lat'] . "' * PI() / 180) * COS(u.user_lat * PI() / 180) * COS(('" . $params['lng'] . "' - u.user_lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance,u.*";
        // }
        
        if ($params['nearBy'] == "Home") {
            $select = "
                ( 6371 * 
                    ACOS( 
                        COS( RADIANS( u.home_lat ) ) * 
                        COS( RADIANS( ".$params['lat']." ) ) * 
                        COS( RADIANS( ".$params['lng']." ) - 
                        RADIANS( u.home_lng ) ) + 
                        SIN( RADIANS( u.home_lat ) ) * 
                        SIN( RADIANS( ".$params['lat'].") ) 
                    ) 
                ) 
                AS distance,u.*";
        } 
        else if ($params['nearBy'] == "Office") {
             $select = "    
                ( 6371 * 
                    ACOS( 
                        COS( RADIANS( u.office_lat ) ) * 
                        COS( RADIANS( ".$params['lat']." ) ) * 
                        COS( RADIANS( ".$params['lng']." ) - 
                        RADIANS( u.office_lng ) ) + 
                        SIN( RADIANS( u.office_lat ) ) * 
                        SIN( RADIANS( ".$params['lat'].") ) 
                    ) 
                ) 
                AS distance,u.*";
        }else if ($params['nearBy'] == "User") {
            $select = "
                ( 6371 * 
                    ACOS( 
                        COS( RADIANS( u.user_lat ) ) * 
                        COS( RADIANS( ".$params['lat']." ) ) * 
                        COS( RADIANS( ".$params['lng']." ) - 
                        RADIANS( u.user_lng ) ) + 
                        SIN( RADIANS( u.user_lat ) ) * 
                        SIN( RADIANS( ".$params['lat'].") ) 
                    ) 
                ) 
                AS distance,u.*";
        }else{
            $select = " CONCAT_WS('',
                        case when ( 6371 * ACOS( 
                                        COS( RADIANS( u.home_lat ) ) * 
                                        COS( RADIANS( ".$params['lat']." ) ) * 
                                        COS( 
                                            RADIANS( ".$params['lng']." ) -  RADIANS( u.home_lng ) 
                                        ) + 
                                        SIN( RADIANS( u.home_lat ) ) * 
                                        SIN( RADIANS( ".$params['lat'].") ) 
                                        )  
                                    ) <=".$miles." AND u.home_lat !='' AND u.home_lat!=''AND u.home_lat IS NOT NULL AND u.home_lat IS NOT NULL then 
                                    CONCAT(( 6371 * ACOS( 
                                        COS( RADIANS( u.home_lat ) ) * 
                                        COS( RADIANS( ".$params['lat']." ) ) * 
                                        COS( 
                                            RADIANS( ".$params['lng']." ) -  RADIANS( u.home_lng ) 
                                        ) + 
                                        SIN( RADIANS( u.home_lat ) ) * 
                                        SIN( RADIANS( ".$params['lat'].") ) 
                                            ) 
                                    ),'') else '' end,
                        case when ( 6371 * ACOS(   
                                        COS( RADIANS( u.office_lat ) ) * 
                                        COS( RADIANS( ".$params['lat']." ) ) * 
                                        COS( 
                                            RADIANS( ".$params['lng']." ) -  RADIANS( u.office_lng ) 
                                        ) + 
                                        SIN( RADIANS( u.office_lat ) ) * 
                                        SIN( RADIANS( ".$params['lat'].") ) 
                                            )  
                                    ) <=".$miles." AND u.office_lat !='' AND u.office_lat!=''  AND u.office_lat IS NOT NULL AND u.office_lat IS NOT NULL then 
                                    CONCAT(',',( 6371 * ACOS(   
                                        COS( RADIANS( u.office_lat ) ) * 
                                        COS( RADIANS( ".$params['lat']." ) ) * 
                                        COS( 
                                            RADIANS( ".$params['lng']." ) - RADIANS( u.office_lng ) 
                                        ) + 
                                        SIN( RADIANS( u.office_lat ) ) * 
                                        SIN( RADIANS( ".$params['lat'].") ) 
                                            ) 
                                    )) else '' end) 
                        AS distance, ";
                        $select .= " CONCAT(
                        case when ( 6371 * ACOS( 
                                        COS( RADIANS( u.home_lat ) ) * 
                                        COS( RADIANS( ".$params['lat']." ) ) * 
                                        COS( 
                                            RADIANS( ".$params['lng']." ) -  RADIANS( u.home_lng ) 
                                        ) + 
                                        SIN( RADIANS( u.home_lat ) ) * 
                                        SIN( RADIANS( ".$params['lat'].") ) 
                                        ) 
                                    ) <=".$miles." then 'home' else '' end,
                        case when ( 6371 * ACOS(   
                                        COS( RADIANS( u.office_lat ) ) * 
                                        COS( RADIANS( ".$params['lat']." ) ) * 
                                        COS( 
                                            RADIANS( ".$params['lng']." ) -  RADIANS( u.office_lng ) 
                                        ) + 
                                        SIN( RADIANS( u.office_lat ) ) * 
                                        SIN( RADIANS( ".$params['lat'].") ) 
                                            ) 
                                    ) <=".$miles." then ', office' else '' end) 
                        AS nearBy,u.* ";
                        

                        // ,
                        // case when ( 6371 * ACOS(   
                        //                 COS( RADIANS( u.user_lat ) ) * 
                        //                 COS( RADIANS( ".$params['lat']." ) ) * 
                        //                 COS( 
                        //                     RADIANS( ".$params['lng']." ) -  RADIANS( u.user_lng ) 
                        //                 ) + 
                        //                 SIN( RADIANS( u.user_lat ) ) * 
                        //                 SIN( RADIANS( ".$params['lat'].") ) 
                        //                     ) 
                        //             ) <=".$miles." AND u.user_lat !='' AND u.user_lat!='' AND u.is_location_enable=1 AND u.user_lat IS NOT NULL AND u.user_lat IS NOT NULL then 
                        //             CONCAT(',',( 6371 * ACOS(   
                        //                 COS( RADIANS( u.user_lat ) ) * 
                        //                 COS( RADIANS( ".$params['lat']." ) ) * 
                        //                 COS( 
                        //                     RADIANS( ".$params['lng']." ) - RADIANS( u.user_lng ) 
                        //                 ) + 
                        //                 SIN( RADIANS( u.user_lat ) ) * 
                        //                 SIN( RADIANS( ".$params['lat'].") ) 
                        //                     ) 
                        //             )) else '' end

                        // ,
                        // case when ( 6371 * ACOS(   
                        //                 COS( RADIANS( u.user_lat ) ) * 
                        //                 COS( RADIANS( ".$params['lat']." ) ) * 
                        //                 COS( 
                        //                     RADIANS( ".$params['lng']." ) -  RADIANS( u.user_lng ) 
                        //                 ) + 
                        //                 SIN( RADIANS( u.user_lat ) ) * 
                        //                 SIN( RADIANS( ".$params['lat'].") ) 
                        //                     ) 
                        //             ) <=".$miles." then ', user' else '' end










             // $select .= " (case when ( 6371 * 
             //                        ACOS( 
             //                            COS( RADIANS( u.home_lat ) ) * 
             //                            COS( RADIANS( ".$params['lat']." ) ) * 
             //                            COS( RADIANS( ".$params['lng']." ) - 
             //                            RADIANS( u.home_lng ) ) + 
             //                            SIN( RADIANS( u.home_lat ) ) * 
             //                            SIN( RADIANS( ".$params['lat'].") ) 
             //                        ) 
             //                    )<=".$miles." then 'home'
             // when ( 6371 * 
             //                        ACOS(   
             //                            COS( RADIANS( u.office_lat ) ) * 
             //                            COS( RADIANS( ".$params['lat']." ) ) * 
             //                            COS( RADIANS( ".$params['lng']." ) - 
             //                            RADIANS( u.office_lng ) ) + 
             //                            SIN( RADIANS( u.office_lat ) ) * 
             //                            SIN( RADIANS( ".$params['lat'].") ) 
             //                        ) 
             //                    ) <=".$miles." then 'office'
             // else  'user'
             // end) AS nearBy,u.*";
        }

     

        $this->db->select($select);
        $this->db->from('users u');
        if ($cleanerIds != "") {
            $this->db->where("u.id NOT IN ('" . $user_id . "')");
        }
        $this->db->where('u.status', '1');
        
        if(isset($params['sub_community_id'])){
            $this->db->where('u.sub_community_id', $params['sub_community_id']);
        }
        
        
        if (isset($params['nearBy']) && in_array($params['nearBy'], ['Home','Office','User'])) {
            $this->db->where('u.'.strtolower($params['nearBy']).'_lat !="" AND u.'.strtolower($params['nearBy']).'_lat IS NOT NULL');
            $this->db->where('u.'.strtolower($params['nearBy']).'_lng !="" AND u.'.strtolower($params['nearBy']).'_lng IS NOT NULL');
            if ($params['nearBy']=='User') {
            	$this->db->where('u.is_location_enable',1);
            }
        	$this->db->having(' distance <= ' . $miles);
        }else{
        	$this->db->having(' distance !="" ');
        }
        // $this->db->having('FIND_IN_SET('.$miles.', distance) ' );
        // $this->db->order_by('distance','asc');
        // if (in_array($params['nearBy'], ['Home','Office','User'])) {
        // }else{
        //     $this->db->having('distance1 <= ' . $miles.' OR distance2 <= ' . $miles.' OR distance3 <= ' . $miles);
        // }
        
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        $this->db->order_by("distance", "asc");
        $posts = $this->db->get()->result_array();

        /*$users = array();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $users[] = $this->userResponse($post, $user_id);
            }
        }*/
        return $posts;
    }

    /* For Add USER */

    function addUser($data) {

        $this->db->insert("users", $data);

        return $this->db->insert_id();
    }

    function addChild($data) {

        $this->db->insert("childs", $data);

        return $this->db->insert_id();
    }

    /* For Add USER */



    /* For Update USER (If FBID already) */

    function updateUser($userID, $data) {

        $this->db->where('id =' . $userID);

        return $this->db->update("users", $data);
    }

    function updateChild($id, $data) {

        $this->db->where('id =' . $id);

        return $this->db->update("childs", $data);
    }

    function deleteChild($id) {

        return $this->db->delete('childs', array('id' => $id));
    }

    /* For Get USER */

    function getUsersById($userID) {

        $this->db->select('u.*');

        $this->db->from("users u");

        $this->db->where("u.id", $userID);

        $posts = $this->db->get()->row_array();

        return $posts;
    }

    function getBlockUsers($user_id) {

        $this->db->select('u.*');

        $this->db->from("block_users bu");

        $this->db->join('users u', 'u.id = bu.block_user_id', 'left');

        $this->db->where("bu.user_id", $user_id);

        $posts = $this->db->get()->result_array();

        $users = array();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $users[] = $this->userResponse($post, $user_id);
            }
        }
        return $users;
    }

    function getShareFromUsers($user_id) {

        $this->db->select('u.*');

        $this->db->from("block_users bu");

        $this->db->join('users u', 'u.id = bu.user_id', 'left');

        $this->db->where("bu.block_user_id", $user_id);

        $posts = $this->db->get()->result_array();

        $users = array();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $users[] = $this->userResponse($post);
            }
        }
        return $users;
    }

    function getSharedUsers($user_id) {

        $this->db->select('bu.block_user_id');

        $this->db->from("block_users bu");

        $this->db->where("bu.user_id", $user_id);

        $posts = $this->db->get()->result_array();

        return $posts;
    }

    /* For Get USER */



    /* Check User Exists */

    function checkUserExists($email = "") {

        $this->db->from("users");

        $this->db->where('email_address ="' . $email . '"');

        $posts = $this->db->get()->result_array();

        //echo $this->db->last_query();

        if (count($posts) > 0) {

            return $posts;
        }



        return false;
    }

    function checkUserMobileExists($mobile) {
        $this->db->from("users");

        $this->db->where('mobile ="' . $mobile . '"');

        $posts = $this->db->get()->result_array();

        //echo $this->db->last_query();

        if (count($posts) > 0) {

            return $posts;
        }



        return false;
    }

    /* Check User Exists Email and Password */

    function checkUserExistsEmailPassword($username, $password) {

        $this->db->select('u.*');

        $this->db->from("users u");

        $this->db->where('u.email_address ="' . $username . '" OR u.mobile ="' . $username . '"');

        $posts = $this->db->get()->row_array();


        if (count($posts) > 0) {

            return $posts;
        }



        return false;
    }

    /* Check User Exists Email and Password */

    function getSyncUsers($syncTime, $city = "") {

        if ($city != "")
            ; {

            $city = str_replace(",", "','", $city);
        }

        $this->db->from("users");

        $this->db->where('updated_time >="' . $syncTime . '"');

        $this->db->where('status', "1");

        $this->db->where('deleted', "0");

        if ($city != "") {

            $this->db->where("city IN ('" . $city . "')");
        }

        $posts = $this->db->get()->result_array();



        return $posts;
    }

    function getInactiveUsers($data='',$start="",$limit="",$sub_id="",$local_id="") {

    	$this->db->select("*,(select count(*) from users u1 where u1.head_id = users.id and u1.is_expired = '0') as member_count,");
        
        $this->db->from("users");

        $this->db->where('status!=1');

        $this->db->where('deleted', "0");
        
        if($sub_id != ""){
            $this->db->where('sub_community_id', $sub_id);
        }else if($local_id != ""){
            $this->db->where('local_community_id', $local_id);
        }
        
        if ($limit>0 && $start>=0) {
        	$this->db->limit($limit, $start);
        }

        $posts = $this->db->get()->result_array();

        return $posts;
    }


    function getInactiveUsersCount($sub_id="",$local_id=""){
        
        $this->db->from("users");
        $this->db->where('status!=1');

        $this->db->where('deleted', "0");
        if($sub_id != ""){
            $this->db->where('sub_community_id', $sub_id);
        }else if($local_id != ""){
            $this->db->where('local_community_id', $local_id);
        }
        return $this->db->get()->result_array();
    }


    function getAppVersionData(){
        
        $this->db->from("app_version");
        
        return $this->db->get()->result_array();
    }
    
    function setAppVersionData($version,$flag){
        
        $data['version'] = $version;
        
        if($flag == 1){
            $data['created_dt'] = date('Y-m-d H:i:s');
            
            $this->db->insert("app_version", $data);
            
            return $this->db->insert_id();
        } else {
            $data['updated_dt'] = date('Y-m-d H:i:s');
            $this->db->where('id = 1');

            return $this->db->update("app_version", $data);
        }
    }

    function getUsers($params = array()) {

        $this->db->select('u.id,u.first_name,u.last_name,u.gender,u.email_address,u.city,u.mobile');

        $this->db->from("users u");

        $this->db->where('u.status', "1");

        $this->db->where('u.deleted', "0");

        if (array_key_exists('city', $params)) {

            $this->db->where('u.city', $params['city']);
        }

        $posts = $this->db->get()->result_array();

        return $posts;
    }

    function getBusinessCategory($date='') {
        $this->db->select('id,name');
        $this->db->from("business_categories");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    function getBusSubCate($bus_id,$date='') {
        $this->db->select('id,name');
        $this->db->from("business_sub_categories");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('business_category_id ', $bus_id);
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        // print_r($this->db->last_query());die;
        return $posts;
    }


    function getListBusSubCate($date=''){
        $this->db->select('id,name,business_category_id as parent_id');
        $this->db->from("business_sub_categories");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status ', '1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }

    function getGotraList($date='') {
        $this->db->select('id,gotra as name');
        $this->db->from("gotra");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_at >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
   
    function getNative($date='') {
        $this->db->select('id,native as name');
        $this->db->from("native");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_at >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    function getOccupation($date='') {
        $this->db->select('id,occupation as name');
        $this->db->from("occupation");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_at >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    function getListRelation($date='') {
        $this->db->select('id,name');
        $this->db->from("relations");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }

    function getCommittee($date='') {
        $this->db->select('id,name');
        $this->db->from("committees");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $posts = $this->db->get()->result_array();
        $this->db->where('status','1');
        return $posts;
    }
    
    function getActivity($date='') {

        $this->db->select('id,activity as name');
        $this->db->from("current_activity");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    function getDistrict($date='') {

        $this->db->select('id,name');
        $this->db->from("distincts");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    function getEducation($date='') {

        $this->db->select('id,name');
        $this->db->from("educations");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    function getDesignation($date='') {
        
        $this->db->select('id,name');
        $this->db->from("designations");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $posts = $this->db->get()->result_array();
        return $posts;
    }

    function getStates($date='') {

        $this->db->select('id,state as name');
        $this->db->from("states");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_at >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        // echo $this->db->last_query();die;
        return $posts;
    }

    function getCities($state_id,$date='') {

        $this->db->select('id,city as name');
        $this->db->from("cities");
        $this->db->where('state_id ', $state_id);
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_at >=',$date);
        }
        $this->db->where('status', '1');
        $this->db->order_by("name", "asc");
                
        $posts = $this->db->get()->result_array();
        return $posts;
        //return array_column($posts, 'city');
    }
    
    function getStatisticCities($sub_community_id,$local_community_id) {

        $this->db->select('DISTINCT(city_id)');
        $this->db->from("users");
        
        if($sub_community_id != ''){
            $this->db->where('sub_community_id', $sub_community_id);    
        }
        
        if($local_community_id != ''){
            $this->db->where('local_community_id', $local_community_id);    
        }
        
       
        $posts = $this->db->get()->result_array();
        return $posts;
        //return array_column($posts, 'city');
    }
    
    function getListCity($date=''){
        $this->db->select('id,city as name,state_id as parent_id');
        $this->db->from("cities");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_at >=',$date);
        }
        $this->db->where('status ', '1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    function getListLocalCommunity($date=''){
        $this->db->select('id,name,sub_community_id as parent_id');
        $this->db->from("local_community");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status ', '1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    
    

    function getSurname($date='') {

        $this->db->select('id,name');
        $this->db->from("sub_casts");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status ', '1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }

    function getSubCommunity($date='') {

        $this->db->select('id,name');
        $this->db->from("sub_community");
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status','1');
        $posts = $this->db->get()->result_array();
        return $posts;
    }
    
    

    function getLocalCommunity($id,$date='') {

        $this->db->select('id,name');
        $this->db->from("local_community");
        $this->db->where("sub_community_id",$id);
        if (isset($date) && $date!='') {
            $date = date('Y-m-d H:i:s',$date);
            $this->db->where('updated_on >=',$date);
        }
        $this->db->where('status','1');

        $posts = $this->db->get()->result_array();
        return $posts;
    }

    function getDeleted($dbname){
        $this->db->select("id");
        // $this->db->select("GROUP_CONCAT(id) as id");
        $this->db->from($dbname);
        // $this->db->where("status",'0');
        $this->db->where("status",'1');
        $posts = $this->db->get()->result_array();
        $ids = [];
        foreach ($posts as $key => $value) {
        	$ids[] = $value['id'];
        }
        $ids = implode(',', $ids);
        // print_r($ids);die;
        return ['id'=>$ids];
    }

    function getLastUpdated($dbname){
        $fiveTable = ["gotra","states","native","occupation","cities"];
        if (in_array($dbname, $fiveTable)) {
            $this->db->select("updated_at as updated");
            $this->db->from($dbname);
            $this->db->order_by('updated_at', 'DESC');
        }else{
            // echo "string123";die;
            $this->db->select("updated_on as updated");
            $this->db->from($dbname);
            $this->db->order_by('updated_on', 'DESC');
        }
        $posts = $this->db->get()->row_array();
        // echo $this->db->last_query();die;
        return strtotime($posts['updated']);
        // return $posts['updated'];
    }

    /*     * **** Pass user details response ******* */

    function userResponse($user, $user_id = "") {

        $d = $user;

        if (array_key_exists('block_user_id', $d)) {

            $d['is_share'] = ($d['block_user_id'] != "") ? 1 : 0;

            unset($d['block_user_id']);
        }

        if (array_key_exists('share_user_id', $d)) {

            $d['can_share'] = ($d['share_user_id'] != "") ? 1 : 0;

            unset($d['share_user_id']);
        }

        $d['birth_date'] = ($d['birth_date'] != "0000-00-00") ? $d['birth_date'] : "";

        $d['marriage_date'] = ($d['marriage_date'] != "0000-00-00") ? $d['marriage_date'] : "";

        $d['shared_id'] = $this->getSharedUsers($d['id']);

        $d['first_name'] = iconv("Windows-1252", "UTF-8", $user['first_name']);

        $d['last_name'] = iconv("Windows-1252", "UTF-8", $user['last_name']);

        $d['city'] = ($user['city']) ? $user['city'] : "";

        $d['plain_password'] = ($user['plain_password']) ? $user['plain_password'] : "";

        $d['gender'] = ($user['gender']) ? $user['gender'] : "";

        $d['sub_cast'] = ($user['sub_cast']) ? $user['sub_cast'] : "";

        $d['ekdo'] = ($user['ekdo']) ? $user['ekdo'] : "";

        $d['marital_status'] = ($user['marital_status']) ? $user['marital_status'] : "";

        $d['work'] = ($user['work']) ? $user['work'] : "";

        $d['office_lat'] = ($user['office_lat']) ? $user['office_lat'] : "";

        $d['office_lng'] = ($user['office_lng']) ? $user['office_lng'] : "";

        $d['home_lat'] = ($user['home_lat']) ? $user['home_lat'] : "";

        $d['home_lng'] = ($user['home_lng']) ? $user['home_lng'] : "";

        $d['user_lat'] = ($user['user_lat']) ? $user['user_lat'] : "";

        $d['user_lng'] = ($user['user_lng']) ? $user['user_lng'] : "";
        $d['member_count'] = (isset($user['member_count'])) ? $user['member_count'] : "0";

/*
        if (!empty($user['profile_pic'])):

            $d['profile_pic_url'] = base_url() . 'uploads/users/' . $user['profile_pic'];

        else:

            $d['profile_pic_url'] = base_url() . 'uploads/' . "no-image.png";

        endif;

        $d['profile_pic'] = "";

        if (!empty($user['img_spouse'])):

            $d['img_spouse_url'] = base_url() . 'uploads/users/' . $user['img_spouse'];

        else:

            $d['img_spouse_url'] = base_url() . 'uploads/' . "no-image.png";

        endif;

        $d['img_spouse'] = "";



        if (!empty($user['img_father'])):

            $d['img_father_url'] = base_url() . 'uploads/users/' . $user['img_father'];

        else:

            $d['img_father_url'] = base_url() . 'uploads/' . "no-image.png";

        endif;

        $d['img_father'] = "";



        if (!empty($user['img_mother'])):

            $d['img_mother_url'] = base_url() . 'uploads/users/' . $user['img_mother'];

        else:

            $d['img_mother_url'] = base_url() . 'uploads/' . "no-image.png";

        endif;

        $d['img_mother'] = "";



        if (!empty($user['img_sfather'])):

            $d['img_sfather_url'] = base_url() . 'uploads/users/' . $user['img_sfather'];

        else:

            $d['img_sfather_url'] = base_url() . 'uploads/' . "no-image.png";

        endif;

        $d['img_sfather'] = "";



        if (!empty($user['img_smother'])):

            $d['img_smother_url'] = base_url() . 'uploads/users/' . $user['img_smother'];

        else:

            $d['img_smother_url'] = base_url() . 'uploads/' . "no-image.png";

        endif;

        $d['img_smother'] = "";

        $birth_date_reminder_id = $this->checkReminderSet($user_id, $user['id'], 'birth_date');
        $d['bdate_reminder_id'] = ($birth_date_reminder_id != FALSE) ? $birth_date_reminder_id : "0";

        $spouse_birth_date_reminder_id = $this->checkReminderSet($user_id, $user['id'], 'spouse_birth_date');
        $d['spouse_bdate_reminder_id'] = ($spouse_birth_date_reminder_id != FALSE) ? $spouse_birth_date_reminder_id : "0";

        $marriage_date_reminder_id = $this->checkReminderSet($user_id, $user['id'], 'marriage_date');
        $d['mdate_reminder_id'] = ($marriage_date_reminder_id != FALSE) ? $marriage_date_reminder_id : "0";

        $d['childs'] = $this->getChilds($d['id'], $user_id);

        $d['familyTree'] = $this->getFamilyTree($d['id']);*/

        return $d;
    }

    function getChilds($user_id, $login_user_id = "") {

        $this->db->from("childs");

        $this->db->where(array('user_id' => $user_id));

        $posts = $this->db->get()->result_array();

        if (count($posts) > 0) {

            $childArr = array();

            foreach ($posts as $post) {

                $child = $post;

                $child['child_bday'] = ($child['child_bday'] != "0000-00-00") ? $child['child_bday'] : "";

                if (!empty($post['child_image'])):

                    $child['child_image_url'] = base_url() . 'uploads/users/' . $post['child_image'];

                else:

                    $child['child_image_url'] = base_url() . 'uploads/' . "no-image.png";

                endif;

                $child_bdate_reminder_id = $this->checkReminderSet($login_user_id, $child['id'], 'child_birth_date');
                $child['child_bdate_reminder_id'] = ($child_bdate_reminder_id != FALSE) ? $child_bdate_reminder_id : "0";
                $childArr[] = $child;
            }



            return $childArr;
        } else {

            return array();
        }
    }

    function getFamilyTree($user_id) {

        $this->db->from("family_trees");

        $this->db->where(array('user_id' => $user_id));

        $posts = $this->db->get()->result_array();

        if (count($posts) > 0) {

            $childArr = array();

            foreach ($posts as $post) {

                $child = $post;

                if (!empty($post['profile_pic'])):

                    $child['profile_pic'] = base_url() . 'uploads/users/' . $post['profile_pic'];

                else:

                    $child['profile_pic'] = base_url() . 'uploads/' . "no-image.png";

                endif;

                $childArr[] = $child;
            }



            return $childArr;
        } else {

            return array();
        }
    }

    /*     * **** End pass user details response ******* */

    function checkReminderSet($user_id, $profile_id, $type) {
        $this->db->select('r.id');

        $this->db->from("reminders r");

        $this->db->where("r.user_id", $user_id);
        if ($type != "child_birth_date") {
            $this->db->where("r.profile_id", $profile_id);
        } else {
            $this->db->where("r.child_id", $profile_id);
        }
        $this->db->where("r.reminder_type", $type);

        $posts = $this->db->get()->row_array();

        if (!empty($posts)) {
            return $posts['id'];
        }
        return FALSE;
    }

    function getRemindersById($user_id,$profile_id='',$reminder_type='') {
        $this->db->select('*');

        $this->db->from("reminders r");

        $this->db->where("r.user_id", $user_id);
        if ($profile_id) {
            $this->db->where("r.profile_id", $profile_id);
        }
        if ($reminder_type) {
            $this->db->where("r.reminder_type", $reminder_type);
        }
        $posts = $this->db->get()->result_array();

        return $posts;
    }

    /* -------------------------------- START Device DETAILS--------------------------------- */

    function addDeviceDetails($data) {
    
        // return $this->db->insert("tbl_devices", $data);
        $CheckExists = $this->db->query("SELECT id FROM tbl_devices WHERE device_token = '".$data['device_token']."'");
        if($CheckExists->num_rows() == 0) {
            // echo "string";die;
            return $this->db->insert("tbl_devices", $data);
        }else {
            // echo "string1";die;
            return $this->db->query("UPDATE tbl_devices SET user_id='".$data['user_id']."', access_token='".$data['access_token']."',updated_on='".date('Y-m-d H:i:s')."' WHERE device_token='".$data['device_token']."'");
            // return $this->db->query("UPDATE tbl_devices SET access_token='".$data['access_token']."',updated_on='".date('Y-m-d H:i:s')."'");
        }
    }

    function updateDeviceDetailsById($deviceID, $data) {

        return $this->db->update("tbl_devices", $data, array('id' => $deviceID));
    }

    function getDeviceDetailsByUdId($intUdId) {

        $this->db->from("tbl_devices");

        $this->db->where(array('int_udid' => $intUdId));

        $posts = $this->db->get()->result_array();



        if (count($posts) > 0) {

            return $posts;
        } else {

            return array();
        }
    }

    function checkDeviceDetails($deviceType, $deviceToken) {

        if (!empty($deviceType)) {

            if ($deviceType == 'Iphone') {

                if (empty($deviceToken)) {

                    $errorDeviceToken = 0;
                } else {

                    $deviceToken = $deviceToken;

                    $errorDeviceToken = 1;
                }
            } else {

                if (empty($deviceToken)) {

                    $errorDeviceToken = 0;
                } else {

                    $deviceToken = $deviceToken;

                    $errorDeviceToken = 1;
                }
            }
        } else {

            $errorDeviceToken = 0;
        }

        return $errorDeviceToken;
    }

    function getUserAccessToken($userId, $device_type = '') {

        $userId = str_replace(',', '","', $userId);

        $this->db->from("tbl_devices");

        $this->db->where('tbl_devices.user_id IN ("' . $userId . '")');

        if (!empty($device_type)) {

            $this->db->where('tbl_devices.device_type', $device_type);
        }

        $this->db->group_by('tbl_devices.device_token');

        $posts = $this->db->get()->result_array();



        if (!empty($posts)) {

            return $posts;
        } else {

            return array();
        }
    }

    function getAdminAccessToken($device_type) {

        $this->db->select("td.*");
        $this->db->from("tbl_devices td");

        $this->db->join('users u', 'u.id = td.user_id', 'left');

        if (!empty($device_type)) {

            $this->db->where('td.device_type', $device_type);
        }

        $this->db->where('u.role', 'ADMIN');

        $this->db->group_by('td.device_token');

        $posts = $this->db->get()->result_array();

        if (!empty($posts)) {

            return $posts;
        } else {

            return array();
        }
    }

    /* -------------------------------- FINISH Device DETAILS--------------------------------- */

    function getEvents($user_id, $event_date = "", $page = '') {
        
        $limit = 25;

        $start = ($page - 1) * $limit;

        $this->db->select('e.*,u.profile_pic');

        $this->db->from('user_events ue');

        $this->db->join('events e', 'e.id = ue.event_id', 'left');
        $this->db->join('users u', 'u.id = ue.created_by', 'left');

        $this->db->where('ue.user_id', $user_id);

        if ($event_date != "") {
            $this->db->where('e.event_date >= "' . $event_date . '"');
        }

        $this->db->where('e.status', '1');

        $this->db->order_by('e.event_date', 'ASC');

        $this->db->group_by('ue.event_id');

        if ($page != "") {
            $this->db->limit($limit, $start);
        }
        $posts = $this->db->get()->result_array();

        $events = array();

        if (!empty($posts)) {

            foreach ($posts as $post) {

                $d['id'] = $post['id'];

                $d['title'] = $post['title'];

                $d['description'] = $post['description'];

                $d['location'] = $post['location'];
                
                $d['profile_pic'] = $post['profile_pic'];

                $d['event_date'] = $post['event_date'];

                $d['lat'] = $post['lat'];

                $d['lng'] = $post['lng'];

                $d['created_dt'] = $post['created_dt'];

                $media = $this->getEventMedia($post['id']);

                $youtube = array();

                $images = array();

                if (!empty($media)) {

                    foreach ($media as $m) {

                        if ($m['type'] == "IMAGE") {

                            $images[] = base_url() . 'uploads/events/' . $m['url'];
                        } else {

                            $youtube[] = $m['url'];
                        }
                    }
                }

                $d['youtube_url'] = $youtube;

                $d['images'] = $images;

                $events[] = $d;
            }
        }

        return $events;
    }

    function getUsersBySearch($params, $user_id = "") {

        $page = '';
        if ($params['page'] != "") {
            $page = isset($params['page']) ? $params['page'] : 1;
        }

        $limit = 25;

        $start = ($page - 1) * $limit;

        $fields = array('u.first_name',
            'u.last_name',
            'u.father_name',
            'u.mother_name',
            'u.city',
            'u.email_address',
            'u.mobile',
            'u.phone',
            'u.gender',
            'u.blood_group',
            'u.marital_status',
            'u.birth_place',
            'u.native_place',
            'u.education',
            'u.occupation',
            'u.address',
            'u.spouse_name',
            'u.spouse_father_name',
            'u.spouse_mother_name',
            'u.office_mobile',
            'u.office_address',
            'u.gotra',
            'u.role',
            'u.work',
            'c.child_bday',
            'c.child_edu',
            'c.child_name',
            'c.child_work',
            'c.is_interested',
            'c.isMarried'
        );

        $this->db->select('u.*,bu.id as block_user_id,su.id as share_user_id');

        $this->db->from("users u");

        $this->db->join('block_users bu', 'bu.user_id = u.id AND bu.block_user_id = "' . $user_id . '"', 'left');

        $this->db->join('block_users su', 'su.user_id = "' . $user_id . '" AND su.block_user_id = u.id', 'left');

        $this->db->join('childs c', 'c.user_id = u.id', 'left');

        $this->db->where('u.status', "1");

        $this->db->where('u.deleted', "0");
        $orcond = 0;
        $where = "";

        for ($i = 0; $i < sizeof($fields); $i++) {
            $fieldArr = explode('.', $fields[$i]);
            if ($where == '') {

                if (array_key_exists($fieldArr[1], $params)) {
                    $orcond = 1;

                    if ($params[$fieldArr[1]] != "") {
                        $where .= $fields[$i] . " LIKE '%" . $params[$fieldArr[1]] . "%'";
                    }
                }
            } else {

                if (array_key_exists($fieldArr[1], $params)) {
                    $orcond = 1;
                    if ($params[$fieldArr[1]] != "") {
                        $where .= " AND " . $fields[$i] . " LIKE '%" . $params[$fieldArr[1]] . "%'";
                    }
                }
            }
        }
        if (array_key_exists('child_mobile', $params)) {

            if ($orcond) {

                $where .= " AND c.mobile LIKE '%" . $params['child_mobile'] . "%'";
            } else {

                $orcond = 1;

                $where .= "c.mobile LIKE '%" . $params['child_mobile'] . "%'";
            }
        }
        if (array_key_exists('child_blood_group', $params)) {

            if ($orcond) {

                $where .= " AND c.blood_group LIKE '%" . $params['child_blood_group'] . "%'";
            } else {

                $orcond = 1;

                $where .= "c.blood_group LIKE '%" . $params['child_blood_group'] . "%'";
            }
        }

        if (array_key_exists('child_birth_place', $params)) {
            if ($orcond) {
                $where .= " AND c.birth_place LIKE '%" . $params['child_birth_place'] . "%'";
            } else {

                $orcond = 1;

                $where .= "c.birth_place LIKE '%" . $params['child_birth_place'] . "%'";
            }
        }

        if (array_key_exists('child_gender', $params)) {

            if ($orcond) {

                $where .= " AND LOWER(c.gender) = '" . strtolower($params['child_gender']) . "'";
            } else {

                $orcond = 1;

                $where .= "LOWER(c.gender) = '" . strtolower($params['child_gender']) . "'";
            }
        }


        if (array_key_exists('from_birth_date', $params) && array_key_exists('to_birth_date', $params)) {

            if ($orcond) {

                $where .= " AND (u.birth_date BETWEEN '" . $params['from_birth_date'] . "' AND '" . $params['to_birth_date'] . "')";
            } else {

                $orcond = 1;

                $where .= "(u.birth_date BETWEEN '" . $params['from_birth_date'] . "' AND '" . $params['to_birth_date'] . "')";
            }
        }

        if (array_key_exists('from_child_bday', $params) && array_key_exists('to_child_bday', $params)) {

            if ($orcond) {

                $where .= " AND (c.child_bday BETWEEN '" . $params['from_child_bday'] . "' AND '" . $params['to_child_bday'] . "')";
            } else {

                $orcond = 1;

                $where .= "(c.child_bday BETWEEN '" . $params['from_child_bday'] . "' AND '" . $params['to_child_bday'] . "')";
            }
        }

        if (array_key_exists('from_marriage_date', $params) && array_key_exists('to_marriage_date', $params)) {

            if ($orcond) {

                $where .= " AND (u.marriage_date BETWEEN '" . $params['from_marriage_date'] . "' AND '" . $params['to_marriage_date'] . "')";
            } else {

                $where .= "(u.marriage_date BETWEEN '" . $params['from_marriage_date'] . "' AND '" . $params['to_marriage_date'] . "')";
            }
        }

        if ($where != "") {
            $this->db->where($where);
        }

        $this->db->order_by('u.first_name', 'ASC');

        $this->db->group_by('u.id');

        if ($page != "") {
            $this->db->limit($limit, $start);
        }

        $posts = $this->db->get()->result_array();

        $users = array();

        foreach ($posts as $post) {
            $users[] = $this->userResponse($post, $user_id);
        }


        return $users;
    }

    function getUsersBySearchCount($params) {

        $fields = array('u.first_name',
            'u.last_name',
            'u.father_name',
            'u.mother_name',
            'u.city',
            'u.email_address',
            'u.mobile',
            'u.phone',
            'u.gender',
            'u.blood_group',
            'u.marital_status',
            'u.birth_place',
            'u.native_place',
            'u.education',
            'u.occupation',
            'u.address',
            'u.spouse_name',
            'u.spouse_father_name',
            'u.spouse_mother_name',
            'u.office_address',
            'u.office_mobile',
            'u.work',
            'u.gotra',
            'u.role',
            'c.child_bday',
            'c.child_edu',
            'c.child_name',
            'c.child_work',
            'c.is_interested',
            'c.isMarried'
        );

        $this->db->select('u.id');

        $this->db->from("users u");

        $this->db->join('childs c', 'c.user_id = u.id', 'left');

        $this->db->where('u.status', "1");

        $this->db->where('u.deleted', "0");
        $orcond = 0;

        $where = "";

        for ($i = 0; $i < sizeof($fields); $i++) {
            $fieldArr = explode('.', $fields[$i]);
            if ($where == '') {

                if (array_key_exists($fieldArr[1], $params)) {
                    $orcond = 1;

                    if ($params[$fieldArr[1]] != "") {
                        $where .= $fields[$i] . " LIKE '%" . $params[$fieldArr[1]] . "%'";
                    }
                }
            } else {

                if (array_key_exists($fieldArr[1], $params)) {
                    $orcond = 1;
                    if ($params[$fieldArr[1]] != "") {
                        $where .= " AND " . $fields[$i] . " LIKE '%" . $params[$fieldArr[1]] . "%'";
                    }
                }
            }
        }
        if (array_key_exists('child_mobile', $params)) {

            if ($orcond) {

                $where .= " AND c.mobile LIKE '%" . $params['child_mobile'] . "%'";
            } else {

                $orcond = 1;

                $where .= "c.mobile LIKE '%" . $params['child_mobile'] . "%'";
            }
        }
        if (array_key_exists('child_blood_group', $params)) {

            if ($orcond) {

                $where .= " AND c.blood_group LIKE '%" . $params['child_blood_group'] . "%'";
            } else {

                $orcond = 1;

                $where .= "c.blood_group LIKE '%" . $params['child_blood_group'] . "%'";
            }
        }

        if (array_key_exists('child_birth_place', $params)) {
            if ($orcond) {
                $where .= " AND c.birth_place = '" . $params['child_birth_place'] . "'";
            } else {

                $orcond = 1;

                $where .= "c.birth_place = '" . $params['child_birth_place'] . "'";
            }
        }

        if (array_key_exists('child_gender', $params)) {

            if ($orcond) {

                $where .= " AND LOWER(c.gender) = '" . strtolower($params['child_gender']) . "'";
            } else {

                $orcond = 1;

                $where .= "LOWER(c.gender) = '" . strtolower($params['child_gender']) . "'";
            }
        }


        if (array_key_exists('from_birth_date', $params) && array_key_exists('to_birth_date', $params)) {

            if ($orcond) {

                $where .= " AND (u.birth_date BETWEEN '" . $params['from_birth_date'] . "' AND '" . $params['to_birth_date'] . "')";
            } else {

                $orcond = 1;

                $where .= "(u.birth_date BETWEEN '" . $params['from_birth_date'] . "' AND '" . $params['to_birth_date'] . "')";
            }
        }

        if (array_key_exists('from_child_bday', $params) && array_key_exists('to_child_bday', $params)) {

            if ($orcond) {

                $where .= " AND (c.child_bday BETWEEN '" . $params['from_child_bday'] . "' AND '" . $params['to_child_bday'] . "')";
            } else {

                $orcond = 1;

                $where .= "(c.child_bday BETWEEN '" . $params['from_child_bday'] . "' AND '" . $params['to_child_bday'] . "')";
            }
        }

        if (array_key_exists('from_marriage_date', $params) && array_key_exists('to_marriage_date', $params)) {

            if ($orcond) {

                $where .= " AND (u.marriage_date BETWEEN '" . $params['from_marriage_date'] . "' AND '" . $params['to_marriage_date'] . "')";
            } else {

                $where .= "(u.marriage_date BETWEEN '" . $params['from_marriage_date'] . "' AND '" . $params['to_marriage_date'] . "')";
            }
        }

        if ($where != "") {

            $this->db->where($where);
        }

        $this->db->group_by('u.id');

        $posts = $this->db->get()->result_array();

        return count($posts);
    }

    function getUsersByDate($params, $user_id = "",$start="",$length="") {

        // $page = '';
        // if ($params['page'] != "") {
        //     $page = isset($params['page']) ? $params['page'] : 1;
        // }

        // $limit = 25;

        // $start = ($page - 1) * $limit;

        // if (isset($params['start']) && isset($params['length'])) {
        //     $start = $params['start'];
        //     $limit = $params['length'];
        // }

        // $fields = array(
        //     'u.birth_date',
        //     'u.marriage_date',
        //     'u.spouse_birth_place',
        //     'c.child_bday'
        // );
        $fields = array(
            'u.birth_date',
            'u.marriage_date',
            'u.expire_date',
        );
        $fields2 = array(
            'birth_date',
            'marriage_date',
            'expire_date',
        );

        // $select = 'CASE ';
        // for ($i = 0; $i < sizeof($fields); $i++) {
        //     $select .= "WHEN (DATE_FORMAT(".$fields[$i].", '%m-%d') >= DATE_FORMAT('" . $params['fromdate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') <= DATE_FORMAT('" . $params['todate'] . "', '%m-%d')) THEN '".str_replace('u.', '', $fields[$i])."'";
        // }
        // $select .= ' END';
        if (isset($fields2[$params['filter']])) {
           $select = ',"'.$fields2[$params['filter']].'" as matched';
        }
        
       $this->db->select('u.*,(select count(*) from users u1 where u1.head_id = u.id and u1.is_expired = "0") as member_count'.$select);

        $this->db->from("users u");

        // $this->db->join('block_users bu', 'bu.user_id = u.id AND bu.block_user_id = "' . $user_id . '"', 'left');

        // $this->db->join('block_users su', 'su.user_id = "' . $user_id . '" AND su.block_user_id = u.id', 'left');

        // $this->db->join('childs c', 'c.user_id = u.id', 'left');

        $this->db->where('u.status', "1");

        $this->db->where('u.deleted', "0");
        
        if(isset($params['sub_community_id'])){
            $this->db->where('u.sub_community_id', $params['sub_community_id']);    
        }    
        
        $orcond = 0;
        $where = "";

        // for ($i = 0; $i < sizeof($fields); $i++) {
        //     if ($where == '') {
        //         $where .= " (DATE_FORMAT(".$fields[$i].", '%m-%d') >= DATE_FORMAT('" . $params['fromdate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') <= DATE_FORMAT('" . $params['todate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') IS NOT NULL)";
        //     } else {
        //         $where .= "  OR (DATE_FORMAT(".$fields[$i].", '%m-%d') >= DATE_FORMAT('" . $params['fromdate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') <= DATE_FORMAT('" . $params['todate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') IS NOT NULL)";
        //     }
        // }

        if (isset($fields[$params['filter']])) {
            $where .= " (DATE_FORMAT(".$fields[$params['filter']].", '%m-%d') >= DATE_FORMAT('" . $params['fromdate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$params['filter']].", '%m-%d') <= DATE_FORMAT('" . $params['todate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$params['filter']].", '%m-%d') IS NOT NULL)";
            // $this->db->order_by("DATE_FORMAT(".$fields[$params['filter']].", '%m-%d')", 'ASC');
        }else{
            for ($i = 0; $i < sizeof($fields); $i++) {
                if ($where == '') {
                    $where .= " (DATE_FORMAT(".$fields[$i].", '%m-%d') >= DATE_FORMAT('" . $params['fromdate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') <= DATE_FORMAT('" . $params['todate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') IS NOT NULL)";
                } else {
                    $where .= "  OR (DATE_FORMAT(".$fields[$i].", '%m-%d') >= DATE_FORMAT('" . $params['fromdate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') <= DATE_FORMAT('" . $params['todate'] . "', '%m-%d') AND DATE_FORMAT(".$fields[$i].", '%m-%d') IS NOT NULL)";
                }
            }            
        }
        
       /* if(isset($params['sub_community_id'])){
            if($where != ""){
                $where .=" AND u.sub_community_id = ".$params['sub_community_id'];        
            }else{
                $where .="u.sub_community_id = ".$params['sub_community_id'];    
            }
        }*/
        
         //echo "kunjan". $where;
        if ($where != "") {
            $this->db->where("(".$where.")");
        }
        
        

        $this->db->order_by('u.first_name', 'ASC');

        $this->db->group_by('u.id');

        // if ($page != "") {
        //     $this->db->limit($limit, $start);
        // }else
        // if ($start && $length) {
        //     $this->db->limit($limit, $start);
        // }
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }

        $posts = $this->db->get()->result_array();
        $fromDM = date('m-d',strtotime($params['fromdate']));
        $toDM = date('m-d',strtotime($params['todate']));
        if (!isset($fields[$params['filter']])) {
            foreach ($posts as $key => $value) {
                $matched = '';
                foreach ($fields2 as $key2 => $value2) {
                    if ($fromDM<=date('m-d',strtotime($value[$value2])) && date('m-d',strtotime($value[$value2]))<=$toDM && $value[$value2]!=NULL) {
                        $matched .= ($matched)?','.$value2:$value2;
                    }
                    $isReminder = $this->getRemindersById($user_id,$posts[$key]['id'],$value2);
                    // print_r($isReminder);die;
                    $posts[$key]['reminder_'.$value2] = (!empty($isReminder))?$isReminder[0]['id']:'0';;
                }
                $posts[$key]['matched'] = $matched;
            }
        }
        $users = array();

        foreach ($posts as $post) {
            $users[] = $this->userResponse($post, $user_id);
        }


        return $users;
    }

    function getUsersByDateCount($params) {

        $fields = array(
            'u.birth_date',
            'u.marriage_date',
            'u.spouse_birth_place',
            'c.child_bday'
        );

        $this->db->select('u.id');

        $this->db->from("users u");

        $this->db->join('childs c', 'c.user_id = u.id', 'left');

        $this->db->where('u.status', "1");

        $this->db->where('u.deleted', "0");
        $orcond = 0;

        $where = "";

        for ($i = 0; $i < sizeof($fields); $i++) {
            if ($where == '') {
                $where .= " DATE_FORMAT(" . $fields[$i] . ", '%m-%d') = DATE_FORMAT('" . $params['date'] . "', '%m-%d')";
            } else {
                $where .= " OR DATE_FORMAT(" . $fields[$i] . ", '%m-%d') = DATE_FORMAT('" . $params['date'] . "', '%m-%d')";
            }
        }

        if(isset($params['sub_community_id']) && $where != ""){
            $where .=" AND sub_community_id = ".$params['sub_community_id'];    
        }

        if ($where != "") {

            $this->db->where($where);
        }

        $this->db->group_by('u.id');

        $posts = $this->db->get()->result_array();

        return count($posts);
    }

    function getUsersByGlobalSearch($params, $user_id = "") {

        $page = isset($params['page']) ? $params['page'] : '';

        $limit = 25;

        $start = ($page - 1) * $limit;

        $fields = array('first_name', 'last_name', 'father_name', 'mother_name', 'birth_date', 'city', 'email_address', 'mobile', 'phone', 'gender', 'blood_group', 'marital_status', 'birth_place', 'native_place', 'education', 'occupation', 'address', 'spouse_name', 'marriage_date', 'spouse_father_name', 'spouse_mother_name', 'gotra');

        $childFields = array('child_edu', 'child_name', 'child_work', 'birth_place', 'mobile', 'gender', 'blood_group');

        $this->db->select("u.*,(select count(*) from users u1 where u1.head_id = users.id and u1.is_expired = '0') as member_count,");
        
        $this->db->select('u.*,bu.id as block_user_id,su.id as share_user_id');

        $this->db->from("users u");

        $this->db->join('childs c', 'c.user_id = u.id', 'left');

        $this->db->join('block_users bu', 'bu.user_id = u.id AND bu.block_user_id = "' . $user_id . '"', 'left');

        $this->db->join('block_users su', 'su.user_id = "' . $user_id . '" AND su.block_user_id = u.id', 'left');

        $this->db->where('u.status', "1");

        $this->db->where('u.deleted', "0");

        $where = "";

        if ($params['search_str'] != "") {
            for ($i = 0; $i < sizeof($fields); $i++) {

                if ($i == 0) {

                    $where .= "u." . $fields[$i] . " LIKE '%" . $params['search_str'] . "%'";
                } else {

                    $where .= " OR u." . $fields[$i] . " LIKE '%" . $params['search_str'] . "%'";
                }
            }


            for ($i = 0; $i < sizeof($childFields); $i++) {

                $where .= " OR c." . $childFields[$i] . " LIKE '%" . $params['search_str'] . "%'";
            }
        }

        if ($where != "") {

            $this->db->where('(' . $where . ')');
        }

        $this->db->order_by('u.first_name', 'ASC');

        $this->db->group_by('u.id');

        if ($page != "") {
            $this->db->limit($limit, $start);
        }

        $posts = $this->db->get()->result_array();

        $users = array();

        foreach ($posts as $post) {

            $users[] = $this->userResponse($post, $user_id);
        }

        return $users;
    }

    function getUsersByGlobalSearchCount($params) {

        $fields = array('first_name', 'last_name', 'father_name', 'mother_name', 'birth_date', 'city', 'email_address', 'mobile', 'phone', 'gender', 'blood_group', 'marital_status', 'birth_place', 'native_place', 'education', 'occupation', 'address', 'spouse_name', 'marriage_date', 'spouse_father_name', 'spouse_mother_name', 'gotra');

        $childFields = array('child_edu', 'child_name', 'child_work', 'birth_place', 'mobile', 'gender', 'blood_group');

        $this->db->select('u.id');

        $this->db->from("users u");

        $this->db->join('childs c', 'c.user_id = u.id', 'left');

        $this->db->where('u.status', "1");

        $this->db->where('u.deleted', "0");

        $where = "";

        if ($params['search_str'] != "") {
            for ($i = 0; $i < sizeof($fields); $i++) {

                if ($i == 0) {

                    $where .= "u." . $fields[$i] . " LIKE '%" . $params['search_str'] . "%'";
                } else {

                    $where .= " OR u." . $fields[$i] . " LIKE '%" . $params['search_str'] . "%'";
                }
            }


            for ($i = 0; $i < sizeof($childFields); $i++) {
                $where .= " OR c." . $childFields[$i] . " LIKE '%" . $params['search_str'] . "%'";
            }
        }

        if ($where != "") {

            $this->db->where('(' . $where . ')');
        }

        $this->db->group_by('u.id');

        $posts = $this->db->get()->result_array();

        return count($posts);
    }

    function getGotra() {
        $select = "gotra";

        $this->db->distinct();

        $this->db->select($select);

        $this->db->from("users");

        $this->db->where('gotra !=', 'NULL');

        $this->db->where('gotra !=', '');

        $posts = $this->db->get()->result_array();

        return array_column($posts, 'gotra');
    }

    function addReminder($data) {
        $this->db->insert('reminders', $data);
        return $this->db->insert_id();
    }

    function deleteReminder($reminder_id) {
        return $this->db->delete('reminders', array('id' => $reminder_id));
    }

    function getReminders() {
        $today = date('Y-m-d');
        $this->db->select('r.*,u.first_name,u.last_name,pu.first_name as profile_first_name,pu.last_name as profile_last_name,c.child_name');
        $this->db->from("reminders r");
        $this->db->join('users u', 'r.user_id = u.id', 'left');
        $this->db->join('users pu', 'r.profile_id = pu.id', 'left');
        $this->db->join('childs c', 'r.child_id = c.id', 'left');

        $where = "DATE_FORMAT(r.reminder_date, '%m-%d') = DATE_FORMAT('" . $today . "', '%m-%d')";

        $this->db->where($where);

        return $this->db->get()->result_array();
    }

    function getEventMedia($event_id) {

        $this->db->select('em.*');

        $this->db->from('event_media em');

        $this->db->where('em.event_id', $event_id);

        return $this->db->get()->result_array();
    }

    function addTree($data) {

        $this->db->insert("family_trees", $data);

        return $this->db->insert_id();
    }

    function updateTree($id, $data) {

        $this->db->where('id =' . $id);

        return $this->db->update("family_trees", $data);
    }

    function deleteTree($id) {

        return $this->db->delete('family_trees', array('id' => $id));
    }

    function generateTimeSlot($startTime, $endTime) {

        $duration = $this->model_name->getSettingByKey(BOOKING_TIME_INTERVAL); //$this->config->item('time_interval');  // split by 30 mins

        $duration = $duration['key_value'];

        $array_of_time = array();

        $start_time = strtotime($startTime); //change to strtotime

        $end_time = strtotime($endTime); //change to strtotime



        $add_mins = $duration * 60;



        while ($start_time <= $end_time) { // loop between time
            $array_of_time[] = date("h:i A", $start_time);

            $start_time += $add_mins; // to check endtie=me
        }



        return $array_of_time;
    }

    function convertToHoursMins($time) {

        if ($time < 1) {

            return;
        }

        $hours = floor($time / 60);

        $minutes = ($time % 60);

        if ($hours != "0" && $minutes != "0") {

            return $hours . 'h ' . $minutes . 'min';
        } elseif ($hours == "0") {

            return $minutes . 'min';
        } else {

            return $hours . 'h';
        }
    }

    function roundTime($time) {

        $time = strtotime($time);



        $frac = 900;

        $r = $time % $frac;



        $new_time = $time + ($frac - $r);

        $new_date = date('H:i:s', $new_time);



        return $new_date;
    }

    function getMinBetweenTime($start_time, $end_time) {

        //$start_time = date('Y-m-d H:i:s', strtotime($start_time));
        //$end_time = date('Y-m-d H:i:s', strtotime($end_time));



        $datetime1 = new DateTime($start_time);

        $datetime2 = new DateTime($end_time);

        $interval = $datetime1->diff($datetime2);

        $elapsed = $interval->format('%H') . ':' . $interval->format('%I') . ':' . $interval->format('%S');

        return $this->minutes($elapsed);
    }

    function minutes($time) {

        $time = explode(':', $time);

        return ($time[0] * 60) + ($time[1]) + ($time[2] / 60);
    }

    /* ---------------- Send notifications in ios devices ----------- */

    function send_pushnotification($notification_text, $device_token, $dataVal, $user_type = "") {

        $payload = array();

        $payload['aps'] = array(
            'alert' => $notification_text, //Message to be displayed in Alert
            'data' => $dataVal,
            'badge' => '1', //Number to be displayed in the top right of your app icon this should not be a string
            'sound' => 'default'        //Default notification sound (you can customize this)
        );



        $payload = json_encode($payload);



        if (ENVIRONMENT_NOTIFICATION == "DEVELOPMENT") {

            $apnsHost = 'gateway.sandbox.push.apple.com';

            $apnsCert = ABSURL . '/application/models/API/apns-dev-clean.pem';

            if ($user_type == "CLEANER") {

                $apnsCert = ABSURL . '/application/models/API/apns-dev-cleaner.pem';
            }

            $passphrase = '';
        } else { // PRODUCTION
            $apnsHost = 'gateway.push.apple.com';

            $apnsCert = ABSURL . '/application/models/API/apns-prod-clean.pem';

            if ($user_type == "CLEANER") {

                $apnsCert = ABSURL . '/application/models/API/apns-prod-cleaner.pem';
            }

            $passphrase = '';
        }



        $apnsPort = 2195;

        $streamContext = @stream_context_create();

        @stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);

        stream_context_set_option($streamContext, 'ssl', 'passphrase', $passphrase);

        $apns = @stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);

        // 60 is the timeout

        $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', trim($device_token)) . chr(0) . chr(strlen($payload)) . $payload;



        $result = @fwrite($apns, $apnsMessage);



        // socket_close($apns);

        @fclose($apns);

        // Push Notification Code End Here



        if (!$result) {

            $msg = 'Message not delivered' . PHP_EOL;
        } else {

            $msg = 'MSG Delivered';
        }

        return $msg;
    }

    /* ---------------- End Send notifications in ios devices ----------- */



    /* ---------------- Send notifications in Android devices ----------- */

    function send_android_notification($notification_text, $device_token) {
        // echo gettype($device_token);die;
        //$url = 'https://android.googleapis.com/gcm/send';

        $url = "https://fcm.googleapis.com/fcm/send";

        $fcmFields = array(
            'to' => $device_token,
            'priority' => 'high',
            'data' => $notification_text
        );

        if (gettype($device_token)=='array') {
            $fcmFields['registration_ids'] = $device_token;
        }else{
            $fcmFields['to'] = $device_token;            
        }

        // print_r($fcmFields);die;
        //AIzaSyBQ3916hnfxVas-ZEJCc55EcdhrfYRIDLA

        //$API_KEY = GOOGLE_API_KEY;
        
        $api_key = 'AAAASdHkU-A:APA91bG-ojuRcHf-nHtVs_d1HoaxZAKJbnq1Gs_usKvxSvuwvbU44Z2pqQoIRKhcBdh-QM1je33FZFqerf2GaxmU8L_aqey3dUCsCSeZC7NthQLOeHJWF7IQ9Tkr4NJ7wYgkLjNKVVm7';

        $fields = array (
            // 'to' => $device_token,
            'data' => array (
                // "title" => APP_NAME,
                "message" => $notification_text,
                // "icon" => base_url().'uploads/logo.png'
            )
        );
        if (gettype($device_token)=='array') {
            $fields['registration_ids'] = $device_token;
        }else{
            $fields['to'] = $device_token;            
        }

        // Open connection

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        // print_r($result);die;
        curl_close($ch);
        return $result;
    }

    function send_android_notification_registration($notification_text, $device_token) {
        // echo gettype($device_token);die;
        //$url = 'https://android.googleapis.com/gcm/send';

        $url = "https://fcm.googleapis.com/fcm/send";

        $fcmFields = array(
            'to' => $device_token,
            'priority' => 'high',
            'data' => $notification_text
        );

        if (gettype($device_token)=='array') {
            $fcmFields['registration_ids'] = $device_token;
        }else{
            $fcmFields['to'] = $device_token;            
        }

        // print_r($fcmFields);die;
        //AIzaSyBQ3916hnfxVas-ZEJCc55EcdhrfYRIDLA

        //$API_KEY = GOOGLE_API_KEY;
        
        $api_key = GOOGLE_FCM_KEY;
        // $api_key = 'AAAASdHkU-A:APA91bG-ojuRcHf-nHtVs_d1HoaxZAKJbnq1Gs_usKvxSvuwvbU44Z2pqQoIRKhcBdh-QM1je33FZFqerf2GaxmU8L_aqey3dUCsCSeZC7NthQLOeHJWF7IQ9Tkr4NJ7wYgkLjNKVVm7';

        $fields = array (
            // 'to' => $device_token,
            // 'data' => array (
            //     "title" => APP_NAME,
            //     "message" => $notification_text,
            //     "icon" => base_url().'uploads/logo.png'
            // )
            "data" => $notification_text,
            "notification" => $notification_text
        );
        if (gettype($device_token)=='array') {
            $fields['registration_ids'] = $device_token;
        }else{
            $fields['to'] = $device_token;            
        }

        // Open connection

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        // print_r($result);die;
        curl_close($ch);
        return $result;
    }

    /* ----------------End Send notifications in Android devices ----------- */
    
    function loginWithEmail($email,$password) {
        
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('email_address', $email)->where('password',$password);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    function loginWithSocial($email) {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('email_address', $email);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    function loginWithMobile($mobile,$password) {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('mobile', $mobile)->where('password',$password);
        $query = $this->db->get();
        
        $result = $query->row();
        return $result;
    }
    
    function loginWithOnlyMobile($mobile) {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('mobile', $mobile);
        $query = $this->db->get();
        
        $result = $query->row();
        return $result;
    }
    
    function loginWithOTP($mobile) {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('mobile', $mobile);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    function findUserByEmail($email, $id = "") {
        
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.email_address', $email);
        if ($id != "") {
            $this->db->where('u.id != ', $id);
        }
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result;
    }
     function userMobileExists($mobile, $id = "") {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.mobile', $mobile);
        if ($id != "") {
            $this->db->where('u.id != ', $id);
        }
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result;
    }
    
    public function getUser($id) {
        $select = "u.*,
                r.name as relation,
                sca.name as sub_cast,
                bc.name as business_category,
                bsc.name as business_sub_category,
                c.name as committee,
                ca.activity as current_activity,
                d.name as designation,
                e.name as education,
                g.gotra,
                lc.name as local_community,
                di.name as distinct,
                n.native as native_place,
                m.name as mosaad,
                o.occupation,
                sc.name as sub_community";
        $this->db->select($select);
        $this->db->from('users u');
        $this->db->join('relations r', 'r.id = u.relation_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = u.sub_community_id', 'left');
        $this->db->join('sub_casts sca', 'sca.id = u.sub_cast_id', 'left');
        $this->db->join('local_community lc', 'lc.id = u.local_community_id', 'left');
        $this->db->join('committees c', 'c.id = u.committee_id', 'left');
        $this->db->join('designations d', 'd.id = u.designation_id', 'left');
        $this->db->join('distincts di', 'di.id = u.distinct_id', 'left');
        $this->db->join('native n', 'n.id = u.native_place_id', 'left');
        $this->db->join('current_activity ca', 'ca.id = u.current_activity_id', 'left');
        $this->db->join('gotra g', 'g.id = u.gotra_id', 'left');
        $this->db->join('mosaads m', 'm.id = u.mosaad_id', 'left');
        $this->db->join('business_categories bc', 'bc.id = u.business_category_id', 'left');
        $this->db->join('business_sub_categories bsc', 'bsc.id = u.business_sub_category_id', 'left');
        $this->db->join('educations e', 'e.id = u.education_id', 'left');
        $this->db->join('occupation o', 'o.id = u.occupation_id', 'left');
        $this->db->where('u.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function getSharedProfile($id){
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $data = [];
        // print_r($result);die;
        if ($result['sharing_id']) {
            $this->db->select('u.*');
            $this->db->from('users u');
            // $this->db->where('u.id', $id);
            $this->db->where("u.id IN (" .$result['sharing_id']. ")");
            $query = $this->db->get();
            $data = $query->result_array();
        }
        return $data;
    }

    public function getSharingProfile($id){
        $this->db->select('u.*');
        $this->db->from('users u');
        // $this->db->where('u.id');
        // $this->db->where("FIND_IN_SET('u.sharing_id',".$id.")",null,false);
        $this->db->where('FIND_IN_SET("'.$id.'", u.sharing_id) <> 0');
        $query = $this->db->get();
        $result = $query->result_array();
        // print_r($result);die;
        return $result;
    }

    public function getFiles($id) {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('id',$id);
        $query = $this->db->get();
        $user = $query->row_array();
        $result = [];
        if ($user) {
            $fromUser = [];
            if ($user['role']=='LOCAL_ADMIN') {
                $this->db->select('id');
                $this->db->from('users');
                $this->db->where('local_community_id',$user['local_community_id']);
                $this->db->where('role','USER');
                $query = $this->db->get();
                $fromUser = $query->result_array();
            }
            // if ($user['role']=='SUB_ADMIN') {
            //     # code...
            // }
            if ($user['role']=='USER') {
                $this->db->select('id');
                $this->db->from('users');
                $this->db->where('local_community_id',$user['local_community_id']);
                $this->db->where('role','LOCAL_ADMIN');
                $query = $this->db->get();
                $fromUser = $query->result_array();
            }
            $fromUser[] = ['id'=>$id];
            // print_r($fromUser);die;
            if (!empty($fromUser)) {
                $fromUser = array_column($fromUser, 'id');
                $this->db->select("admin_files.*,CASE WHEN filename = '' THEN '' ELSE CONCAT('".base_url()."uploads/files/',filename)  END AS file_url,u.first_name,u.last_name");
                $this->db->from('admin_files');
                $this->db->join('users u', 'u.id = admin_files.user_id', 'left');
                $this->db->where_in('user_id',$fromUser);
                $this->db->order_by('created_at','desc');
                $query = $this->db->get();
                $result = $query->result_array();
                // return $result;
            }
        }
        return $result;
        // echo base_url();die;
    }

    public function getContactList($contacts) {
        //$return = [];

        $this->db->from('users');
        $this->db->where_in('mobile',$contacts);
        $this->db->where('status !=','0');
        $query = $this->db->get();
        $result = $query->result_array();

       /* foreach ($contacts as $key => $value) {
            $mobile = substr($value, -10);
            $this->db->select("*,(select count(*) from users u1 where u1.head_id = users.id) as member_count,");
            $this->db->from('users');
            $this->db->where("mobile LIKE '%".$mobile."%'");
            $query = $this->db->get();
            $result = $query->row_array();
            if (!empty($result)) {
                $return[] = $result; 
            }
        }*/
        // print_r($return);die;
        return $result;
    }

    public function getMasterUpdateCounts() {
        $this->db->select("table_name,counts");
        $this->db->from('updated_counts');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getStatusMatrimonyCounts($subCommId){
        
        $concat="";
        if($subCommId>0){
         $concat = " AND sub_community_id=".$subCommId;
        }
        
        $this->db->select("COUNT(CASE WHEN matrimony = 'Yes' AND is_expired = '0' AND marital_status  != 'Married'".$concat."  THEN 1 END) AS matrimony_counts, COUNT(IF(status != '1',1,NULL)) AS status_counts");
        $this->db->from('users');
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    
    
}
?>

