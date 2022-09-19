<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');
$array1 = array('blue'  => 1, 'red'  => 2, 'green'  => 3, 'purple' => 4);
$array2 = array('green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan'   => 8);

class GetFamilyMembers extends REST_Controller
{
    
    
    function index()
    {
       if (($this->flag) == "1") {

        $data = $this->request_paramiters;
        // print_r($data);die;
        $head_id = $data['head_id'];
        $dataList = $this->Users_model->getMembers($head_id);
        if (isset($data['id'])) {
            $this->db->query("UPDATE users SET `last_login`='".date('Y-m-d H:i:s')."' WHERE id=".$data['id']);
        }
        
        $profile_keys = array();    
        for($i=0;$i<count($dataList);$i++)
        {
            $mandatory = array('id','email_address',
                'gender',
                'address',
                'mobile',
                'birth_date',
                'birth_time',
                'birth_place',
                'distinct_id',
                'native_place_id',
                'blood_group',
                'current_activity_id',
                'gotra_id',
                'profile_pic',
                'region',
                'is_rented',
                'is_donor',
                'business_category_id',
                'business_sub_category_id',
                'work_details',
                'company_name',
                'business_address',
                'education_id',
                'occupation_id',
                'designation_id'
            );
                
            if($dataList[$i]['marital_status'] != '') {
                array_push($mandatory,'marriage_date','mosaad_id');
            }
            if($dataList[$i]['matrimony'] == 'Yes') {
                array_push($mandatory,'about_me','weight','height','is_spect','is_mangal','is_shani','hobby','facebook_profile','expectation');
            }
            if($dataList[$i]['is_expired'] == '1') {
                array_push($mandatory,'expire_date');
            }
        
            for($j=0;$j<count($mandatory);$j++)
            {
                $profile_keys[$i][$mandatory[$j]] = ($dataList[$i][$mandatory[$j]] != '' && $dataList[$i][$mandatory[$j]] != 0) ? $dataList[$i][$mandatory[$j]] : '';
            }

            $online = 0;
            $lastTime = strtotime($dataList[$i]['last_login']);
            $currentTime = strtotime(date('Y-m-d H:i:s'))-(60*3.5);
            if ($currentTime<=$lastTime) {
                if ($dataList[$i]['login_status']) {
                    $online = 1;
                }
            }
            $dataList[$i]['login_status'] = intval($dataList[$i]['login_status']);
            $dataList[$i]['last_login'] = $dataList[$i]['login_status'];
            $dataList[$i]['online_status'] = $online;
        }
        
        for($i=0;$i<count($profile_keys);$i++) {
            $dataList[$i]['profile_completed'] = round(count(array_filter($profile_keys[$i]))/count($profile_keys[$i]) * 100) . '%';
        }
        
        $relation = $this->db->query("SELECT * FROM `relations` WHERE `name` = 'Wife' ")->row_array();
        
        $i=0; $profile_users = array();
        if(!empty($relation))
        {
            $found = 0;
            foreach($dataList as $profile)
            {
                if($profile['head_id'] == 0){
                    $profile_users[0] = $profile;
                    $i=1;
                    $found = 1;
                }
            }
            
            foreach($dataList as $profile) {
                if($profile['relation_id'] ==  $relation['id'])     {
                    $profile_users[1] = $profile;
                    $found = 2;
                    $i=2;
                }
            }
            
            if($found == 1)
            {   
                foreach($dataList as $profile)
                {
                    if($profile['head_id'] != 0)    {
                        
                        $profile_users[$i] = $profile;
                        $i++;
                    }
                }
            }else if($found == 2){
                
                foreach($dataList as $profile)
                {
                    if($profile['head_id'] != 0 && $profile['relation_id'] !=  $relation['id'])
                    {
                        $profile_users[$i] = $profile;
                        $i++;
                    }
                }
            }
          
        }
        else {
            $i = 0;
            foreach($dataList as $profile){
                if($profile['head_id'] == 0){
                    $profile_users[0] = $profile;
                    $i=1;
                }
            }
            
            foreach($dataList as $profile) {
               if($profile['head_id'] != 0){
                        $profile_users[$i] = $profile;
                        $i++;
                }
            }
            
        }
        //print_r($profile_users); exit();
        $response = array('success' => true, 'total_records' => count($profile_users), "members" => $profile_users);
        echo json_encode($response);
        exit;
       }
       
    }
}