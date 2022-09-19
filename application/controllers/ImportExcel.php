<?php
error_reporting(E_ALL);
require( APPPATH . '/libraries/excel/PHPExcel.php' );
//require(APPPATH . '/libraries/REST_Controller.php');

class ImportExcel extends CI_Controller
{
    function index()
    {

    	$inputFileName 				= FCPATH.'uploads\import.xlsx';
    	
    	$inputFileName 				= str_replace( "\\", "/", $inputFileName );
    	
    	//echo $inputFileName;die;

    	if( !file_exists( $inputFileName ) ){
    		$inputFileName 				= FCPATH.'uploads\import.xls';
    	}

    	$inputFileName 				= str_replace( "\\", "/", $inputFileName );
    	//die();

    	//  Read your Excel workbook
		try {
		    $inputFileType 					= PHPExcel_IOFactory::identify( $inputFileName );
		    $objReader 						= PHPExcel_IOFactory::createReader( $inputFileType );
		    $objPHPExcel 					= $objReader->load( $inputFileName );
		} catch( Exception $e ) {
		    die( 'Error loading file "'. pathinfo( $inputFileName, PATHINFO_BASENAME ).'": '.$e->getMessage() );
		}

		//  Get worksheet dimensions
		$sheet 								= $objPHPExcel->getSheet(0); 
		$highestRow 						= $sheet->getHighestRow(); 
		$highestColumn 						= $sheet->getHighestColumn();

		$colData 							= $sheet->rangeToArray( 'A1:' . $highestColumn . '1', NULL, TRUE, FALSE );
	    $colArr 							= $colData[0];

		//  Loop through each row of the worksheet in turn
		for ( $row = 2; $row <= $highestRow; $row++ ){ 
		//for ( $row = 2; $row <= 10; $row++ ){ 
		    
		    $rowData 					= $sheet->rangeToArray( 'A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE );
	    	$rowArr 					= $rowData[0];

	    	if( empty( $rowArr[0] ) ){
	    		continue;
	    	}
	    	

	    	/*echo PHPExcel_Style_NumberFormat::toFormattedString($rowArr[3], "yyyy-mm-dd");
	    	echo "<br/>";
	    	echo PHPExcel_Style_NumberFormat::toFormattedString($rowArr[4], "H:i:s");*/


	    	$recors_arr 				= [];

	    	$recors_arr 				= array_combine( $colArr, $rowArr );

	    	$id 						= $recors_arr['id'];
	    	unset( $recors_arr['id'] );
	    	unset( $recors_arr[''] );

	    	if( isset( $recors_arr['birth_date'] ) ){
	    		$birth_date = PHPExcel_Style_NumberFormat::toFormattedString( $recors_arr['birth_date'], "yyyy-mm-dd" );
	    		unset( $recors_arr['birth_date'] );
	    		$recors_arr['birth_date'] = $birth_date;
	    	}

	    	if( isset( $recors_arr['birth_time'] ) ){
	    		$birth_time = PHPExcel_Style_NumberFormat::toFormattedString( $recors_arr['birth_time'], "H:i:s" );
	    		unset( $recors_arr['birth_time'] );
	    		$recors_arr['birth_time'] = $birth_time;
	    	}

	    	if( isset( $recors_arr['marriage_date'] ) ){
	    		$marriage_date = PHPExcel_Style_NumberFormat::toFormattedString( $recors_arr['marriage_date'], "yyyy-mm-dd" );
	    		unset( $recors_arr['marriage_date'] );
	    		$recors_arr['marriage_date'] = $marriage_date;
	    	}

	    	if( isset( $recors_arr['expire_date'] ) ){
	    		$expire_date = PHPExcel_Style_NumberFormat::toFormattedString( $recors_arr['expire_date'], "yyyy-mm-dd" );
	    		unset( $recors_arr['expire_date'] );
	    		$recors_arr['expire_date'] = $expire_date;
	    	}

	    	if( isset( $recors_arr['last_login'] ) ){
	    		$last_login = PHPExcel_Style_NumberFormat::toFormattedString( $recors_arr['last_login'], "yyyy-mm-dd H:i:s" );
	    		unset( $recors_arr['last_login'] );
	    		$recors_arr['last_login'] = $last_login;
	    	}

	    	if( isset( $recors_arr['created_dt'] ) ){
	    		$created_dt = PHPExcel_Style_NumberFormat::toFormattedString( $recors_arr['created_dt'], "yyyy-mm-dd H:i:s" );
	    		unset( $recors_arr['created_dt'] );
	    		$recors_arr['created_dt'] = $created_dt;
	    	}

	    	if( isset( $recors_arr['updated_dt'] ) ){
	    		$updated_dt = PHPExcel_Style_NumberFormat::toFormattedString( $recors_arr['updated_dt'], "yyyy-mm-dd H:i:s" );
	    		unset( $recors_arr['updated_dt'] );
	    		$recors_arr['updated_dt'] = $updated_dt;
	    	}

	    	if( isset( $recors_arr['login_status'] ) ){
	    		$login_status = $recors_arr['login_status'];
	    		unset( $recors_arr['login_status'] );
	    		$recors_arr['login_status'] = strval($login_status);
	    	}

	    	/*print_r($recors_arr);
	    	die();*/

			$is_there 					= $this->db->get_where('users', array('id' => $id))->row_array();
			if( $is_there && !empty( $is_there ) ){
				
				$this->db->where('id', $id);
        		$this->db->update('users', $recors_arr);

        		echo "#ID => " . $rowArr[0] . " = Update Record<br/>";

			} else {
				$this->db->insert('users', $recors_arr);

				echo "#ID => " . $this->db->insert_id() . " = Insert Record<br/>";
			}

			/*insert into `wp_postmeta` SET `meta_key` = "test", `meta_value` = "test"
			update `wp_postmeta` SET `meta_key` = "test", `meta_value` = "test" WHERE meta_id = 7*/

	    	/*$recors_arr = [];
	    	//$recors_arr['id'] = $rowArr[0];
			$recors_arr['role'] = $rowArr[1];
			$recors_arr['head_id'] = $rowArr[2];
			$recors_arr['member_code'] = $rowArr[3];
			$recors_arr['email_address'] = $rowArr[4];
			$recors_arr['mobile'] = $rowArr[5];
			$recors_arr['plain_password'] = $rowArr[6];
			$recors_arr['password'] = $rowArr[7];
			$recors_arr['relation_id'] = $rowArr[8];
			$recors_arr['sub_community_id'] = $rowArr[9];
			$recors_arr['local_community_id'] = $rowArr[10];
			$recors_arr['committee_id'] = $rowArr[11];
			$recors_arr['designation_id'] = $rowArr[12];
			$recors_arr['first_name'] = $rowArr[13];
			$recors_arr['last_name'] = $rowArr[14];
			$recors_arr['father_name'] = $rowArr[15];
			$recors_arr['mother_name'] = $rowArr[16];
			$recors_arr['sub_cast_id'] = $rowArr[17];
			$recors_arr['status'] = $rowArr[18];
			$recors_arr['gender'] = $rowArr[19];
			$recors_arr['address'] = $rowArr[20];
			$recors_arr['local_address'] = $rowArr[21];
			$recors_arr['city_id'] = $rowArr[22];
			$recors_arr['state_id'] = $rowArr[23];
			$recors_arr['area'] = $rowArr[24];
			$recors_arr['pincode'] = $rowArr[25];
			$recors_arr['phone'] = $rowArr[26];
			$recors_arr['matrimony'] = $rowArr[27];
			$recors_arr['birth_date'] = $rowArr[28];
			$recors_arr['birth_time'] = $rowArr[29];
			$recors_arr['birth_place'] = $rowArr[30];
			$recors_arr['distinct_id'] = $rowArr[31];
			$recors_arr['native_place_id'] = $rowArr[32];
			$recors_arr['blood_group'] = $rowArr[33];
			$recors_arr['about_me'] = $rowArr[34];
			$recors_arr['weight'] = $rowArr[35];
			$recors_arr['height'] = $rowArr[36];
			$recors_arr['is_spect'] = $rowArr[37];
			$recors_arr['is_mangal'] = $rowArr[38];
			$recors_arr['is_shani'] = $rowArr[39];
			$recors_arr['hobby'] = $rowArr[40];
			$recors_arr['facebook_profile'] = $rowArr[41];
			$recors_arr['expectation'] = $rowArr[42];
			$recors_arr['mosaad_id'] = $rowArr[43];
			$recors_arr['current_activity_id'] = $rowArr[44];
			$recors_arr['marital_status'] = $rowArr[45];
			$recors_arr['marriage_date'] = $rowArr[46];
			$recors_arr['gotra_id'] = $rowArr[47];
			$recors_arr['profile_pic'] = $rowArr[48];
			$recors_arr['region'] = $rowArr[49];
			$recors_arr['is_rented'] = $rowArr[50];
			$recors_arr['is_expired'] = $rowArr[51];
			$recors_arr['expire_date'] = $rowArr[52];
			$recors_arr['is_donor'] = $rowArr[53];
			$recors_arr['business_category_id'] = $rowArr[54];
			$recors_arr['business_sub_category_id'] = $rowArr[55];
			$recors_arr['work_details'] = $rowArr[56];
			$recors_arr['company_name'] = $rowArr[57];
			$recors_arr['business_address'] = $rowArr[58];
			$recors_arr['business_logo'] = $rowArr[59];
			$recors_arr['website'] = $rowArr[60];
			$recors_arr['education_id'] = $rowArr[61];
			$recors_arr['occupation_id'] = $rowArr[62];
			$recors_arr['user_lat'] = $rowArr[63];
			$recors_arr['user_lng'] = $rowArr[64];
			$recors_arr['home_lat'] = $rowArr[65];
			$recors_arr['home_lng'] = $rowArr[66];
			$recors_arr['office_lat'] = $rowArr[67];
			$recors_arr['office_lng'] = $rowArr[68];
			$recors_arr['deleted'] = $rowArr[69];
			$recors_arr['is_location_enable'] = $rowArr[70];
			$recors_arr['sharing_id'] = $rowArr[71];
			$recors_arr['login_status'] = $rowArr[72];
			$recors_arr['last_login'] = $rowArr[73];
			$recors_arr['profile_password'] = $rowArr[74];
			$recors_arr['profile_percent'] = $rowArr[75];
			$recors_arr['updated_time'] = $rowArr[76];
			$recors_arr['created_dt'] = $rowArr[77];
			$recors_arr['created_by'] = $rowArr[78];
			$recors_arr['updated_dt'] = $rowArr[79];
			$recors_arr['updated_by'] = $rowArr[80];*/

		}

    }
}