<?php
    // DISPLAY ERRORS...
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

		require_once("phrets.php");
		require_once("configuration.php");

		$config = new JConfig();

		$prefix = $config->dbprefix;
		//*********************
		var_dump($argv);
		parse_str($argv[1], $params);
		$query2=$params['query2'];
		parse_str($argv[2], $params);
		$offset=$params['offset'];
		parse_str($argv[3], $params);
		$limit=$params['limit'];

		echo "query2=";echo $query2; // 
		echo " offset=";echo $offset; // 
		echo " limit=";echo $limit; // 
		//*********************

    $rets = new phRETS;
    $conn = mysqli_connect($config->host, $config->user, $config->password, $config->db);
    if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

    //setting was using port 80 before
    //$rets_login_url = "http://retsgw.flexmls.com:6103/rets2_3/Login";
    $rets_login_url = "http://retsgw.flexmls.com/rets2_3/Login";
    //$rets_login_url = "http://retsgw.flexmls.com:80/rets2_3/Login";

    $rets_username = "az.rets.usmr01b";
    $rets_password = "puces-uronic23";

    $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
    if (!$connect) {
        print_r($rets->Error());
    }

    date_default_timezone_set('US/Arizona');
    $date = date('Y-m-d')."+";
    //set default query ***Make sure to change the base year to 3 years or so
    $query = "(LIST_87=".$date.")"; //default
    //$query = "(LIST_87=2016-01-01+)";
    $date2 = date('h:i:s');
echo "AZ ALL Properties starting at:"; echo $date2;
    
    //If executing from the browser
    if(!empty($_REQUEST['query'])){
        $query = $_REQUEST['query'];
    }

                if(!empty($query2)){
                        //$query2=($_REQUEST['query2']);
                        if ($query2 == "1") {
                           $query = "(LIST_87=2014-01-01+)";
                        }elseif  ($query2 == "2") {
                           $query = "(LIST_15=Active)";
                        }elseif  ($query2 == "3") {
                           $query = "(ListingStatus=ACT)";
                        }elseif  ($query2 == "4") {
                           $query = "(ListingStatus=BMP)";
                        }elseif  ($query2 == "5") {
                           $query = "(ListingStatus=SSP)";
                        }elseif  ($query2 == "6") {
                           $query = "(ListingStatus=PEN)";
                        }elseif  ($query2 == "7") {
                           $query = "(ListingStatus=POP)";
                        }else {
                           //do nothing
                           echo "<br>Query2 something else query2:";echo $query2;
                        }
                 echo "<br>Query using query2";
                }
    		echo "Query:"; echo $query;


//set default offset *don't need first one ------ * THIS IS THE ONLY DIFFERENCE
if(empty($offset)){
	//$offset = "0"; //default
}
echo " <br>Offset=";echo $offset;

//set limit to 1000
if(empty($limit)){
	$limit = "1000"; //default
}
echo " Limit:";echo $limit;




    //$offset = 0;  ------This reset the records to first record and gives you offset error...after first time it never pulls anything

    $types = $rets->GetMetadataTypes();
    foreach ($types as $type) {
        //echo "<br>Type: ";print_r($type);
        if($type['Resource'] == "Property"){
            foreach ($type['Data'] as $data) {
                    //echo "<br>Data: "; print_r($data);
                    $fields = $rets->GetMetadataTable($type['Resource'], $data['ClassName']);
//echo "<br>Fields:";print_r($fields);
                    $results = $rets->Search($type['Resource'], $data['ClassName'] ,$query, array("Limit" => $limit, "Offset"=> $offset));

//                    echo "<Types >"; print_r($type);
//                    echo "<Fields >"; print_r($fields);
//                    echo "<Results >"; print_r($results);
//echo "<br>..2nd foreach";

                    $keys = array();
                        foreach ($fields as $key) {
                            $keys[] = $key['SystemName'];
                        }
//echo "<br>..after keys for each";
                    $_iproperty = array();
                    $_iproperty_propmid = array();
                    $_iproperty_agentmid = array();
                    $_iproperty_images = array();
                    $_iproperty_citiesmid = array();

                    //$max_id = 1;
                    $sql = "SELECT MAX(id) AS max_id FROM ".$prefix."iproperty";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        if(!empty($row['max_id']))
                            $max_id = $row['max_id'];
                    }

//echo "<br>...after select maxid...";

                    $_iproperty['columns'] = "(for_picture,stype, country, locstate, city, baths, comp_for_sale, beds, yearbuilt, longitude, latitude, postcode, street2, street, street_num ,cross_street, blog_num, street_compass, street_suffix, stype_freq, price, price2,low_price,access, from_rets, description, mls_id, int_list_id,agentid, property_type, province, county, total_units, tax, tax_year,tax_municipality, sqft, sqft_range, lotsize, heat, cool, garage_type, garage_size, pool, zoning, roof, school_district, elem_school_dist, created_by, created, modified, publish_up,dwelling_type,map_code,buyer_commission,sub_agents,other_commission,public_remark,assessor_number,marketing_name,builder_name,auction,planned_comm_name,subdivision,elementary_school,middle_school,high_school,comp_to_buyer_broker,comp_to_subagent,assessor_book,assessor_map,assessor_parcel,legal_township,legal_range,legal_section,legal_lot_num,legal_cnty_rcrd_bk_pg,association_hoa_fee_yes,association_hoa_fee,association_hoa_fee_name,association_hoa2_fee_yes,association_hoa2_fee,association_hoa2_fee_name,association_hoa3_fee_yes,association_hoa3_fee,association_hoa3_fee_name,basement_yes,separate_den_office,kitchen_partial_full,parking_space,carpot_space,total_cover_space,agent_phone_num,additional_bedroom,building_style,exterior_features,interior_features,fireplace,kitchen_features,master_bedroom,master_bathroom,special_listing_cond,technology,listing_member_shortid,listing_office_shortid,listing_member_name,listing_office_name,unbranded_virtual_tour,picture_count,interior_levels,exterior_story,vacation_rental_yes,types,rental_hoa_yes,rental_hoa_name,rental_hoa_fee,rental_property_sleeps,rental_furnish,rental_floor_num,lots_in_listing,apx_total_acres,apx_total_acres_name,apx_sqft_name,apx_sqft,elevation,existing_land_use,land_features,parcel_size,potential_use,use_restrictions,zoned_presently,construction,current_use,environmental,stories,tenant_pays,sale_includes)";

                    $count = 0;
      		    //I added the following line--kq
                    $propId = $max_id;
                    $my_prop_id = 0;

                if($data['ClassName'] == 'A'){
                    foreach ($results as $record) {
//echo "<br> Record: ";echo $record;

                        $sql = "SELECT id AS my_prop_id, mls_id FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105']." AND from_rets=1 AND locstate=3921";
                        $check_result = mysqli_query($conn, $sql);

//echo " --sql:"; echo $sql;

                        if (mysqli_num_rows($check_result) > 0) {

//echo "its if...";

                           if(!empty($row['my_prop_id'])) {
                               $my_prop_id = $row['my_prop_id'];
                               echo "<my_prop_id>"; print_r($my_prop_id);
                            }

                            if ($record['LIST_15'] != 'Active') {
                               echo "LISTING  A NOT ACTIVE";
                                //state, approved, access values are 0
                               //$update_status = "DELETE FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105'];

                               /*$update_status = "UPDATE ".$prefix."iproperty SET state=0, approved=0, access=0 WHERE mls_id=".$record['LIST_105'];
                               $update_status_exe = mysqli_query($conn, $update_status);*/

echo "<br>not active--deleting: ".$record['LIST_105']." ";
                               $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                  echo "This ".$record['LIST_105']." MLS Number Property is Not Active. So Deleting this Property from our Database";
                                  }

                               echo "Deleting A Images";
                               $update_status = "DELETE FROM ".$prefix."iproperty_images WHERE propid=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                 echo "This my_prop_id=".$my_prop_id." Images Deleted from Database-az";
                               }


                            } /*else {
                               echo"UPDATE c4aqr_iproperty SET
                                                        stype = 1,
                                                        country = '".str_replace("'", "\'", str_replace("''", '"', $country))."',
                                                        locstate = '".str_replace("'", "\'", str_replace("''", '"', $locstate))."',
                                                        city = '".str_replace("'", "\'", str_replace("''", '"', $city))."',
                                                        baths = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_67']))."',
                                                        comp_for_sale = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_52']))."',
                                                        beds = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_66']))."',
                                                        yearbuilt = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_53']))."',
                                                        longitude = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_47']))."',
                                                        latitude = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_46']))."',
                                                        postcode = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_43']))."',
                                                        street2 = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                        street = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_34']))."',
                                                        street_num = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_31']))."',
                                                        cross_street = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_130'])).",
                                                        blog_num = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_32']))."',
                                                        street_compass = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_33']))."',
                                                        street_suffix = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                        stype_freq = 'SqFt',
                                                        price = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_25']))."',
                                                        price2 = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_22']))."',
                                                        low_price = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_24']))."',
                                                        access = 1,
                                                        from_rets = 1,
                                                        description = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134222636466000000']))."',
                                                        mls_id = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_105']))."',
                                                        int_list_id = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_1']))."',
                                                        agentid = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_5']))."',
                                                        property_type = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_8']))."',
                                                        province = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_113']))."',
                                                        county = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_41']))."',
                                                        total_units = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_35']))."',
                                                        tax = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_75']))."',
                                                        tax_year = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_76']))."',
                                                        tax_municipality = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_92']))."',
                                                        sqft = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_48']))."',
                                                        sqft_range = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_114']))."',
                                                        lotsize = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_56']))."',
                                                        heat = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134031105159000000']))."',
                                                        cool = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134018021136000000']))."',
                                                        garage_type = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_143']))."',
                                                        garage_size = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                        pool = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_73']))."',
                                                        zoning = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_74']))."',
                                                        roof = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134004605295000000']))."',
                                                        school_district = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_112']))."',
                                                        elem_school_dist = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_111']))."',
                                                        created_by = 785,
                                                        created = '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                        modified = '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                        publish_up = '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                        dwelling_type = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_9']))."',
                                                        map_code = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_29']))."',
                                                        buyer_commission = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_58']))."',
                                                        sub_agents = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_59']))."',
                                                        other_commission = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_60']))."',
                                                        public_remark = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_78']))."',
                                                        assessor_number = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_80']))."',
                                                        marketing_name = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_85']))."',
                                                        builder_name = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_86']))."',
                                                        auction = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_93']))."',
                                                        planned_comm_name = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_101']))."',
                                                        elementary_school = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_108']))."',
                                                        middle_school = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_109']))."',
                                                        high_school = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_110']))."',
                                                        comp_to_buyer_broker = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_119']))."',
                                                        comp_to_subagent = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_118']))."',
                                                        assessor_book = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_124']))."',
                                                        assessor_map = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_125']))."',
                                                        assessor_parcel = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_126']))."',
                                                        legal_township = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203318529362000000']))."',
                                                        legal_range = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203326455292000000']))."',
                                                        legal_section = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203335955409000000']))."',
                                                        legal_lot_num = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203731663536000000']))."',
                                                        legal_cnty_rcrd_bk_pg = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203441259293000000']))."',
                                                        association_hoa_fee_yes = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205202933704000000']))."',
                                                        association_hoa_fee = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205229919954000000']))."',
                                                        association_hoa_fee_name = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205338315648000000']))."',
                                                        association_hoa2_fee_yes = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225028756930000000']))."',
                                                        association_hoa2_fee = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225501566841000000']))."',
                                                        association_hoa2_fee_name = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412230614024897000000']))."',
                                                        association_hoa3_fee_yes = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203556732237000000']))."',
                                                        association_hoa3_fee = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203729521799000000']))."',
                                                        association_hoa3_fee_name = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203819097748000000']))."',
                                                        basement_yes = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161043032254000000']))."',
                                                        separate_den_office = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161604325096000000']))."',
                                                        kitchen_partial_full = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914185721572556000000']))."',
                                                        parking_space = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                        carpot_space = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163434654648000000']))."',
                                                        total_cover_space = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163837118120000000']))."',
                                                        agent_phone_num = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080404180341443455000000']))."',
                                                        additional_bedroom = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135794000000']))."',
                                                        building_style = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131719000000']))."',
                                                        exterior_features = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131436000000']))."',
                                                        interior_features = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135630000000']))."',
                                                        fireplace = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135759000000']))."',
                                                        kitchen_features = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131789000000']))."',
                                                        master_bedroom = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20121130191428826666000000']))."',
                                                        master_bathroom = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203457617516000000']))."',
                                                        special_listing_cond = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20080207202713312731000000']))."',
                                                        technology = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135662000000']))."',
                                                        listing_member_shortid = '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_shortid']))."',
                                                        listing_office_shortid = '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_shortid']))."',
                                                        listing_member_name = '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_name']))."',
                                                        listing_office_name = '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_name']))."',
                                                        unbranded_virtual_tour = '".str_replace("'", "\'", str_replace("''", '"', $record['UNBRANDEDIDXVIRTUALTOUR']))."',
                                                        picture_count = '".str_replace("'", "\'", str_replace("''", '"', $record['picture_count']))."',
                                                        interior_levels = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_68']))."',
                                                        exterior_story = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_69']))."',
                                                        vacation_rental_yes = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_91']))."',
                                                        types = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_107']))."',
                                                        rental_hoa_yes = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412222540943628000000']))."',
                                                        rental_hoa_name = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116192529063673000000']))."',
                                                        rental_hoa_fee = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127181504522660000000']))."',
                                                        rental_property_sleeps = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130402192514025687000000']))."',
                                                        rental_furnish = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193640412686000000']))."',
                                                        rental_floor_num = '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193617886554000000']))."',
                                                        lots_in_listing = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_49']))."',
                                                        apx_total_acres = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_57']))."',
                                                        apx_total_acres_name = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_72']))."',
                                                        apx_sqft_name = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_95']))."',
                                                        apx_sqft = '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_117']))."',
                                                        elevation = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035540438309000000']))."',
                                                        existing_land_use = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035349289855000000']))."',
                                                        land_features = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035530068525000000']))."',
                                                        parcel_size = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035440502884000000']))."',
                                                        potential_use = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035428139715000000']))."',
                                                        use_restrictions = '".str_replace("'", "\'", str_replace("''", '"', str_replace("'", "", $record['GF20071117040305000417000000'])))."',
                                                        zoned_presently = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035412533196000000']))."',
                                                        construction = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119135700713497000000']))."',
                                                        current_use = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150611106648000000']))."',
                                                        environmental = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150518403713000000']))."',
                                                        stories = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150620828281000000']))."',
                                                        tenant_pays = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172524344039000000']))."',
                                                        sale_includes = '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071121011759800083000000']))."'
                                                        WHERE mls_id=".$mls_id."";

                                                        echo "<br/><br/><br/><br/>";
                            }*/
                        } else {
//echo "...its else...";
                            // Insert values...
                            if ($record['LIST_15'] != 'Active') {
                                continue;
                            }
                            $proamenities = array();
                            array_push($proamenities, 0);
                            for ($i=0; $i < count($keys); $i++) {

//echo "Listing Status list 15: ";echo $record['LIST_15'];
                                    // get city...
                                if($keys[$i] == "LIST_39") {
                                    $city = '';
                                    if(!empty($record[$keys[$i]])) {
                                        $get_city = trim(reset(explode(',',$record[$keys[$i]])));
                                        $sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$get_city."'";
                                        $result = mysqli_query($conn, $sql);

                                        if (mysqli_num_rows($result) <= 0) {
                                          $_iproperty_citiesmid['columns'] = "(title, mc_name, state)";
                                          //$_iproperty_citiesmid['values'][] = "('".$record[$keys[$i]]."','',3921)";
                                          $_iproperty_citiesmid['values'] = "('".$get_city."','',3921)";


                                          echo "INSERTING CITY: ";echo $get_city;

                                          $values = implode(",", $_iproperty_citiesmid['values']);
                                          $sql = "INSERT INTO ".$prefix."iproperty_cities ".$_iproperty_citiesmid['columns']." VALUES ".$_iproperty_citiesmid['values'];
                                          //echo "Insert SQL: ";echo $sql;
                                          $inserted_properties = mysqli_query($conn, $sql);
                                          //echo "INSERTED_PROPERTIES: ";echo $inserted_properties;

                                          $sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$get_city."'";
                                          $result = mysqli_query($conn, $sql);
                                        }

                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            if(!empty($row["id"])){
                                                $city = $row["id"];
                                                //echo $city; echo "<br/><br/>";
                                            }else{
                                                 $city = $get_city;
                                                 }
                                        }else {
                                              $city = $get_city;
                                              }
                                    }
                                }

                                // amenities
                                if($keys[$i] == "GF20070914134205261619000000") array_push($proamenities, 25);
                                if($keys[$i] == "FEAT20110510163155967817000000") array_push($proamenities, 4);
                                if($keys[$i] == "GF20070913202500135759000000") array_push($proamenities, 5);
                                if($keys[$i] == "GF20070913202500135494000000") array_push($proamenities, 34);
                                if($keys[$i] == "GF20070913202500135727000000") array_push($proamenities, 3);
                                if($keys[$i] == "GF20100909235843952771000000") array_push($proamenities, 23);
                                if($keys[$i] == "GF20070914134031105159000000") array_push($proamenities, 84);
                                if($keys[$i] == "GF20070914134018021136000000") array_push($proamenities, 37);
                                if($keys[$i] == "GF20070913202500135598000000") array_push($proamenities, 87);

                            }
                            $re = explode(',',$record['GF20071121011753771268000000']);
                            $re=array_values(array_diff($re,array("null","")));
                            if(count($re) > 0){
                                $amenities_titles = "'".implode("','", $re)."'";
                                echo $sql = "SELECT id FROM ".$prefix."iproperty_amenities WHERE title IN (".$amenities_titles.")";exit;
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                                        if(!empty($row["id"])){
                                            array_push($proamenities, $row["id"]);
                                        }
                                    }
                                }
                            }

                            $property_cat = 1;

                            //this list_87 for created, published, modifies
                            $created_date = str_replace('T', ' ', $record['LIST_87']);
                            $locstate = 3921;
                            $country = 231;

                            $_iproperty['values'][] = "(0,
                                                 1,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $country))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $locstate))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $city))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_67']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_52']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_66']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_53']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_47']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_46']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_43']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_34']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_31']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_130']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_32']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_33']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                 'SqFt',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_25']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_22']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_24']))."',
                                                 1,
                                                 1,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134222636466000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_105']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_1']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_5']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_8']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_113']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_41']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_35']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_75']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_76']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_92']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_48']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_114']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_56']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134031105159000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134018021136000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_143']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_73']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_74']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134004605295000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_112']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_111']))."',
                                                 785,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_9']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_29']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_58']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_59']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_60']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_78']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_80']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_85']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_86']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_93']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_101']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_131']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_108']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_109']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_110']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_119']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_118']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_124']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_125']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_126']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203318529362000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203326455292000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203335955409000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203731663536000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203441259293000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205202933704000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205229919954000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205338315648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225028756930000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225501566841000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412230614024897000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203556732237000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203729521799000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203819097748000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161043032254000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161604325096000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914185721572556000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163434654648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163837118120000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080404180341443455000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135794000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131719000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131436000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135630000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135759000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131789000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20121130191428826666000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203457617516000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20080207202713312731000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135662000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_shortid']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_shortid']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_name']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_name']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['UNBRANDEDIDXVIRTUALTOUR']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['picture_count']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_68']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_69']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_91']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_107']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412222540943628000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116192529063673000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127181504522660000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130402192514025687000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193640412686000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193617886554000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_49']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_57']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_72']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_95']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_117']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035540438309000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035349289855000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035530068525000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035440502884000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035428139715000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', str_replace("'", "", $record['GF20071117040305000417000000'])))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035412533196000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119135700713497000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150611106648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150518403713000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150620828281000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172524344039000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071121011759800083000000']))."')";



                            $propId = ++$max_id;
                            if(count($proamenities) > 0){
                                $c = 0;
                                $v = array();
                                foreach ($proamenities as $pro) {
                                    $midvalues = array($propId, $property_cat, $pro);
                                    $v[$c++] = "(".implode(",", $midvalues).")";
                                }
                                $v = implode(",", $v);
                                $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                                $_iproperty_propmid['values'][] = $v;
                            } else {
                                $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                                $_iproperty_propmid['values'][] = "(".$propId.", ".$property_cat.", 0)";
                            }
                            $sql = "SELECT id FROM ".$prefix."user_usergroup_map AS map INNER JOIN ".$prefix."iproperty_agents AS ag ON map.user_id = ag.user_id WHERE map.group_id = 8 AND ag.agent_type = 1";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                if(count($proamenities) > 0){
                                    $_iproperty_agentmid['columns'] = "(prop_id, agent_id, agent_type)";
                                    $_iproperty_agentmid['values'][] = "(".$propId.", ".$row['id'].", 1)";
                                }
                            }
                            $count++;
                        }
                    }
                } else if($data['ClassName'] == 'B'){
                    foreach ($results as $record) {
                        $sql = "SELECT id AS my_prop_id FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105']." AND from_rets=1 AND locstate=3921";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {

                           if(!empty($row['my_prop_id'])) {
                               $my_prop_id = $row['my_prop_id'];
                               echo "<my_prop_id>"; print_r($my_prop_id);
                            }

                            if ($record['LIST_15'] != 'Active') {
                               echo "LISTING  B NOT ACTIVE";
                               /*$update_status = "UPDATE ".$prefix."iproperty SET state=0, approved=0, access=0 WHERE mls_id=".$record['LIST_105'];
                               $update_status_exe = mysqli_query($conn, $update_status);*/
                               
                               //$update_status = "DELETE FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105'];
                               $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                echo "This ".$record['LIST_105']." MLS Number Property is Not Active. So Deleted this Property from our Database";
                               }

                               echo "Deleting B Images";
                               $update_status = "DELETE FROM ".$prefix."iproperty_images WHERE propid=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                 echo "This my_prop_id=".$my_prop_id." Images Deleted from our Database";
                               }

                            }
                        } else {
                            if ($record['LIST_15'] != 'Active') {
                                continue;
                            }
                            $proamenities = array();
                            array_push($proamenities, 0);

                            for ($i=0; $i < count($keys); $i++) {
                                    // get city...
                                if($keys[$i] == "LIST_39") {
                                    $city = '';
                                    if(!empty($record[$keys[$i]])) {
                                        $sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$record[$keys[$i]]."'";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            if(!empty($row["id"])){
                                                $city = $row["id"];
                                            }
                                        }
                                    }
                                }

                                // amenities
                                if($keys[$i] == "GF20070914134205261619000000") array_push($proamenities, 25);
                                if($keys[$i] == "FEAT20110510163155967817000000") array_push($proamenities, 4);
                                if($keys[$i] == "GF20070913202500135759000000") array_push($proamenities, 5);
                                if($keys[$i] == "GF20070913202500135494000000") array_push($proamenities, 34);
                                if($keys[$i] == "GF20070913202500135727000000") array_push($proamenities, 3);
                                if($keys[$i] == "GF20100909235843952771000000") array_push($proamenities, 23);
                                if($keys[$i] == "GF20070914134031105159000000") array_push($proamenities, 84);
                                if($keys[$i] == "GF20070914134018021136000000") array_push($proamenities, 37);
                                if($keys[$i] == "GF20070913202500135598000000") array_push($proamenities, 87);

                            }

                            $re = explode(',',$record['GF20071121011753771268000000']);
                            $re=array_values(array_diff($re,array("null","")));
                            if(count($re) > 0){
                                $amenities_titles = "'".implode("','", $re)."'";
                                $sql = "SELECT id FROM ".$prefix."_iproperty_amenities WHERE title IN (".$amenities_titles.")";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                                        if(!empty($row["id"])){
                                            array_push($proamenities, $row["id"]);
                                        }
                                    }
                                }
                            }
                            $property_cat = 2;

                                //this list_87 for created, published, modifies
                            $created_date = str_replace('T', ' ', $record['LIST_87']);
                            $locstate = 3921;
                            $country = 231;
                            //echo "<pre>"; print_r($proamenities);
                            // Insert values...
                            $_iproperty['values'][] = "(0,
                                                1,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $country))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $locstate))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $city))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_67']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_52']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_66']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_53']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_47']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_46']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_43']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_34']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_31']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_130']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_32']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_33']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                 'SqFt',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_25']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_22']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_24']))."',
                                                 1,
                                                 1,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134222636466000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_105']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_1']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_5']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_8']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_113']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_41']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_35']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_75']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_76']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_92']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_48']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_114']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_56']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134031105159000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134018021136000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_143']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_73']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_74']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134004605295000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_112']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_111']))."',
                                                 785,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_9']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_29']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_58']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_59']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_60']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_78']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_80']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_85']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_86']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_93']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_101']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_131']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_108']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_109']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_110']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_119']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_118']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_124']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_125']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_126']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080204211931471021000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080204211938974272000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080204211938974272000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080204212017122314000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080204212033044444000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205202933704000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205229919954000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205338315648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225028756930000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225501566841000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412230614024897000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203556732237000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203729521799000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203819097748000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161859632166000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161925161990000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914185721572556000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510165207213719000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510165242171220000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510165328434394000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080404205845999146000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203453881181000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203454474848000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203455097260000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203455818652000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203455656746000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203457156956000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20121130191428826666000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203457617516000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20100125194441384838000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203500475777000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_shortid']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_shortid']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_name']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_name']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['UNBRANDEDIDXVIRTUALTOUR']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['picture_count']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_68']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_69']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_91']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_107']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412222540943628000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116192529063673000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127181504522660000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130402192514025687000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193640412686000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193617886554000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_49']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_57']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_72']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_95']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_117']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035540438309000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035349289855000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035530068525000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035440502884000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035428139715000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', str_replace("'", "", $record['GF20071117040305000417000000'])))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035412533196000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119135700713497000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150611106648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150518403713000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150620828281000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172524344039000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071121011759800083000000']))."')";

                            $propId = ++$max_id;

                            if(count($proamenities) > 0){
                                $c = 0;
                                $v = array();
                                foreach ($proamenities as $pro) {
                                    $midvalues = array($propId, $property_cat, $pro);
                                    $v[$c++] = "(".implode(",", $midvalues).")";
                                }
                                $v = implode(",", $v);
                                $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                                $_iproperty_propmid['values'][] = $v;
                            } else {
                                $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                                $_iproperty_propmid['values'][] = "(".$propId.", ".$property_cat.", 0)";
                            }
                                $sql = "SELECT id FROM ".$prefix."user_usergroup_map AS map INNER JOIN ".$prefix."iproperty_agents AS ag ON map.user_id = ag.user_id WHERE map.group_id = 8 AND ag.agent_type = 1";
                                $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                if(count($proamenities) > 0){
                                    $_iproperty_agentmid['columns'] = "(prop_id, agent_id, agent_type)";
                                    $_iproperty_agentmid['values'][] = "(".$propId.", ".$row['id'].", 1)";
                                }
                            }
                            $count++;
                        }
                    }
                } else if($data['ClassName'] == 'C'){
                    foreach ($results as $record) {
                        $sql = "SELECT id AS my_prop_id FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105']." AND from_rets=1 AND locstate=3921";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {

                           if(!empty($row['my_prop_id'])) {
                               $my_prop_id = $row['my_prop_id'];
                               echo "<my_prop_id>"; print_r($my_prop_id);
                            }

                            if ($record['LIST_15'] != 'Active') {
                               echo "LISTING  C NOT ACTIVE";
                               /*$update_status = "UPDATE ".$prefix."iproperty SET state=0, approved=0, access=0 WHERE mls_id=".$record['LIST_105'];
                               $update_status_exe = mysqli_query($conn, $update_status);*/
                               
                               //$update_status = "DELETE FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105'];
                               $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                echo "This ".$record['LIST_105']." MLS Number Property is Not Active. So Deleting this Property from our Database";
                               }

                               echo "Deleting C Images";
                               $update_status = "DELETE FROM ".$prefix."iproperty_images WHERE propid=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                 echo "This my_prop_id=".$my_prop_id." Images Deleted from our Database";
                               }

                            }
                        } else {
                            if ($record['LIST_15'] != 'Active') {
                                continue;
                            }
                            $proamenities = array();
                            array_push($proamenities, 0);

                        for ($i=0; $i < count($keys); $i++) {
                            // get city...
                            if($keys[$i] == "LIST_39") {
                                $city = '';
                                if(!empty($record[$keys[$i]])) {
                                    $sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$record[$keys[$i]]."'";
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                        if(!empty($row["id"])){
                                            $city = $row["id"];
                                        }
                                    }
                                }
                            }

                            // amenities
                            if($keys[$i] == "GF20070914134205261619000000") array_push($proamenities, 25);
                            if($keys[$i] == "FEAT20110510163155967817000000") array_push($proamenities, 4);
                            if($keys[$i] == "GF20070913202500135759000000") array_push($proamenities, 5);
                            if($keys[$i] == "GF20070913202500135494000000") array_push($proamenities, 34);
                            if($keys[$i] == "GF20070913202500135727000000") array_push($proamenities, 3);
                            if($keys[$i] == "GF20100909235843952771000000") array_push($proamenities, 23);
                            if($keys[$i] == "GF20070914134031105159000000") array_push($proamenities, 84);
                            if($keys[$i] == "GF20070914134018021136000000") array_push($proamenities, 37);
                            if($keys[$i] == "GF20070913202500135598000000") array_push($proamenities, 87);

                        }

                        $re = explode(',',$record['GF20071121011753771268000000']);
                        $re=array_values(array_diff($re,array("null","")));
                        if(count($re) > 0){
                            $amenities_titles = "'".implode("','", $re)."'";
                            $sql = "SELECT id FROM ".$prefix."_iproperty_amenities WHERE title IN (".$amenities_titles.")";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)){
                                    if(!empty($row["id"])){
                                        array_push($proamenities, $row["id"]);
                                    }
                                }
                            }
                        }
                        $property_cat = 3;

                        //this list_87 for created, published, modifies
                        $created_date = str_replace('T', ' ', $record['LIST_87']);
                        $locstate = 3921;
                        $country = 231;
                        //echo "<pre>"; print_r($proamenities);
                        // Insert values...
                        $_iproperty['values'][] = "(0,
                                            1,
                                             '".str_replace("'", "\'", str_replace("''", '"', $country))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $locstate))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $city))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_67']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_52']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_66']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_53']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_47']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_46']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_43']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_34']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_31']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_130']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_32']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_33']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                             'SqFt',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_25']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_22']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_24']))."',
                                             1,
                                             1,
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134222636466000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_105']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_1']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_5']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_8']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_113']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_41']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_35']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_75']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_76']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_92']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_48']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_114']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_56']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134031105159000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134018021136000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_143']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_73']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_74']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134004605295000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_112']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_111']))."',
                                             785,
                                             '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_9']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_29']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_58']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_59']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_60']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_78']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_80']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_85']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_86']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_93']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_101']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_131']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_108']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_109']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_110']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_119']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_118']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_124']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_125']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_126']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080222174009973866000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080222174023344299000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080222173951418327000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080204212516762546000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080204212528532575000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127220034630438000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071201025833384891000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127220235522942000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127222801556165000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127222612720056000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127222516825843000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203556732237000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203729521799000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203819097748000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161043032254000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161604325096000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914185721572556000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163434654648000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163837118120000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080404211507854818000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135794000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131719000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131436000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135630000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135759000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131789000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20121130191428826666000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203457617516000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20080207202752466102000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135662000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_shortid']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_shortid']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_name']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_name']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['UNBRANDEDIDXVIRTUALTOUR']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['picture_count']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_68']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_69']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_91']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_107']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412222540943628000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116192529063673000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127181504522660000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130402192514025687000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193640412686000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193617886554000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_49']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_57']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_72']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_95']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_117']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035540438309000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035349289855000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035530068525000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035440502884000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035428139715000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', str_replace("'", "", $record['GF20071117040305000417000000'])))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035412533196000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119135700713497000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150611106648000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150518403713000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150620828281000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172524344039000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071121011759800083000000']))."')";

                        $propId = ++$max_id;

                        if(count($proamenities) > 0){
                            $c = 0;
                            $v = array();
                            foreach ($proamenities as $pro) {
                                $midvalues = array($propId, $property_cat, $pro);
                                $v[$c++] = "(".implode(",", $midvalues).")";
                            }
                            $v = implode(",", $v);
                            $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                            $_iproperty_propmid['values'][] = $v;
                        } else {
                            $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                            $_iproperty_propmid['values'][] = "(".$propId.", ".$property_cat.", 0)";
                        }
                        $sql = "SELECT id FROM ".$prefix."user_usergroup_map AS map INNER JOIN ".$prefix."iproperty_agents AS ag ON map.user_id = ag.user_id WHERE map.group_id = 8 AND ag.agent_type = 1";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            if(count($proamenities) > 0){
                                $_iproperty_agentmid['columns'] = "(prop_id, agent_id, agent_type)";
                                $_iproperty_agentmid['values'][] = "(".$propId.", ".$row['id'].", 1)";
                            }
                        }
                        $count++;
                        }
                    }
                } else if($data['ClassName'] == 'D'){
                    foreach ($results as $record) {
                        $sql = "SELECT id AS my_prop_id FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105']." AND from_rets=1 AND locstate=3921";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {

                           if(!empty($row['my_prop_id'])) {
                               $my_prop_id = $row['my_prop_id'];
                               echo "<my_prop_id>"; print_r($my_prop_id);
                            }

                            if ($record['LIST_15'] != 'Active') {
                           echo "LISTING  D NOT ACTIVE";
                           /*$update_status = "UPDATE ".$prefix."iproperty SET state=0, approved=0, access=0 WHERE mls_id=".$record['LIST_105'];
                           $update_status_exe = mysqli_query($conn, $update_status);*/
                           
                           //$update_status = "DELETE FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105'];
                           $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$my_prop_id;
                           $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                echo "This ".$record['LIST_105']." MLS Number Property is Not Active. So Deleting this Property from our Database";
                               }

                               echo "Deleting D Images";
                               $update_status = "DELETE FROM ".$prefix."iproperty_images WHERE propid=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                 echo "This my_prop_id=".$my_prop_id." Images Deleted from our Database";
                               }

                            }
                        } else {
                            if ($record['LIST_15'] != 'Active') {
                                continue;
                            }
                            $proamenities = array();
                            array_push($proamenities, 0);

                            for ($i=0; $i < count($keys); $i++) {
                                // get city...
                                if($keys[$i] == "LIST_39") {
                                    $city = '';
                                    if(!empty($record[$keys[$i]])) {
                                        $sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$record[$keys[$i]]."'";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            if(!empty($row["id"])){
                                                $city = $row["id"];
                                            }
                                        }
                                    }
                                }
                                // amenities
                                if($keys[$i] == "GF20070914134205261619000000") array_push($proamenities, 25);
                                if($keys[$i] == "FEAT20110510163155967817000000") array_push($proamenities, 4);
                                if($keys[$i] == "GF20070913202500135759000000") array_push($proamenities, 5);
                                if($keys[$i] == "GF20070913202500135494000000") array_push($proamenities, 34);
                                if($keys[$i] == "GF20070913202500135727000000") array_push($proamenities, 3);
                                if($keys[$i] == "GF20100909235843952771000000") array_push($proamenities, 23);
                                if($keys[$i] == "GF20070914134031105159000000") array_push($proamenities, 84);
                                if($keys[$i] == "GF20070914134018021136000000") array_push($proamenities, 37);
                                if($keys[$i] == "GF20070913202500135598000000") array_push($proamenities, 87);

                            }

                            $re = explode(',',$record['GF20071119150727236413000000']);
                            $re=array_values(array_diff($re,array("null","")));
                            if(count($re) > 0){
                                $amenities_titles = "'".implode("','", $re)."'";
                                $sql = "SELECT id FROM ".$prefix."_iproperty_amenities WHERE title IN (".$amenities_titles.")";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                                        if(!empty($row["id"])){
                                            array_push($proamenities, $row["id"]);
                                        }
                                    }
                                }
                            }
                            $property_cat = 4;

                            //this list_87 for created, published, modifies
                            $created_date = str_replace('T', ' ', $record['LIST_87']);
                            $locstate = 3921;
                            $country = 231;
                            //echo "<pre>"; print_r($proamenities);
                            // Insert values...
                            $_iproperty['values'][] = "(0,
                                                1,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $country))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $locstate))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $city))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_67']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_52']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_66']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_53']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_47']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_46']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_43']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_34']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_31']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_130']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_32']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_33']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                 'SqFt',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_25']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_22']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_24']))."',
                                                 1,
                                                 1,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134222636466000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_105']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_1']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_5']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_8']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_113']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_41']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_35']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_75']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_76']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_92']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_48']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_114']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_56']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134031105159000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134018021136000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_143']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_73']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_74']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134004605295000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_112']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_111']))."',
                                                 785,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_9']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_29']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_58']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_59']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_60']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_78']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_80']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_85']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_86']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_93']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_101']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_131']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_108']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_109']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_110']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_119']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_118']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_124']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_125']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_126']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207165832716619000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207165838587603000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207165850278878000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207165911829315000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207165920125762000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205202933704000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205229919954000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205338315648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225028756930000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225501566841000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412230614024897000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203556732237000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203729521799000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203819097748000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161043032254000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161604325096000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914185721572556000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163434654648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163837118120000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080404213101600538000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135794000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119135700382116000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131436000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135630000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135759000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131789000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20121130191428826666000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203457617516000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20080207202713312731000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135662000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_shortid']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_shortid']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_name']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_name']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['UNBRANDEDIDXVIRTUALTOUR']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['picture_count']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_68']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_69']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_91']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_107']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412222540943628000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116192529063673000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127181504522660000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130402192514025687000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193640412686000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193617886554000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_49']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_57']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_72']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_95']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_117']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035540438309000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035349289855000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035530068525000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035440502884000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035428139715000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', str_replace("'", "", $record['GF20071119150507770471000000'])))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035412533196000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119135700713497000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150611106648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150518403713000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150620828281000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172524344039000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071121011759800083000000']))."')";

                            $propId = ++$max_id;

                            if(count($proamenities) > 0){
                                $c = 0;
                                $v = array();
                                foreach ($proamenities as $pro) {
                                    $midvalues = array($propId, $property_cat, $pro);
                                    $v[$c++] = "(".implode(",", $midvalues).")";
                                }
                                $v = implode(",", $v);
                                $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                                $_iproperty_propmid['values'][] = $v;
                            } else {
                                $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                                $_iproperty_propmid['values'][] = "(".$propId.", ".$property_cat.", 0)";
                            }
                            $sql = "SELECT id FROM ".$prefix."user_usergroup_map AS map INNER JOIN ".$prefix."iproperty_agents AS ag ON map.user_id = ag.user_id WHERE map.group_id = 8 AND ag.agent_type = 1";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                if(count($proamenities) > 0){
                                    $_iproperty_agentmid['columns'] = "(prop_id, agent_id, agent_type)";
                                    $_iproperty_agentmid['values'][] = "(".$propId.", ".$row['id'].", 1)";
                                }
                            }
                            $count++;
                        }
                    }
                } else if($data['ClassName'] == 'E'){
                    foreach ($results as $record) {
                        $sql = "SELECT id AS my_prop_id FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105']." AND from_rets=1 AND locstate=3921";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {

                           if(!empty($row['my_prop_id'])) {
                               $my_prop_id = $row['my_prop_id'];
                               echo "<my_prop_id>"; print_r($my_prop_id);
                            }

                            if ($record['LIST_15'] != 'Active') {
                                echo "LISTING  E NOT ACTIVE";
                                /*$update_status = "UPDATE ".$prefix."iproperty SET state=0, approved=0, access=0 WHERE mls_id=".$record['LIST_105'];
                                $update_status_exe = mysqli_query($conn, $update_status);*/
                                
                                //$update_status = "DELETE FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105'];
                                $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$my_prop_id;
                                $update_status_exe = mysqli_query($conn, $update_status);
                                if($update_status_exe){
                                echo "This ".$record['LIST_105']." MLS Number Property is Not Active. So Deleted this Property from our Database";
                                }

                               echo "Deleting E Images";
                               $update_status = "DELETE FROM ".$prefix."iproperty_images WHERE propid=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                 echo "This my_prop_id=".$my_prop_id." Images Deleted from our Database";
                               }

                            }
                        } else {
                            if ($record['LIST_15'] != 'Active') {
                                continue;
                            }
                            $proamenities = array();
                            array_push($proamenities, 0);

                            for ($i=0; $i < count($keys); $i++) {
                                // get city...
                                if($keys[$i] == "LIST_39") {
                                    $city = '';
                                    if(!empty($record[$keys[$i]])) {
                                        $sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$record[$keys[$i]]."'";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            if(!empty($row["id"])){
                                                $city = $row["id"];
                                            }
                                        }
                                    }
                                }
                                // amenities
                                if($keys[$i] == "GF20070914134205261619000000") array_push($proamenities, 25);
                                if($keys[$i] == "FEAT20110510163155967817000000") array_push($proamenities, 4);
                                if($keys[$i] == "GF20070913202500135759000000") array_push($proamenities, 5);
                                if($keys[$i] == "GF20070913202500135494000000") array_push($proamenities, 34);
                                if($keys[$i] == "GF20070913202500135727000000") array_push($proamenities, 3);
                                if($keys[$i] == "GF20100909235843952771000000") array_push($proamenities, 23);
                                if($keys[$i] == "GF20070914134031105159000000") array_push($proamenities, 84);
                                if($keys[$i] == "GF20070914134018021136000000") array_push($proamenities, 37);
                                if($keys[$i] == "GF20070913202500135598000000") array_push($proamenities, 87);

                            }

                            $re = explode(',',$record['GF20071119172525590600000000']);
                            $re=array_values(array_diff($re,array("null","")));
                            if(count($re) > 0){
                                $amenities_titles = "'".implode("','", $re)."'";
                                $sql = "SELECT id FROM ".$prefix."_iproperty_amenities WHERE title IN (".$amenities_titles.")";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                                        if(!empty($row["id"])){
                                            array_push($proamenities, $row["id"]);
                                        }
                                    }
                                }
                            }
                            $property_cat = 5;

                            //this list_87 for created, published, modifies
                            $created_date = str_replace('T', ' ', $record['LIST_87']);
                            $locstate = 3921;
                            $country = 231;
                            //echo "<pre>"; print_r($proamenities);
                            // Insert values...
                            $_iproperty['values'][] = "(0,
                                                1,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $country))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $locstate))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $city))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_67']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_52']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_66']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_53']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_47']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_46']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_43']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_34']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_31']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_130']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_32']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_33']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                                 'SqFt',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_25']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_22']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_24']))."',
                                                 1,
                                                 1,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134222636466000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_105']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_1']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_5']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_8']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_113']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_41']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_35']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_75']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_76']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_92']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_48']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_114']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_56']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134031105159000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134018021136000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_143']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_73']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_74']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134004605295000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_112']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_111']))."',
                                                 785,
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_9']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_29']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_58']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_59']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_60']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_78']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_80']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_85']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_86']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_93']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_101']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_131']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_108']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_109']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_110']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_119']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_118']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_124']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_125']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_126']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170327130633000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170333803282000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170344208800000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170405809083000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080123203441259293000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205202933704000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205229919954000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205338315648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225028756930000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225501566841000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412230614024897000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203556732237000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203729521799000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203819097748000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161043032254000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161604325096000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914185721572556000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163434654648000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163837118120000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080404180341443455000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135794000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172525971739000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131436000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135630000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135759000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131789000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20121130191428826666000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203457617516000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20080207202713312731000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135662000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_shortid']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_shortid']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_name']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_name']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['UNBRANDEDIDXVIRTUALTOUR']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['picture_count']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_68']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_69']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_91']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_107']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412222540943628000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116192529063673000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127181504522660000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130402192514025687000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193640412686000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193617886554000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_49']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_57']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_72']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_95']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_117']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035540438309000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035349289855000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035530068525000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035440502884000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035428139715000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', str_replace("'", "", $record['GF20071117040305000417000000'])))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035412533196000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172526400141000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172526560972000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172528895341000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172536579654000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172524344039000000']))."',
                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071121011759800083000000']))."')";

                            $propId = ++$max_id;

                            if(count($proamenities) > 0){
                                $c = 0;
                                $v = array();
                                foreach ($proamenities as $pro) {
                                    $midvalues = array($propId, $property_cat, $pro);
                                    $v[$c++] = "(".implode(",", $midvalues).")";
                                }
                                $v = implode(",", $v);
                                $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                                $_iproperty_propmid['values'][] = $v;
                            } else {
                                $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                                $_iproperty_propmid['values'][] = "(".$propId.", ".$property_cat.", 0)";
                            }
                            $sql = "SELECT id FROM ".$prefix."user_usergroup_map AS map INNER JOIN ".$prefix."iproperty_agents AS ag ON map.user_id = ag.user_id WHERE map.group_id = 8 AND ag.agent_type = 1";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                if(count($proamenities) > 0){
                                    $_iproperty_agentmid['columns'] = "(prop_id, agent_id, agent_type)";
                                    $_iproperty_agentmid['values'][] = "(".$propId.", ".$row['id'].", 1)";
                                }
                            }
                            $count++;
                        }
                    }
                } else if($data['ClassName'] == 'F'){
                    foreach ($results as $record) {
                        $sql = "SELECT id AS my_prop_id FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105']." AND from_rets=1 AND locstate=3921";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {

                           if(!empty($row['my_prop_id'])) {
                               $my_prop_id = $row['my_prop_id'];
                               echo "<my_prop_id>"; print_r($my_prop_id);
                            }

                            if ($record['LIST_15'] != 'Active') {
                                echo "LISTING  F NOT ACTIVE";
                                /*$update_status = "UPDATE ".$prefix."iproperty SET state=0, approved=0, access=0 WHERE mls_id=".$record['LIST_105'];
                                $update_status_exe = mysqli_query($conn, $update_status);*/
                                
                               //$update_status = "DELETE FROM ".$prefix."iproperty WHERE mls_id=".$record['LIST_105'];
                               $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                                if($update_status_exe){
                                echo "This ".$record['LIST_105']." MLS Number Property is Not Active. So Deleted this Property from our Database";
                                }

                               echo "Deleting F Images";
                               $update_status = "DELETE FROM ".$prefix."iproperty_images WHERE propid=".$my_prop_id;
                               $update_status_exe = mysqli_query($conn, $update_status);
                               if($update_status_exe){
                                 echo "This my_prop_id=".$my_prop_id." Images Deleted from our Database";
                               }

                            }
                        } else {
                            if ($record['LIST_15'] != 'Active') {
                                continue;
                            }
                            $proamenities = array();
                        array_push($proamenities, 0);

                        for ($i=0; $i < count($keys); $i++) {
                            // get city...
                            if($keys[$i] == "LIST_39") {
                                $city = '';
                                if(!empty($record[$keys[$i]])) {
                                    $sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$record[$keys[$i]]."'";
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                        if(!empty($row["id"])){
                                            $city = $row["id"];
                                        }
                                    }
                                }
                            }
                            // amenities
                            if($keys[$i] == "GF20070914134205261619000000") array_push($proamenities, 25);
                            if($keys[$i] == "FEAT20110510163155967817000000") array_push($proamenities, 4);
                            if($keys[$i] == "GF20070913202500135759000000") array_push($proamenities, 5);
                            if($keys[$i] == "GF20070913202500135494000000") array_push($proamenities, 34);
                            if($keys[$i] == "GF20070913202500135727000000") array_push($proamenities, 3);
                            if($keys[$i] == "GF20100909235843952771000000") array_push($proamenities, 23);
                            if($keys[$i] == "GF20070914134031105159000000") array_push($proamenities, 84);
                            if($keys[$i] == "GF20070914134018021136000000") array_push($proamenities, 37);
                            if($keys[$i] == "GF20070913202500135598000000") array_push($proamenities, 87);

                        }

                        $re = explode(',',$record['GF20071121011753771268000000']);
                        $re=array_values(array_diff($re,array("null","")));
                        if(count($re) > 0){
                            $amenities_titles = "'".implode("','", $re)."'";
                            $sql = "SELECT id FROM ".$prefix."_iproperty_amenities WHERE title IN (".$amenities_titles.")";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)){
                                    if(!empty($row["id"])){
                                        array_push($proamenities, $row["id"]);
                                    }
                                }
                            }
                        }
                        $property_cat = 6;

                        //this list_87 for created, published, modifies
                        $created_date = str_replace('T', ' ', $record['LIST_87']);
                        $locstate = 3921;
                        $country = 231;
                        //echo "<pre>"; print_r($proamenities);
                        // Insert values...
                        $_iproperty['values'][] = "(0,
                                            1,
                                             '".str_replace("'", "\'", str_replace("''", '"', $country))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $locstate))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $city))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_67']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_52']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_66']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_53']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_47']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_46']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_43']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_34']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_31']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_130']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_32']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_33']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_37']))."',
                                             'SqFt',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_25']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_22']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_24']))."',
                                             1,
                                             1,
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134222636466000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_105']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_1']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_5']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_8']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_113']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_41']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_35']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_75']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_76']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_92']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_48']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_114']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_56']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134031105159000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134018021136000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_143']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_73']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_74']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070914134004605295000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_112']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_111']))."',
                                             785,
                                             '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $created_date))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_9']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_29']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_58']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_59']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_60']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_78']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_80']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_85']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_86']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_93']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_101']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_131']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_108']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_109']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_110']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_119']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_118']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_124']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_125']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_126']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170630116587000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170637898113000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170645142514000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170735671566000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080207170748472299000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205202933704000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205229919954000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914205338315648000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225028756930000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412225501566841000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412230614024897000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203556732237000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203729521799000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20121130203819097748000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161043032254000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130513161604325096000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20070914185721572556000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163155967817000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163434654648000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110510163837118120000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20080407134907758005000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135794000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172525971739000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131436000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135630000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135759000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500131789000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20121130191428826666000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071116203457617516000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20080207202713312731000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20070913202500135662000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_shortid']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_shortid']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['listing_member_name']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['listing_office_name']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['UNBRANDEDIDXVIRTUALTOUR']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['picture_count']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_68']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_69']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_91']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_107']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20110412222540943628000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116192529063673000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20120127181504522660000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20130402192514025687000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193640412686000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['FEAT20071116193617886554000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_49']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_57']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_72']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_95']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['LIST_117']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035540438309000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035349289855000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035530068525000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035440502884000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035428139715000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', str_replace("'", "", $record['GF20071117040305000417000000'])))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071117035412533196000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071121011754872078000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150611106648000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150518403713000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119150620828281000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071119172524344039000000']))."',
                                             '".str_replace("'", "\'", str_replace("''", '"', $record['GF20071121011759800083000000']))."')";

                        $propId = ++$max_id;

                        if(count($proamenities) > 0){
                            $c = 0;
                            $v = array();
                            foreach ($proamenities as $pro) {
                                $midvalues = array($propId, $property_cat, $pro);
                                $v[$c++] = "(".implode(",", $midvalues).")";
                            }
                            $v = implode(",", $v);
                            $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                            $_iproperty_propmid['values'][] = $v;
                        } else {
                            $_iproperty_propmid['columns'] = '(prop_id, cat_id, amen_id)';
                            $_iproperty_propmid['values'][] = "(".$propId.", ".$property_cat.", 0)";
                        }
                        $sql = "SELECT id FROM ".$prefix."user_usergroup_map AS map INNER JOIN ".$prefix."iproperty_agents AS ag ON map.user_id = ag.user_id WHERE map.group_id = 8 AND ag.agent_type = 1";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            if(count($proamenities) > 0){
                                $_iproperty_agentmid['columns'] = "(prop_id, agent_id, agent_type)";
                                $_iproperty_agentmid['values'][] = "(".$propId.", ".$row['id'].", 1)";
                            }
                        }
                        $count++;
                        }
                    }
                }
                $rets->FreeResult($results);

                echo "<br/><br/>Type: ".($data['ClassName'])."-".count($_iproperty['values']).' Properties Fetched Successfully';
                if($count > 0 && count($_iproperty['values']) > 0){
                    $values = implode(",", $_iproperty['values']);
                    $sql = "INSERT IGNORE INTO ".$prefix."iproperty ".$_iproperty['columns']." VALUES ".$values;
                    $inserted_properties = mysqli_query($conn, $sql);
                    if($inserted_properties){
                        $values = implode(",", $_iproperty_propmid['values']);
                        $sql = "INSERT INTO ".$prefix."iproperty_propmid ".$_iproperty_propmid['columns']." VALUES ".$values;
                        $inserted_properties = mysqli_query($conn, $sql);
                        if($inserted_properties){
                            $values = implode(",", $_iproperty_agentmid['values']);
                            $sql = "INSERT INTO ".$prefix."iproperty_agentmid ".$_iproperty_agentmid['columns']." VALUES ".$values;
                            $inserted_properties = mysqli_query($conn, $sql);
                        }
                    }
                }
            }

        }
    }
?>
