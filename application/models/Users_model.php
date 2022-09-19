<?php

class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->table = "users";
    }
    
    function get_global_search($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = [], $whereCondition="") {
        $userData = $this->session->userdata('backEndLogin');
        //define columns for searching
        
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state");
        
        $q = array();
        // foreach($searchby as $searchby) {
           $k = strtolower($searchby);
           array_push($q,"(users.first_name LIKE '%".$k."%') AS `{$k}_found_in_first_name`");
           array_push($q,"(users.email_address LIKE '%".$k."%') AS `{$k}_found_in_email_address`"); 
           array_push($q,"(users.address LIKE '%".$k."%') AS `{$k}_found_in_address`");
           array_push($q,"(users.mobile LIKE '%".$k."%') AS `{$k}_found_in_mobile`");
           array_push($q,"(users.phone LIKE '%".$k."%') AS `{$k}_found_in_phone`");
           array_push($q,"(users.work_details LIKE '%".$k."%') AS `{$k}_found_in_work_details`");
           array_push($q,"(users.business_address LIKE '%".$k."%') AS `{$k}_found_in_business_address`");
           array_push($q,"(users.city_id = (SELECT id FROM cities WHERE city LIKE '%".$k."%' LIMIT 1)) AS `{$k}_found_in_city`");
           array_push($q,"(users.sub_cast_id IN (SELECT id FROM sub_casts WHERE name LIKE '%".$k."%')) AS `{$k}_found_in_sub_cast`");
           array_push($q,"(users.business_category_id IN (SELECT id FROM business_categories WHERE name LIKE '%".$k."%')) AS `{$k}_found_in_business_category`");
           array_push($q,"(users.business_sub_category_id IN (SELECT id FROM business_sub_categories WHERE name LIKE '%".$k."%')) AS `{$k}_found_in_business_sub_category`");
           array_push($q,"(users.current_activity_id IN (SELECT id FROM current_activity WHERE activity LIKE '%".$k."%')) AS `{$k}_found_in_current_activity`");
           array_push($q,"(users.education_id IN (SELECT id FROM educations WHERE name LIKE '%".$k."%')) AS `{$k}_found_in_education`");
           array_push($q,"(users.occupation_id IN (SELECT id FROM occupation WHERE occupation LIKE '%".$k."%')) AS `{$k}_found_in_occupation`");
        // }
        
        $q = implode(",",$q);
        $this->db->select("users.*,$q,
        cities.city,(select count(*) from users u1 where u1.head_id = users.id and u1.is_expired = '0') as member_count");
        
        $this->db->from($this->table);
        $this->db->join('cities', 'cities.id = users.city_id', 'left');
        $this->db->join('states', 'states.id = users.state_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = users.sub_community_id', 'left');
        $this->db->join('relations r', 'r.id = users.relation_id', 'left');
        $this->db->join('local_community lc', 'lc.id = users.local_community_id', 'left');
        $this->db->join('designations d', 'users.designation_id = d.id', 'left');
        $this->db->join('native n', 'users.native_place_id = n.id', 'left');
        $this->db->join('mosaads m', 'users.mosaad_id = m.id', 'left');
        $this->db->join('current_activity a', 'users.current_activity_id = a.id', 'left');
        $this->db->join('gotra g', 'users.gotra_id = g.id', 'left');
        $this->db->join('business_categories bc', 'users.business_category_id = bc.id', 'left');
        $this->db->join('business_sub_categories bsc', 'users.business_sub_category_id = bsc.id', 'left');
        $this->db->join('educations e', 'users.education_id = e.id', 'left');
        $this->db->join('occupation o', 'users.occupation_id = o.id', 'left');
        $this->db->where('users.status!=0');
        
        if(!empty($whereCondition)) {
            $this->db->where($whereCondition);
        }
        // $this->db->where('users.status',1);
        // Addtitional Filtering
        $sWhere = "";
        if ($searchParamVal != "") {
            $sWhere = "(";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        
        if(isset($searchby)) {
            $wc = "";
            // foreach($searchby as $k) {
                $k = $searchby;
               $wc .= "users.first_name LIKE '%".$k."%' 
               OR users.email_address LIKE '%".$k."%' 
               OR users.address LIKE '%".$k."%' 
               OR users.mobile LIKE '%".$k."%' 
               OR users.phone LIKE '%".$k."%' 
               OR users.work_details LIKE '%".$k."%' 
               OR users.business_address LIKE '%".$k."%'
               OR users.city_id IN (SELECT id FROM cities WHERE city LIKE '%".$k."%')
               OR users.sub_cast_id IN (SELECT id FROM sub_casts WHERE name LIKE '%".$k."%')
               OR users.business_category_id IN (SELECT id FROM business_categories WHERE name LIKE '%".$k."%')
               OR users.business_sub_category_id IN (SELECT id FROM business_sub_categories WHERE name LIKE '%".$k."%')
               OR users.current_activity_id IN (SELECT id FROM current_activity WHERE activity LIKE '%".$k."%')
               OR users.education_id IN (SELECT id FROM educations WHERE name LIKE '%".$k."%')
               OR users.occupation_id IN (SELECT id FROM occupation WHERE occupation LIKE '%".$k."%')
               OR "; 
            // }
            $wc = substr($wc,0,strlen($wc)-4);
        }
        if(!empty($wc)) {
            $this->db->where('('.$wc.')');
        }
        
       // echo "kunjan ".$userData['role'];
        
        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {
            if ($userData['role'] == "SUB_ADMIN") {
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);
            }
            if ($userData['role'] == "LOCAL_ADMIN") {
                $this->db->where('users.local_community_id', $userData['local_community_id']);
            }
            $this->db->where('users.id != ', $userData['id']);
        }
        
        
      //  $this->db->where('users.sub_community_id', $userData['sub_community_id']);
        
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN","SUPERADMIN")');

        
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        if (!empty($orderBy) && !empty($orderByVal)) {
            $this->db->order_by($orderBy, $orderByVal);
        }

        $posts = $this->db->get()->result_array();
        
        $match = array();
        $i=0;
        
        foreach($posts as $p) {
            $sb=0;
            // foreach($searchby as $sk) {
                $matched = array();
                $sk = strtolower(str_replace(" ","_",$searchby));
                
                // if(!is_array($k)) {
                //     ${$k} = array();
                // }
                
                if($p[$sk.'_found_in_first_name'] == 1) {
                    array_push($matched,'first_name');
                }
                unset($posts[$i][$sk.'_found_in_first_name']);
                
                if($p[$sk.'_found_in_email_address'] == 1){
                    array_push($matched,'email_address');
                }
                unset($posts[$i][$sk.'_found_in_email_address']);
                
                if($p[$sk.'_found_in_address'] == 1){
                    array_push($matched,'address');
                }
                unset($posts[$i][$sk.'_found_in_address']);
                
                if($p[$sk.'_found_in_mobile'] == 1){
                    array_push($matched,'mobile');
                }
                unset($posts[$i][$sk.'_found_in_mobile']);
                
                if($p[$sk.'_found_in_phone'] == 1){
                    array_push($matched,'phone');
                }
                unset($posts[$i][$sk.'_found_in_phone']);
                
                if($p[$sk.'_found_in_work_details'] == 1){
                    array_push($matched,'work_details');
                }
                unset($posts[$i][$sk.'_found_in_work_details']);
                
                if($p[$sk.'_found_in_business_address'] == 1){
                    array_push($matched,'business_address');
                }
                unset($posts[$i][$sk.'_found_in_business_address']);
                
                if($p[$sk.'_found_in_city'] == 1){
                    array_push($matched,'city');
                }
                unset($posts[$i][$sk.'_found_in_city']);
                
                if($p[$sk.'_found_in_sub_cast'] == 1){
                    array_push($matched,'sub_cast');
                }
                unset($posts[$i][$sk.'_found_in_sub_cast']);
                
                if($p[$sk.'_found_in_business_category'] == 1){
                    array_push($matched,'business_category');
                }
                unset($posts[$i][$sk.'_found_in_business_category']);
                
                if($p[$sk.'_found_in_business_sub_category'] == 1){
                    array_push($matched,'business_sub_category');
                }
                unset($posts[$i][$sk.'_found_in_business_sub_category']);
                
                if($p[$sk.'_found_in_current_activity'] == 1){
                    array_push($matched,'current_activity');
                }
                unset($posts[$i][$sk.'_found_in_current_activity']);
                
                if($p[$sk.'_found_in_education'] == 1){
                    array_push($matched,'education');
                }
                unset($posts[$i][$sk.'_found_in_education']);
                
                if($p[$sk.'_found_in_occupation'] == 1){
                    array_push($matched,'occupation');
                }
                unset($posts[$i][$sk.'_found_in_occupation']);
                
                //$posts[$i]['matches'][$sk] = $matched;
                if(count($matched) > 0) {
                    $posts[$i]['matches'] = $matched;
                    $sb++;
                }
                // print_r($matched);
                
                //$posts[$i]['matches'][] = 
                // $object = new stdClass();
                // $object->$key = $value;
                
            // }
            $i++;
        }
        
        
        
        //echo $this->db->last_query();exit;
        
        
        
        if (count($posts)) {
            return $posts;
        } else {
            return array();
        }
    }
    
    
     function get_global_search_count($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = []) {	
        $userData = $this->session->userdata('backEndLogin');	
        //define columns for searching	
        	
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state");	
        	
        $q = array();	
        // foreach($searchby as $searchby) {	
           $k = strtolower($searchby);	
           array_push($q,"(users.first_name LIKE '%".$k."%') AS `{$k}_found_in_first_name`");	
           array_push($q,"(users.email_address LIKE '%".$k."%') AS `{$k}_found_in_email_address`"); 	
           array_push($q,"(users.address LIKE '%".$k."%') AS `{$k}_found_in_address`");	
           array_push($q,"(users.mobile LIKE '%".$k."%') AS `{$k}_found_in_mobile`");	
           array_push($q,"(users.phone LIKE '%".$k."%') AS `{$k}_found_in_phone`");	
           array_push($q,"(users.work_details LIKE '%".$k."%') AS `{$k}_found_in_work_details`");	
           array_push($q,"(users.business_address LIKE '%".$k."%') AS `{$k}_found_in_business_address`");	
           array_push($q,"(users.city_id = (SELECT id FROM cities WHERE city LIKE '%".$k."%' LIMIT 1)) AS `{$k}_found_in_city`");	
           array_push($q,"(users.sub_cast_id IN (SELECT id FROM sub_casts WHERE name LIKE '%".$k."%')) AS `{$k}_found_in_sub_cast`");	
           array_push($q,"(users.business_category_id IN (SELECT id FROM business_categories WHERE name LIKE '%".$k."%')) AS `{$k}_found_in_business_category`");	
           array_push($q,"(users.business_sub_category_id IN (SELECT id FROM business_sub_categories WHERE name LIKE '%".$k."%')) AS `{$k}_found_in_business_sub_category`");	
           array_push($q,"(users.current_activity_id IN (SELECT id FROM current_activity WHERE activity LIKE '%".$k."%')) AS `{$k}_found_in_current_activity`");	
           array_push($q,"(users.education_id IN (SELECT id FROM educations WHERE name LIKE '%".$k."%')) AS `{$k}_found_in_education`");	
           array_push($q,"(users.occupation_id IN (SELECT id FROM occupation WHERE occupation LIKE '%".$k."%')) AS `{$k}_found_in_occupation`");	
        // }	
        	
        $q = implode(",",$q);	
        $this->db->select("users.*,cities.city,states.state");	
        	
        $this->db->from($this->table);	
        $this->db->join('cities', 'cities.id = users.city_id', 'left');	
        $this->db->join('states', 'states.id = users.state_id', 'left');	
        $this->db->where('users.status!=0');	
        $this->db->where('users.is_expired = "0"');	
        // Addtitional Filtering	
        $sWhere = "";	
        if ($searchParamVal != "") {	
            $sWhere = "(";	
            for ($i = 0; $i < count($aColumns); $i++) {	
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";	
            }	
            $sWhere = substr_replace($sWhere, "", -3);	
            $sWhere .= ')';	
        }	
        	
        if(isset($searchby)) {	
            $wc = "";	
            	
            $k = $searchby;	
            $wc .= "users.first_name LIKE '%".$k."%' 	
               OR users.email_address LIKE '%".$k."%' 	
               OR users.address LIKE '%".$k."%' 	
               OR users.mobile LIKE '%".$k."%' 	
               OR users.phone LIKE '%".$k."%' 	
               OR users.work_details LIKE '%".$k."%' 	
               OR users.business_address LIKE '%".$k."%'	
               OR users.city_id IN (SELECT id FROM cities WHERE city LIKE '%".$k."%')	
               OR users.sub_cast_id IN (SELECT id FROM sub_casts WHERE name LIKE '%".$k."%')	
               OR users.business_category_id IN (SELECT id FROM business_categories WHERE name LIKE '%".$k."%')	
               OR users.business_sub_category_id IN (SELECT id FROM business_sub_categories WHERE name LIKE '%".$k."%')	
               OR users.current_activity_id IN (SELECT id FROM current_activity WHERE activity LIKE '%".$k."%')	
               OR users.education_id IN (SELECT id FROM educations WHERE name LIKE '%".$k."%')	
               OR users.occupation_id IN (SELECT id FROM occupation WHERE occupation LIKE '%".$k."%')	
               OR "; 	
            	
            $wc = substr($wc,0,strlen($wc)-4);	
        }	
        if(!empty($wc)) {	
            $this->db->where('('.$wc.')');	
        }	
        	
        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {	
            if ($userData['role'] == "SUB_ADMIN") {	
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);	
            }	
            if ($userData['role'] == "LOCAL_ADMIN") {	
                $this->db->where('users.local_community_id', $userData['local_community_id']);	
            }	
            $this->db->where('users.id != ', $userData['id']);	
        }	
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN","SUPERADMIN")');	
        	
        if ($start >= 0 && $length > 0) {	
            $this->db->limit($length, $start);	
        }	
        if (!empty($orderBy) && !empty($orderByVal)) {	
            $this->db->order_by($orderBy, $orderByVal);	
        }	
        $posts = $this->db->get()->result_array();	
        return $posts;	
    }	
    
    
    function get_search_datatables($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = []) {
        $userData = $this->session->userdata('backEndLogin');
        //define columns for searching
        
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state");

        $this->db->select("users.*,
        (select name from sub_casts where id = users.sub_cast_id) as last_name, 
        (select count(*) from users u1 where u1.head_id = users.id and u1.is_expired = '0') as member_count, 
        cities.city,
        states.state,
        sc.name as sub_community,
        lc.name as local_community,
        r.name as relation,
        d.name as designation,
        n.native,
        m.name as mossad,
        a.activity as current_activity,
        g.gotra,
        h.first_name as head_name,
        h.sub_cast_id as head_sub_cast_id,
        bc.name as business_category,
        bsc.name as business_sub_category,
        e.name as education,
        o.occupation");
        $this->db->from($this->table);
        $this->db->join('cities', 'cities.id = users.city_id', 'left');
        $this->db->join('states', 'states.id = users.state_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = users.sub_community_id', 'left');
        $this->db->join('relations r', 'r.id = users.relation_id', 'left');
        $this->db->join('local_community lc', 'lc.id = users.local_community_id', 'left');
        $this->db->join('designations d', 'users.designation_id = d.id', 'left');
        $this->db->join('native n', 'users.native_place_id = n.id', 'left');
        $this->db->join('mosaads m', 'users.mosaad_id = m.id', 'left');
        $this->db->join('current_activity a', 'users.current_activity_id = a.id', 'left');
        $this->db->join('gotra g', 'users.gotra_id = g.id', 'left');
        $this->db->join('business_categories bc', 'users.business_category_id = bc.id', 'left');
        $this->db->join('business_sub_categories bsc', 'users.business_sub_category_id = bsc.id', 'left');
        $this->db->join('educations e', 'users.education_id = e.id', 'left');
        $this->db->join('occupation o', 'users.occupation_id = o.id', 'left');
        $this->db->join('users h', 'users.head_id = h.id', 'left');
        $this->db->where('users.status!=0');
        // $this->db->where('users.status',1);
        // Addtitional Filtering
        $sWhere = "";
        if ($searchParamVal != "") {
            $sWhere = "(";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        // echo $sWhere;die;

        $exclude = array("member_name","head_name","address","local_address","area","min_age","max_age","birth_date","marriage_date","business_address","created_dt","updated_dt","member_code","id","birth_place","email_address","min_height","max_height","min_weight","max_weight","min_percentage","max_percentage");

        $searchbyExcluded = array_diff_key($searchby, array_flip($exclude));

        if(!empty($searchbyExcluded)) {
            $searchbyExcluded = array_flip($searchbyExcluded);
            foreach($searchbyExcluded as $key => &$val)
            {
                $val = "users." . $val;
            }
            $searchbyExcluded = array_flip($searchbyExcluded);
            $this->db->where($searchbyExcluded);
        }

        if(isset($searchby['member_name'])) {
            $this->db->where("(users.first_name LIKE '%".$searchby['member_name']."%')");
        }
        
        if(isset($searchby['email_address'])) {
            $this->db->where("(users.email_address LIKE '%".$searchby['email_address']."%')");
        }
        
        if(isset($searchby['birth_place'])) {
            $this->db->where("(users.birth_place LIKE '%".$searchby['birth_place']."%')");
        }

        if(isset($searchby['min_height']) && isset($searchby['max_height'])) {
            $this->db->where("users.height BETWEEN '".$searchby['min_height']."' AND '".$searchby['max_height']."'");
        }

        if(isset($searchby['min_weight']) && isset($searchby['max_weight'])) {
            $this->db->where("users.weight BETWEEN '".$searchby['min_weight']."' AND '".$searchby['max_weight']."'");
        }

        if(isset($searchby['min_percentage']) && isset($searchby['max_percentage'])) {
            $this->db->where("users.profile_percent BETWEEN '".$searchby['min_percentage']."' AND '".$searchby['max_percentage']."'");
        }
        
        if(isset($searchby['address'])) {
            $this->db->where("(users.address LIKE '%".$searchby['address']."%')");
        }
        
        if(isset($searchby['head_name'])) {
            $this->db->where("(h.first_name LIKE '%".$searchby['head_name']."%')");
        }
        
        if(isset($searchby['member_code'])) {
            $this->db->where("(users.member_code = '".$searchby['member_code']."')");
        }
        
        if(isset($searchby['id'])) {
            $this->db->where("(users.id = '".$searchby['id']."')");
        }

        if(isset($searchby['matrimony'])) {
            $this->db->where("(users.matrimony = '".$searchby['matrimony']."')");
            if ($searchby['matrimony']=='Yes') {
                $this->db->where("(users.marital_status != 'Married')");
            }
        }
        
        if(isset($searchby['local_address'])) {
            $this->db->where("(users.local_address LIKE '%".$searchby['local_address']."%')");
        }
        
        if(isset($searchby['area'])) {
            $this->db->where("(users.area LIKE '%".$searchby['area']."%')");
        }
        
        if(isset($searchby['min_age']) || isset($searchby['max_age'])) {
            $this->db->where("(users.birth_date BETWEEN '".date('Y-m-d', strtotime($searchby['max_age'] . ' years ago'))."' AND '".date('Y-m-d', strtotime($searchby['min_age'] . ' years ago'))."')");
        }
        
        if(isset($searchby['birth_date'])) {
            list($by,$bm,$bd) = explode("-",$searchby['birth_date']);
            
            if($by != '00') {
                $this->db->where("(YEAR(users.birth_date) = '".$by."')");
            }
            if($bm != '00') {
                $this->db->where("(MONTH(users.birth_date) = '".$bm."')");
            }
            if($bd != '00') {
                $this->db->where("(DAY(users.birth_date) = '".$bd."')");
            }
        }
        
        if(isset($searchby['marriage_date'])) {
            list($by,$bm,$bd) = explode("-",$searchby['marriage_date']);
            
            if($by != '00') {
                $this->db->where("(YEAR(users.marriage_date) = '".$by."')");
            }
            if($bm != '00') {
                $this->db->where("(MONTH(users.marriage_date) = '".$bm."')");
            }
            if($bd != '00') {
                $this->db->where("(DAY(users.marriage_date) = '".$bd."')");
            }
        }
        
        if(isset($searchby['business_address'])) {
            $this->db->where("(users.business_address LIKE '%".$searchby['business_address']."%')");
        }
        

        if (!empty($sWhere)) {
            $this->db->where($sWhere);
        }

        if (!empty($searchWhere)) {
            $this->db->where($searchWhere);
        }

        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {
            if ($userData['role'] == "SUB_ADMIN") {
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);
            }
            if ($userData['role'] == "LOCAL_ADMIN") {
                $this->db->where('users.local_community_id', $userData['local_community_id']);
            }
            $this->db->where('users.id != ', $userData['id']);
        }
        
        // if(isset($searchby['created_dt'])) {
        //     list($by,$bm,$bd) = explode("-",$searchby['created_dt']);
            
        //     if($by != '00') {
        //         $this->db->where("(YEAR(users.created_dt) = '".$by."')");
        //     }
        //     if($bm != '00') {
        //         $this->db->where("(MONTH(users.created_dt) = '".$bm."')");
        //     }
        //     if($bd != '00') {
        //         $this->db->where("(DAY(users.created_dt) = '".$bd."')");
        //     }
        // }
        if(isset($searchby['created_dt'])) {
            $this->db->where("DATE_FORMAT(CAST(users.created_dt as DATE), '%Y-%m-%d')='".$searchby['created_dt']."'");
        }

        if(isset($searchby['updated_dt'])) {
            $this->db->where("DATE_FORMAT(CAST(users.updated_dt as DATE), '%Y-%m-%d')='".$searchby['updated_dt']."'");
        }
        
        // if(isset($searchby['updated_dt'])) {
        //     list($by,$bm,$bd) = explode("-",$searchby['updated_dt']);
            
        //     if($by != '00') {
        //         $this->db->where("(YEAR(users.updated_dt) = '".$by."')");
        //     }
        //     if($bm != '00') {
        //         $this->db->where("(MONTH(users.updated_dt) = '".$bm."')");
        //     }
        //     if($bd != '00') {
        //         $this->db->where("(DAY(users.updated_dt) = '".$bd."')");
        //     }
        // }

        
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN","SUPERADMIN")');

        
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        if (!empty($orderBy) && !empty($orderByVal)) {
            $this->db->order_by($orderBy, $orderByVal);
        }

        $posts = $this->db->get()->result_array();

        //echo $this->db->last_query();exit;

        if (count($posts)) {
            return $posts;
        } else {
            return array();
        }
    }

 function get_search_datatables_count($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = []) {	
        $userData = $this->session->userdata('backEndLogin');	
        //define columns for searching	
        	
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state");	
        $this->db->select("users.*, h.first_name as head_name,	
        h.sub_cast_id as head_sub_cast_id");	
        $this->db->from($this->table);	
        $this->db->join('users h', 'users.head_id = h.id', 'left');	
        $this->db->where('users.status!=0');	
        $this->db->where('users.is_expired = "0"');	
        // Addtitional Filtering	
        $sWhere = "";	
        if ($searchParamVal != "") {	
            $sWhere = "(";	
            for ($i = 0; $i < count($aColumns); $i++) {	
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";	
            }	
            $sWhere = substr_replace($sWhere, "", -3);	
            $sWhere .= ')';	
        }	
        // echo $sWhere;die;	
        $exclude = array("member_name","head_name","address","local_address","area","min_age","max_age","birth_date","marriage_date","business_address","created_dt","updated_dt","member_code","id","birth_place","email_address","min_height","max_height","min_weight","max_weight","min_percentage","max_percentage");	
        $searchbyExcluded = array_diff_key($searchby, array_flip($exclude));	
        if(!empty($searchbyExcluded)) {	
            $searchbyExcluded = array_flip($searchbyExcluded);	
            foreach($searchbyExcluded as $key => &$val)	
            {	
                $val = "users." . $val;	
            }	
            $searchbyExcluded = array_flip($searchbyExcluded);	
            $this->db->where($searchbyExcluded);	
        }	
        if(isset($searchby['member_name'])) {	
            $this->db->where("(users.first_name LIKE '%".$searchby['member_name']."%')");	
        }	
        	
        if(isset($searchby['email_address'])) {	
            $this->db->where("(users.email_address LIKE '%".$searchby['email_address']."%')");	
        }	
        	
        if(isset($searchby['birth_place'])) {	
            $this->db->where("(users.birth_place LIKE '%".$searchby['birth_place']."%')");	
        }	
        if(isset($searchby['min_height']) && isset($searchby['max_height'])) {	
            $this->db->where("users.height BETWEEN '".$searchby['min_height']."' AND '".$searchby['max_height']."'");	
        }	
        if(isset($searchby['min_weight']) && isset($searchby['max_weight'])) {	
            $this->db->where("users.weight BETWEEN '".$searchby['min_weight']."' AND '".$searchby['max_weight']."'");	
        }	
        if(isset($searchby['min_percentage']) && isset($searchby['max_percentage'])) {	
            $this->db->where("users.profile_percent BETWEEN '".$searchby['min_percentage']."' AND '".$searchby['max_percentage']."'");	
        }	
        	
        if(isset($searchby['address'])) {	
            $this->db->where("(users.address LIKE '%".$searchby['address']."%')");	
        }	
        	
        if(isset($searchby['head_name'])) {	
            $this->db->where("(h.first_name LIKE '%".$searchby['head_name']."%')");	
        }	
        	
        if(isset($searchby['member_code'])) {	
            $this->db->where("(users.member_code = '".$searchby['member_code']."')");	
        }	
        	
        if(isset($searchby['id'])) {	
            $this->db->where("(users.id = '".$searchby['id']."')");	
        }	
        if(isset($searchby['matrimony'])) {	
            $this->db->where("(users.matrimony = '".$searchby['matrimony']."')");	
            if ($searchby['matrimony']=='Yes') {	
                $this->db->where("(users.marital_status != 'Married')");	
            }	
        }	
        	
        if(isset($searchby['local_address'])) {	
            $this->db->where("(users.local_address LIKE '%".$searchby['local_address']."%')");	
        }	
        	
        if(isset($searchby['area'])) {	
            $this->db->where("(users.area LIKE '%".$searchby['area']."%')");	
        }	
        	
        if(isset($searchby['min_age']) || isset($searchby['max_age'])) {	
            $this->db->where("(users.birth_date BETWEEN '".date('Y-m-d', strtotime($searchby['max_age'] . ' years ago'))."' AND '".date('Y-m-d', strtotime($searchby['min_age'] . ' years ago'))."')");	
        }	
        	
        if(isset($searchby['birth_date'])) {	
            list($by,$bm,$bd) = explode("-",$searchby['birth_date']);	
            	
            if($by != '00') {	
                $this->db->where("(YEAR(users.birth_date) = '".$by."')");	
            }	
            if($bm != '00') {	
                $this->db->where("(MONTH(users.birth_date) = '".$bm."')");	
            }	
            if($bd != '00') {	
                $this->db->where("(DAY(users.birth_date) = '".$bd."')");	
            }	
        }	
        	
        if(isset($searchby['marriage_date'])) {	
            list($by,$bm,$bd) = explode("-",$searchby['marriage_date']);	
            	
            if($by != '00') {	
                $this->db->where("(YEAR(users.marriage_date) = '".$by."')");	
            }	
            if($bm != '00') {	
                $this->db->where("(MONTH(users.marriage_date) = '".$bm."')");	
            }	
            if($bd != '00') {	
                $this->db->where("(DAY(users.marriage_date) = '".$bd."')");	
            }	
        }	
        	
        if(isset($searchby['business_address'])) {	
            $this->db->where("(users.business_address LIKE '%".$searchby['business_address']."%')");	
        }	
        	
        if (!empty($sWhere)) {	
            $this->db->where($sWhere);	
        }	
        if (!empty($searchWhere)) {	
            $this->db->where($searchWhere);	
        }	
        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {	
            if ($userData['role'] == "SUB_ADMIN") {	
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);	
            }	
            if ($userData['role'] == "LOCAL_ADMIN") {	
                $this->db->where('users.local_community_id', $userData['local_community_id']);	
            }	
            $this->db->where('users.id != ', $userData['id']);	
        }	
        	
        if(isset($searchby['created_dt'])) {	
            $this->db->where("DATE_FORMAT(CAST(users.created_dt as DATE), '%Y-%m-%d')='".$searchby['created_dt']."'");	
        }	
        if(isset($searchby['updated_dt'])) {	
            $this->db->where("DATE_FORMAT(CAST(users.updated_dt as DATE), '%Y-%m-%d')='".$searchby['updated_dt']."'");	
        }	
        	
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN","SUPERADMIN")');	
        	
        if ($start >= 0 && $length > 0) {	
            $this->db->limit($length, $start);	
        }	
        if (!empty($orderBy) && !empty($orderByVal)) {	
            $this->db->order_by($orderBy, $orderByVal);	
        }	
        $posts = $this->db->get()->result_array();	
        //echo $this->db->last_query();exit;	
        if (count($posts)) {	
            return $posts;	
        } else {	
            return array();	
        }	
    }	



    function get_datatables_for_api($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = [],$alpha, $whereCondition="") {
        $userData = $this->session->userdata('backEndLogin');
        //define columns for searching
        
        
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
       // $this->db->select("users.head_id,users.member_code,CASE WHEN users.profile_pic = '' THEN '' ELSE CONCAT('http://muslimghanchisamaj.in/uploads/users/thumb/',users.profile_pic) END AS profile_pic,users.id,users.first_name, (select name from sub_casts where id = users.sub_cast_id) as last_name, users.email_address,users.mobile,users.area,users.role,users.status,cities.city,states.state,sc.name as sub_community,lc.name as local_community");
        $this->db->select("users.*");
        // $this->db->select("users.*,CASE WHEN users.profile_pic = '' THEN '' ELSE CONCAT('http://muslimghanchisamaj.in/uploads/users/thumb/',users.profile_pic) END AS profile_pic");
        $this->db->from('users users');
        $this->db->join('cities', 'cities.id = users.city_id', 'left');
        $this->db->join('states', 'states.id = users.state_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = users.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = users.local_community_id', 'left');
        $this->db->join('sub_casts ca', 'ca.id = users.last_name', 'left');
        $this->db->where('users.status!=0');    
        
        if(!empty($whereCondition)) {
            $this->db->where($whereCondition);
        }
        // $this->db->where('users.status',1);        
        
        // Addtitional Filtering
        $sWhere = "";
        if ($searchParamVal != "") {
            $sWhere = "(";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        
        // $filter = "";
        // if(count($filterby) > 0) {
        //     $f = "";
        //     foreach($filterby as $k=>$v) {
        //         $f .= "users.".$k."=".$v. " AND ";  
        //     }
        //     $f = substr($f,0,-5);
        //     $filter = "(".$f.")";
        // }
        
        
        
        if(!empty($searchby)) {
            $searchby = array_flip($searchby);
            foreach($searchby as $key => &$val) { $val = "users." . $val; }
            $searchby = array_flip($searchby);
            
            $this->db->where($searchby);        
        }

        if (!empty($sWhere)) {
            $this->db->where($sWhere);
        }
        
        if (!empty($searchWhere)) {
            $this->db->where($searchWhere);
        }
        
        if($alpha != '') {
            $this->db->where("users.first_name LIKE '".$alpha."%'");
        }

        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {
            if ($userData['role'] == "SUB_ADMIN") {
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);
            }
            if ($userData['role'] == "LOCAL_ADMIN") {
                $this->db->where('users.local_community_id', $userData['local_community_id']);
            }
            $this->db->where('users.id != ', $userData['id']);
        }

        $this->db->where('users.head_id', '0');
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN","SUPERADMIN")');

        // $this->db->group_by('shortId');
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        if (!empty($orderBy) && !empty($orderByVal)) {
            $this->db->order_by($orderBy, $orderByVal);
        }

        $posts = $this->db->get()->result_array();

        //echo $this->db->last_query();exit;

        if (count($posts)) {
            foreach ($posts as $k => $v) {
                
                $posts[$k]['member_count'] = $this->get_members_counts($posts[$k]['id'], 1);
                
            //    $posts[$k]['members'] = $this->getMembers($posts[$k]['id'], 1);
                // $posts[$k]['member_count'] = count($this->getMembers($posts[$k]['id'], 1));
                //$posts[$k]['total_members'] = $this->get_members_counts($posts[$k]['id'], 1);
            }
            return $posts;
        } else {
            return array();
        }
    }



    function get_members_counts($head_id, $admin = 0) {	
        $this->db->select("count(u.id) as counts");	
        $this->db->from('users u');	
        $this->db->where('u.head_id', $head_id);	
        $this->db->where('u.is_expired = "0"');	
        $this->db->where('u.status != "0"');
      
        if ($admin != 1) {	
            $this->db->or_where('u.id', $head_id);	
        }
        $this->db->order_by('u.id','ASC');	
       	
        $query = $this->db->get();	
        $data = $query->row_array();	
        return $data['counts'];	
    }	
    function get_datatables_for_api_count($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = [],$alpha) {	
        $userData = $this->session->userdata('backEndLogin');	
        //define columns for searching	
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state");	
        $this->db->select("users.*");	
        $this->db->from('users users');	
        $this->db->join('cities', 'cities.id = users.city_id', 'left');	
        $this->db->join('states', 'states.id = users.state_id', 'left');	
        $this->db->where('users.status!=0');	
        $this->db->where('users.is_expired = "0"');	
        	
        // Addtitional Filtering	
        $sWhere = "";	
        if ($searchParamVal != "") {	
            $sWhere = "(";	
            for ($i = 0; $i < count($aColumns); $i++) {	
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";	
            }	
            $sWhere = substr_replace($sWhere, "", -3);	
            $sWhere .= ')';	
        }	
        	
        if(!empty($searchby)) {	
            $searchby = array_flip($searchby);	
            foreach($searchby as $key => &$val) { $val = "users." . $val; }	
            $searchby = array_flip($searchby);	
            	
            $this->db->where($searchby);        	
        }	
        if (!empty($sWhere)) {	
            $this->db->where($sWhere);	
        }	
        	
        if (!empty($searchWhere)) {	
            $this->db->where($searchWhere);	
        }	
        	
        if($alpha != '') {	
            $this->db->where("users.first_name LIKE '".$alpha."%'");	
        }	
        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {	
            if ($userData['role'] == "SUB_ADMIN") {	
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);	
            }	
            if ($userData['role'] == "LOCAL_ADMIN") {	
                $this->db->where('users.local_community_id', $userData['local_community_id']);	
            }	
            $this->db->where('users.id != ', $userData['id']);	
        }	
       // $this->db->where('users.head_id', '0');	
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN","SUPERADMIN")');	
        // $this->db->group_by('shortId');	
        if ($start >= 0 && $length > 0) {	
            $this->db->limit($length, $start);	
        }	
        if (!empty($orderBy) && !empty($orderByVal)) {	
            $this->db->order_by($orderBy, $orderByVal);	
        }	
        $posts = $this->db->get()->result_array();	
        return $posts;	
    }	



    function get_datatables($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = [],$alpha) {
        // echo base_url();die;
        $userData = $this->session->userdata('backEndLogin');
        //define columns for searching
        
        
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state", "n.native", "users.address", "users.gender");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
        $this->db->select("users.head_id,users.member_code,users.profile_pic,users.id,users.first_name, (select name from sub_casts where id = users.sub_cast_id) as last_name, users.email_address,users.mobile,users.area,users.role,users.status,cities.city,states.state,sc.name as sub_community,lc.name as local_community,users.gender,users.address,n.native");
        $this->db->from('users users');
        $this->db->join('cities', 'cities.id = users.city_id', 'left');
        $this->db->join('states', 'states.id = users.state_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = users.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = users.local_community_id', 'left');
        $this->db->join('sub_casts ca', 'ca.id = users.last_name', 'left');
        $this->db->join('native n', 'users.native_place_id = n.id', 'left');
            
         // $this->db->where('users.status',1);        
        
        // Addtitional Filtering
        $sWhere = "";
        if ($searchParamVal != "") {
            $sWhere = "(";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        
        // $filter = "";
        // if(count($filterby) > 0) {
        //     $f = "";
        //     foreach($filterby as $k=>$v) {
        //         $f .= "users.".$k."=".$v. " AND ";  
        //     }
        //     $f = substr($f,0,-5);
        //     $filter = "(".$f.")";
        // }
        
        
        
        if(!empty($searchby)) {
            $searchby = array_flip($searchby);
            foreach($searchby as $key => &$val) { $val = "users." . $val; }
            $searchby = array_flip($searchby);
            
            $this->db->where($searchby);        
        }

        if (!empty($sWhere)) {
            $this->db->where($sWhere);
        }
        
        if (!empty($searchWhere)) {
            $this->db->where($searchWhere);
        }
        
        if($alpha != '') {
            $this->db->where("users.first_name LIKE '".$alpha."%'");
        }

        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {
            if ($userData['role'] == "SUB_ADMIN") {
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);
            }
            if ($userData['role'] == "LOCAL_ADMIN") {
                $this->db->where('users.local_community_id', $userData['local_community_id']);
            }
            $this->db->where('users.id != ', $userData['id']);
        }

        $this->db->where('users.head_id', '0');
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN")');

        // $this->db->group_by('shortId');
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        if (!empty($orderBy) && !empty($orderByVal)) {
            $this->db->order_by($orderBy, $orderByVal);
        }

        $posts = $this->db->get()->result_array();

        //echo $this->db->last_query();exit;

        if (count($posts)) {
            foreach ($posts as $k => $v) {
                // $posts[$k]['members'] = $this->getMembersCount($posts[$k]['id'], 1);
                // $posts[$k]['members'] = count($this->getMembers($posts[$k]['id'], 1));
                $posts[$k]['memberscount'] = $this->getMembersCount($posts[$k]['id'], 1);
            }
            return $posts;
        } else {
            return array();
        }
    }

    function get_datatables_counts($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = [],$alpha) {
        // echo base_url();die;
        $userData = $this->session->userdata('backEndLogin');
        //define columns for searching
        
        
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state", "n.native", "users.address", "users.gender");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
        $this->db->select("count(*) as counts");
        $this->db->from('users users');
        $this->db->join('cities', 'cities.id = users.city_id', 'left');
        $this->db->join('states', 'states.id = users.state_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = users.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = users.local_community_id', 'left');
        $this->db->join('sub_casts ca', 'ca.id = users.last_name', 'left');
        $this->db->join('native n', 'users.native_place_id = n.id', 'left');
         // $this->db->where('users.status',1);        
        
        // Addtitional Filtering
        $sWhere = "";
        if ($searchParamVal != "") {
            $sWhere = "(";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        
        // $filter = "";
        // if(count($filterby) > 0) {
        //     $f = "";
        //     foreach($filterby as $k=>$v) {
        //         $f .= "users.".$k."=".$v. " AND ";  
        //     }
        //     $f = substr($f,0,-5);
        //     $filter = "(".$f.")";
        // }
        
        
        
        if(!empty($searchby)) {
            $searchby = array_flip($searchby);
            foreach($searchby as $key => &$val) { $val = "users." . $val; }
            $searchby = array_flip($searchby);
            
            $this->db->where($searchby);        
        }

        if (!empty($sWhere)) {
            $this->db->where($sWhere);
        }
        
        if (!empty($searchWhere)) {
            $this->db->where($searchWhere);
        }
        
        if($alpha != '') {
            $this->db->where("users.first_name LIKE '".$alpha."%'");
        }

        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {
            if ($userData['role'] == "SUB_ADMIN") {
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);
            }
            if ($userData['role'] == "LOCAL_ADMIN") {
                $this->db->where('users.local_community_id', $userData['local_community_id']);
            }
            $this->db->where('users.id != ', $userData['id']);
        }

        $this->db->where('users.head_id', '0');
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN")');

        // $this->db->group_by('shortId');
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        if (!empty($orderBy) && !empty($orderByVal)) {
            $this->db->order_by($orderBy, $orderByVal);
        }

        $posts = $this->db->get()->row_array();

        //echo $this->db->last_query();exit;

        return $posts['counts'];
    }


    function user_validate($userName, $password) {

        $this->db->select('*, (select name from local_community where id = users.local_community_id) as local_community_name, (select name from sub_community where id = users.sub_community_id) as sub_community_name, (select name from sub_casts where id = users.sub_cast_id) as last_name');
        $this->db->from('users');
        $this->db->where('mobile', $userName);
        $this->db->where('password', sha1($password));
        $this->db->where("status",1);

        $query = $this->db->get()->result_array();

        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }
    
    function validate($userName, $password) {
        $this->db->where('mobile', $userName);
        $this->db->where('password', sha1($password));
        $this->db->where("role IN ('SUB_ADMIN','LOCAL_ADMIN','SUPERADMIN')");

        $query = $this->db->get('users')->result_array();

        if (!empty($query)) {
            //echo "True";
            return $query;
        } else {
            //echo "Flase";
            return false;
        }
    }

    function getRows() {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->order_by('u.id', 'DESC');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function findUserByMobile($mobile, $head = 1, $id = "") {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.mobile', $mobile);
        $this->db->where('u.role != "SUPERADMIN"');
        if ($head == 1) {
            $this->db->where('u.head_id', '0');
        }
        if ($id != "") {
            $this->db->where('u.id != ', $id);
        }
        $query = $this->db->get();
        return $query->row_array();
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


    function loginByMobile($mobile) {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.mobile', $mobile);
        $query = $this->db->get();
        return $query->row_array();
    }
    
     function loginWithEmail($email,$password) {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('email_address', $email)->where('password',sha1($password));
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
        $this->db->where('mobile', $mobile)->where('password',sha1($password));
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
    
    
    
   
    function getMembers($head_id, $admin = 0) {
        $this->db->select("u.*,
            cities.city,
            states.state,
            sc.name as sub_community,lc.name as local_community, 
            (select name from sub_casts where id = u.sub_cast_id) as last_name,  
            r.name as relation,
            d.name as designation,
            n.native,
            m.name as mossad,
            a.activity as current_activity,
            g.gotra,
            bc.name as business_category,
            bsc.name as business_sub_category,
            e.name as education,
            o.occupation
        ");
        $this->db->from('users u');
        $this->db->join('relations r', 'r.id = u.relation_id', 'left');
        
        $this->db->join('cities', 'cities.id = u.city_id', 'left');
        $this->db->join('states', 'states.id = u.state_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = u.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = u.local_community_id', 'left');
        $this->db->join('designations d', 'u.designation_id = d.id', 'left');
        $this->db->join('native n', 'u.native_place_id = n.id', 'left');
        $this->db->join('mosaads m', 'u.mosaad_id = m.id', 'left');
        $this->db->join('current_activity a', 'u.current_activity_id = a.id', 'left');
        $this->db->join('gotra g', 'u.gotra_id = g.id', 'left');
        $this->db->join('business_categories bc', 'u.business_category_id = bc.id', 'left');
        $this->db->join('business_sub_categories bsc', 'u.business_sub_category_id = bsc.id', 'left');
        $this->db->join('educations e', 'u.education_id = e.id', 'left');
        $this->db->join('occupation o', 'u.occupation_id = o.id', 'left');
        
        $this->db->where('u.head_id', $head_id);
        if ($admin != 1) {
            $this->db->or_where('u.id', $head_id);
        }
        $this->db->order_by('u.id','ASC');
       
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    
    function getMembers_count($head_id, $admin = 0) {	
        $this->db->select("u.*");	
        $this->db->from('users u');	
        	
        $this->db->where('u.head_id', $head_id);	
        $this->db->where('u.is_expired = "0"');	
        if ($admin != 1) {	
            $this->db->or_where('u.id', $head_id);	
        }	
        $this->db->order_by('u.id','ASC');	
       	
        $query = $this->db->get();	
        return $query->result_array();	
    }

    function getMembersCount($head_id, $admin = 0) {
        $this->db->select("count(u.id) as counts");
        $this->db->from('users u');
        
        $this->db->where('u.head_id', $head_id);
        if ($admin != 1) {
            $this->db->or_where('u.id', $head_id);
        }
        $this->db->order_by('u.id','ASC');
       
        $query = $this->db->get();
        $data = $query->row_array();
        return $data['counts'];
    }

    function getUsersByRole($role) {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.role IN (' . $role . ')');
        $this->db->order_by('u.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function user_by_user_name($UserName) {
        $this->db->from('users');
        $this->db->where('email', $UserName);
        $query = $this->db->get();
        return $query->result_array();
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

    public function getUsersExportData($state_id='', $city_id='', $sub_community_id='', $local_community_id='') {
        $select = "u.*,
                r.name as relation,
                sca.name as sub_cast,
                bc.name as business_category,
                bsc.name as business_sub_category,
                c.city,
                s.state,
                ca.activity as current_activity,
                e.name as education,
                lc.name as local_community,
                di.name as district,
                n.native as native_place,
                o.occupation,
                sc.name as sub_community";
        $this->db->select($select);
        $this->db->from('users u');
        $this->db->join('relations r', 'r.id = u.relation_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = u.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = u.local_community_id', 'left');
        $this->db->join('sub_casts sca', 'sca.id = u.sub_cast_id', 'left');
        $this->db->join('distincts di', 'di.id = u.distinct_id', 'left');
        $this->db->join('cities c', 'c.id = u.city_id', 'left');
        $this->db->join('states s', 's.id = u.state_id', 'left');
        $this->db->join('native n', 'n.id = u.native_place_id', 'left');
        $this->db->join('current_activity ca', 'ca.id = u.current_activity_id', 'left');
        $this->db->join('business_categories bc', 'bc.id = u.business_category_id', 'left');
        $this->db->join('business_sub_categories bsc', 'bsc.id = u.business_sub_category_id', 'left');
        $this->db->join('educations e', 'e.id = u.education_id', 'left');
        $this->db->join('occupation o', 'o.id = u.occupation_id', 'left');
        $this->db->where('u.head_id', 0);
        $this->db->where('u.role != "SUPERADMIN"');

        if(isset($state_id) && $state_id != ''){
            $this->db->where("u.state_id", $state_id);
        }

        if(isset($city_id) && $city_id != ''){
            $this->db->where("u.city_id", $city_id);
        }

        if(isset($sub_community_id) && $sub_community_id != ''){
            $this->db->where("u.sub_community_id", $sub_community_id);
        }

        if(isset($local_community_id) && $local_community_id != ''){
            $this->db->where("u.local_community_id", $local_community_id);
        }

        $query = $this->db->get();
        $posts = $query->result_array();

        $exportArr = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $d['ID'] = $post['id'];
                $d['Head ID'] = $post['head_id'];
                $d['Relation'] = $post['relation'];
                $d['Sub Community'] = $post['sub_community'];
                $d['Local Community'] = $post['local_community'];
                $d['First Name'] = $post['first_name'];
                $d['Last Name'] = $post['last_name'];
                $d['Sub Cast'] = $post['sub_cast'];
                $d['Gender'] = $post['gender'];
                $d['Email Address'] = $post['email_address'];
                $d['Is Live?'] = ($post['is_expired'] == 1) ? "Yes" : "No";
                $d['Expire Date'] = $post['expire_date'];
                $d['Address'] = $post['address'];
                $d['Local Address'] = $post['local_address'];
                $d['State'] = $post['state'];
                $d['City'] = $post['city'];
                $d['Area'] = $post['area'];
                $d['Pincode'] = $post['pincode'];
                $d['Mobile'] = $post['mobile'];
                $d['Phone'] = $post['phone'];
                $d['Native District'] = $post['district'];
                $d['Native Village'] = $post['native_place'];
                $d['Current Activity'] = $post['current_activity'];
                $d['Education'] = $post['education'];
                $d['Blood Group'] = $post['blood_group'];
                $d['Is Blood Donor'] = ($post['is_donor'] == 1) ? "Yes" : "No";
                $d['Marital Status'] = $post['marital_status'];
                $d['Marriage Date'] = $post['marriage_date'];
                $d['Business Category'] = $post['business_category'];
                $d['Business Sub Category'] = $post['business_sub_category'];
                $d['Company Name'] = $post['company_name'];
                $d['Company Address'] = $post['business_address'];
                $d['Website'] = $post['website'];
                $d['Occupation'] = $post['occupation'];
                $d['Work Details'] = $post['work_details'];
                $d['Interested in Matrimony?'] = $post['matrimony'];
                $d['Birth Date'] = $post['birth_date'];
                $d['Birth Time'] = $post['birth_time'];
                $d['Birth Place'] = $post['birth_place'];
                $d['Weight'] = $post['weight'];
                $d['Height'] = $post['height'];
                $d['Is Spect?'] = ($post['is_spect'] == 1) ? "Yes" : "No";
                $d['About Me'] = $post['about_me'];
                $d['Hobby'] = $post['hobby'];
                $d['Expectation'] = $post['expectation'];
                $d['Social Profile URL'] = $post['facebook_profile'];
                $exportArr[] = $d;
                $members = $this->getMembersForExport($post['id']);
                if (!empty($members)) {
                    foreach ($members as $member) {
                        $exportArr[] = $member;
                    }
                }
            }
        }

        return $exportArr;
    }

    function getMembersForExport($id) {
        $select = "u.*,
                r.name as relation,
                sca.name as sub_cast,
                bc.name as business_category,
                bsc.name as business_sub_category,
                c.city,
                s.state,
                ca.activity as current_activity,
                e.name as education,
                lc.name as local_community,
                di.name as district,
                n.native as native_place,
                o.occupation,
                sc.name as sub_community";
        $this->db->select($select);
        $this->db->from('users u');
        $this->db->join('relations r', 'r.id = u.relation_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = u.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = u.local_community_id', 'left');
        $this->db->join('sub_casts sca', 'sca.id = u.sub_cast_id', 'left');
        $this->db->join('distincts di', 'di.id = u.distinct_id', 'left');
        $this->db->join('cities c', 'c.id = u.city_id', 'left');
        $this->db->join('states s', 's.id = u.state_id', 'left');
        $this->db->join('native n', 'n.id = u.native_place_id', 'left');
        $this->db->join('current_activity ca', 'ca.id = u.current_activity_id', 'left');
        $this->db->join('business_categories bc', 'bc.id = u.business_category_id', 'left');
        $this->db->join('business_sub_categories bsc', 'bsc.id = u.business_sub_category_id', 'left');
        $this->db->join('educations e', 'e.id = u.education_id', 'left');
        $this->db->join('occupation o', 'o.id = u.occupation_id', 'left');
        $this->db->where('u.head_id', $id);
        $query = $this->db->get();
        $posts = $query->result_array();

        $exportMembers = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $d['ID'] = $post['id'];
                $d['Head ID'] = $post['head_id'];
                $d['Relation'] = $post['relation'];
                $d['Sub Community'] = $post['sub_community'];
                $d['Local Community'] = $post['local_community'];
                $d['First Name'] = $post['first_name'];
                $d['Last Name'] = $post['last_name'];
                $d['Sub Cast'] = $post['sub_cast'];
                $d['Gender'] = $post['gender'];
                $d['Email Address'] = $post['email_address'];
                $d['Is Live?'] = ($post['is_expired'] == 1) ? "Yes" : "No";
                $d['Expire Date'] = $post['expire_date'];
                $d['Address'] = $post['address'];
                $d['Local Address'] = $post['local_address'];
                $d['State'] = $post['state'];
                $d['City'] = $post['city'];
                $d['Area'] = $post['area'];
                $d['Pincode'] = $post['pincode'];
                $d['Mobile'] = $post['mobile'];
                $d['Phone'] = $post['phone'];
                $d['Native District'] = $post['district'];
                $d['Native Village'] = $post['native_place'];
                $d['Current Activity'] = $post['current_activity'];
                $d['Education'] = $post['education'];
                $d['Blood Group'] = $post['blood_group'];
                $d['Is Blood Donor'] = ($post['is_donor'] == 1) ? $post['is_donor'] : "";
                $d['Marital Status'] = $post['marital_status'];
                $d['Marriage Date'] = $post['marriage_date'];
                $d['Business Category'] = $post['business_category'];
                $d['Business Sub Category'] = $post['business_sub_category'];
                $d['Company Name'] = $post['company_name'];
                $d['Company Address'] = $post['business_address'];
                $d['Website'] = $post['website'];
                $d['Occupation'] = $post['occupation'];
                $d['Work Details'] = $post['work_details'];
                $d['Interested in Matrimony?'] = $post['matrimony'];
                $d['Birth Date'] = $post['birth_date'];
                $d['Birth Time'] = $post['birth_time'];
                $d['Birth Place'] = $post['birth_place'];
                $d['Weight'] = $post['weight'];
                $d['Height'] = $post['height'];
                $d['Is Spect?'] = ($post['is_spect'] == 1) ? "Yes" : "No";
                $d['About Me'] = $post['about_me'];
                $d['Hobby'] = $post['hobby'];
                $d['Expectation'] = $post['expectation'];
                $d['Social Profile URL'] = $post['facebook_profile'];
                $exportMembers[] = $d;
            }
        }

        return $exportMembers;
    }

    function send_emailsimple($subject, $to, $from, $body) {

        $this->load->library('email');
        $this->email->clear();

        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        //$this->email->set_newline("\r\n");
        $this->email->from($from, 'one-99'); // change it to yours
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

    function get_user($userID) {

        $this->db->select('u.*, (select name from sub_casts where id = u.sub_cast_id) as last_name');
        $this->db->from('users u');
        $this->db->where('u.id', $userID);
        $query = $this->db->get();
        return $query->row_array();
    }

    function add_user($params) {
        $this->db->insert('users', $params);
        return $this->db->insert_id();
    }

    function updateUser($userID, $params) {
        $this->db->where('id', $userID);
        return $this->db->update('users', $params);
    }

    function delete_user($userID) {
        $user = $this->session->userdata('frontLogin');
        $this->db->delete('users', array('id' => $userID));
        if (!empty($user) && $user['id'] == $userID) {
            $this->db->delete('users', array('head_id' => $userID));
        }
    }

    function delete_multiple($ids) {
        foreach ($ids as $id) {
            $user = $this->session->userdata('frontLogin');
            $this->db->delete('users', array('id' => $id));
            $this->db->delete('users', array('head_id' => $id));
        }
    }

    public function checkOldPass($oldpassword, $adminID) {
        $this->db->where('password', sha1($oldpassword));
        $this->db->where('id', $adminID);
        $query = $this->db->count_all_results('users');

        if ($query > 0)
            return 1;
        else
            return 0;
    }

    function update_user($fieldname, $id, $data, $table) {
        $this->db->where($fieldname, $id);
        $this->db->update($table, $data);
        return 1;
    }

    function get_db_session_data() {
        $query = $this->db->select('user_data')->get('ci_sessions');

        $user = array(); /* array to store the user data we fetch */
        foreach ($query->result() as $row) {
            $udata = unserialize($row->user_data);
            /* put data in array using username as key */
            $user['userName'] = $udata['userName'];
            $user['adminID'] = $udata['adminID'];
            $user['is_logged_in'] = $udata['is_logged_in'];
        }
        return $user;
    }

    function checkEmailExists($email) {
        $row = $this->db->get_where('users', array('email' => $email))->row_array();
        if (!empty($row)) {
            return $row;
        }
        return FALSE;
    }

    function convertDate($date) {
        $dateArr = explode('/', $date);

        return $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];
    }
    
    function getCommitteeMembers($id) {
        
       $sql = "SELECT committee_member.*, users.first_name, users.last_name, users.email_address, users.mobile, (SELECT name FROM local_community WHERE local_community.id = users.local_community_id) as community_name, (SELECT name FROM committees WHERE committees.id = committee_member.committee_id) as committee_name, (SELECT name FROM designations WHERE designations.id = committee_member.designation_id) as designation FROM committee_member JOIN users ON users.id = committee_member.user_id WHERE committee_member.user_id IN (SELECT id FROM users WHERE id = $id OR head_id = $id)";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getHeadMembers($id) {
        $sql = "SELECT users.id, CONCAT(users.first_name,' ',users.last_name) as user_name FROM  users WHERE users.id = $id OR head_id = $id";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_record($id) {

        $sql = "SELECT id, user_id, committee_id,designation_id, DATE_FORMAT(start_date, '%d/%m/%Y') as start_date, committee_id, DATE_FORMAT(end_date, '%d/%m/%Y') as end_date FROM committee_member WHERE id = $id";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function add_committee_member($params) {
        $this->db->insert('committee_member', $params);
        return $this->db->insert_id();
    }

    function update_committee_member($id, $params) {

        $this->db->where('id', $id);

        return $this->db->update('committee_member', $params);
    }

    function get_head_counts() {
        // echo "string";die;
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('head_id', 0);
        // $this->db->where('role != "SUPERADMIN"');
        $data = $this->db->get()->result_array();
        // print_r($data);die;
        return $data;
    }

    function delete_committee_member($id) {
        $this->db->delete('committee_member', array('id' => $id));
    }

    function delete_multiple_committee_member($ids) {
        $this->db->where_in('id', $ids);
        $this->db->delete('committee_member');
    }

    function get_committee_member($start = "", $length = "", $orderBy = "", $orderByVal = "", $searchby = []) {
        $userData = $this->session->userdata('backEndLogin');
        //define columns for searching
     
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state");

        $this->db->select("users.*,
        (select name from sub_casts where id = users.sub_cast_id) as last_name,
        (select count(*) from users u1 where u1.head_id = users.id and u1.is_expired = '0') as member_count, 
        cities.city,
        cm.designation_id as designation_id,
        cm.committee_id as committee_id,
        states.state,
        sc.name as sub_community,
        lc.name as local_community,
        r.name as relation,
        d.name as designation,
        n.native,
        m.name as mossad,
        a.activity as current_activity,
        g.gotra,
        h.first_name as head_name,
        h.sub_cast_id as head_sub_cast_id,
        bc.name as business_category,
        bsc.name as business_sub_category,
        e.name as education,
        o.occupation");
        $this->db->from($this->table);
        $this->db->join('cities', 'cities.id = users.city_id', 'left');
        $this->db->join('states', 'states.id = users.state_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = users.sub_community_id', 'left');
        $this->db->join('relations r', 'r.id = users.relation_id', 'left');
        $this->db->join('local_community lc', 'lc.id = users.local_community_id', 'left');
        $this->db->join('designations d', 'users.designation_id = d.id', 'left');
        $this->db->join('native n', 'users.native_place_id = n.id', 'left');
        $this->db->join('mosaads m', 'users.mosaad_id = m.id', 'left');
        $this->db->join('current_activity a', 'users.current_activity_id = a.id', 'left');
        $this->db->join('gotra g', 'users.gotra_id = g.id', 'left');
        $this->db->join('business_categories bc', 'users.business_category_id = bc.id', 'left');
        $this->db->join('business_sub_categories bsc', 'users.business_sub_category_id = bsc.id', 'left');
        $this->db->join('educations e', 'users.education_id = e.id', 'left');
        $this->db->join('occupation o', 'users.occupation_id = o.id', 'left');
        $this->db->join('users h', 'users.head_id = h.id', 'left');
        $this->db->join('committee_member cm', 'users.id = cm.user_id', 'left');
        $this->db->where('users.status!=0');
       
        // Addtitional Filtering
        $sWhere = "";
        if ($searchParamVal != "") {
            $sWhere = "(";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
       
        $exclude = array("member_name","head_name","address","local_address","area","min_age","max_age","birth_date","marriage_date","business_address","created_dt","updated_dt","member_code","id");

       
        if(isset($searchby['str_search'])) {
            $this->db->where("(users.first_name LIKE '%".$searchby['str_search']."%')");
        }
        
        if(isset($searchby['local_community_id'])) {
            $this->db->where("(users.local_community_id = '".$searchby['local_community_id']."')");
        }
        
        if(isset($searchby['sub_community_id'])) {
            $this->db->where("(users.sub_community_id = '".$searchby['sub_community_id']."')");
        }
        
         if(isset($searchby['committee_id'])) {
            $this->db->where("(cm.committee_id = '".$searchby['committee_id']."')");
        }
        
        if(isset($searchby['designation_id'])) {
            $this->db->where("(cm.designation_id = '".$searchby['designation_id']."')");
        }

        if(isset($searchby['start_date'])) {
            $this->db->where("DATE_FORMAT(CAST(cm.start_date as DATE), '%Y-%m')>='".$searchby['start_date']."'");
        }

        if(isset($searchby['end_date'])) {
            $this->db->where("DATE_FORMAT(CAST(cm.end_date as DATE), '%Y-%m')<='".$searchby['end_date']."'");
        }
        
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        if (!empty($orderBy) && !empty($orderByVal)) {
            $this->db->order_by($orderBy, $orderByVal);
        }

        $posts = $this->db->get()->result_array();

        //echo $this->db->last_query();exit;

        if (count($posts)) {
            return $posts;
        } else {
            return array();
        }
    }

}
