<?php
		require_once("phrets.php");
		require_once("configuration.php");

		$config = new JConfig();

		$prefix = $config->dbprefix;

		// Create connection
		$conn = mysqli_connect($config->host, $config->user, $config->password, $config->db);
		if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

		$rets_login_url = "http://rets.rmlsweb.com:6103/rets/login";
        $rets_username = "DS04US51";
        $rets_password = "DS0807US";


		$rets = new phRETS;
		$connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
		if (!$connect) {
			print_r($rets->Error()); exit;
		}
		date_default_timezone_set('US/Arizona');
	    $date = date('Y-m-d')."+";

	    $query = "(DateTimeModified=".$date.")";
		/*$query = "(StandardStatus=Actice)";
		$query = "(ListingStatus=ACT)";*/
		if(!empty($_REQUEST['query'])){
			$query = $_REQUEST['query'];
		}
    $date2 = date('h:i:s');
echo "OR Properties starting at:"; echo $date2;
echo "Query:";echo $query;

                $limit = 200;
		if(!empty($_REQUEST['limit'])){
			$limit = $_REQUEST['limit'];
		}
		$types = $rets->GetMetadataTypes();
	    foreach ($types as $type) {
	        if($type['Resource'] == "Property"){
	            foreach ($type['Data'] as $data) {
	            	$fields = $rets->GetMetadataTable($type['Resource'], $data['ClassName']);
                    $results = $rets->Search($type['Resource'], $data['ClassName'] ,$query, array("Limit" => $limit, "Offset"=> $offset));
                    //echo "<pre>"; print_r($results);
                    //echo "<fields>"; print_r($fields);
                    //echo "<data>"; print_r($data);

                    $properties = array();
					$keys = array();
					foreach ($fields as $key) {
						$keys[] = $key['SystemName'];
					}

					$_iproperty = array();
					$_iproperty_propmid = array();
					$_iproperty_agentmid = array();
                                        $_iproperty_citiesmid = array();

					$_iproperty['columns'] = "(mls_id,int_list_id,latitude,longitude,for_picture,created_by,locstate,city,baths,beds,parking_space,garage_type,association_hoa_fee,association_hoa2_fee,association_hoa_fee_yes,elementary_school,high_school,middle_school,sqft,subdivision,property_type,yearbuilt,fireplace,interior_levels,lotsize,building_style,property_area,county,created,modified, publish_up,agent_phone_num,agent_email,agentid,listing_member_name,listing_office_shortid,listing_office_name,description,access,country,picture_count,price_type,public_remark,street,street_num,street_suffix,street_compass,postcode,zoning,price2,dwelling_type,from_rets)";


					$sql = "SELECT MAX(id) AS max_id FROM ".$prefix."iproperty";
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) > 0) {
						$row = mysqli_fetch_assoc($result);
						if(!empty($row['max_id']))
							$max_id = $row['max_id'];
					}
					$propId = $max_id;
					$count = 0;
					foreach ($results as $record) {

						$my_prop_id = 0;
                                                $sql = "SELECT id AS my_prop_id FROM ".$prefix."iproperty WHERE mls_id=".$record['ListingID']." AND from_rets=1 AND locstate=3962";
						$result = mysqli_query($conn, $sql);
						if (mysqli_num_rows($result) > 0) {
//echo " num_rows > ";
                                                if(!empty($row['my_prop_id'])) {
                                                 $my_prop_id = $row['my_prop_id'];
                                                 echo "<my_prop_id>"; print_r($my_prop_id);
                                                 }


							/*echo "StandardStatus-->".$record['StandardStatus']."---ListingStatus-->".$record['ListingStatus']; */
							//echo "<pre>"; print_r($record);
			                          if ($record['StandardStatus'] != 'Active' && $record['ListingStatus'] != 'Active') {
                                                        //state, approved, access values are 0
                                                        /* $update_status = "UPDATE ".$prefix."iproperty SET state=0, approved=0, access=0 WHERE mls_id=".$record['ListingID'];
                                                        $update_status_exe = mysqli_query($conn, $update_status); */
                                                        
                                                        echo "OR LISTING NOT ACTIVE";
//echo " not active ";
                                                        $update_status = "DELETE FROM ".$prefix."iproperty WHERE mls_id=".$record['ListingID'];
                                                        $update_status_exe = mysqli_query($conn, $update_status);
                                                        if($update_status_exe){
                                                         echo "This ".$record['ListingID']." MLS Number Property is Not Active. Deleted from our Database - OR";
                                                         }

                                                        $update_status = "DELETE FROM ".$prefix."iproperty_images WHERE propid=".$my_prop_id;
                                                        $update_status_exe = mysqli_query($conn, $update_status);
                                                        if($update_status_exe){
                                                         echo "This my_prop_id=".$my_prop_id." Images Deleted from our Database - OR";
                                                         }

                                                   }
						} else {
//echo " Else state Standard Status:";echo $record['StandardStatus'];echo " ListingStatus:";echo $record['ListingStatus'];echo " ListingID:";echo $record['ListingID'];
							if ($record['StandardStatus'] != 'Active' && $record['ListingStatus'] != 'Active') {
//echo " Not active...continuing ";
								continue;
							}
							echo "MLS-->".$record['ListingID']."--"."StandardStatus-->".$record['StandardStatus']."---ListingStatus-->".$record['ListingStatus']."<br/><br/>";
							$proamenities = array();
							array_push($proamenities, 0);
							for ($i=0; $i < count($keys); $i++) {

								// Fields values
								if($keys[$i] == "City") {
									$city = '';
									if(!empty($record[$keys[$i]])) {
										$get_city = trim(reset(explode(',',$record[$keys[$i]])));
										$sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$get_city ."'";
										$result = mysqli_query($conn, $sql);
//echo " Cityis: "; echo $get_city;

                                                                                if (mysqli_num_rows($result) <= 0) {
                                                                                   $_iproperty_citiesmid['columns'] = "(title, mc_name, state)";
                                                                                   $_iproperty_citiesmid['values'][] = "('".$get_city."','',3962)";

                                                                                   echo "INSERTING CITY: ";echo $get_city;

                                                                                   $values = implode(",", $_iproperty_citiesmid['values']);
                                                                                   $sql = "INSERT INTO ".$prefix."iproperty_cities ".$_iproperty_citiesmid['columns']." VALUES ".$values;
                                                                                   echo "Insert SQL: ";echo $sql;
                                                                                   $inserted_properties = mysqli_query($conn, $sql);
                                                                                   echo "INSERTED_PROPERTIES: ";echo $inserted_properties;

                                                                                   $sql = "SELECT id FROM ".$prefix."iproperty_cities WHERE title='".$get_city ."'";
                                                                                   $result = mysqli_query($conn, $sql);
                                                                                }
										if (mysqli_num_rows($result) > 0) {
											$row = mysqli_fetch_assoc($result);
											if(!empty($row["id"])){
												$city = $row["id"];
												echo $city; echo "<br/><br/>";
											}else{
                                                                                             $city = $get_city;
                                                                                             }
										}else{
                                                                                      $city = $get_city;
                                                                                     }
									}
								}
							}
								//aminities
				                $ami = $record['CoolingDescription'];
				                $ami1 = $record['DiningRoomFeatures'];
				                $ami2 = $record['ExteriorDescription'];
				                $ami3 = $record['ExteriorFeatures'];
				                $ami4 = $record['HeatingDescription'];
				                $ami5 = $record['InteriorFeatures'];
				                $ami6 = $record['KitchenAppliances'];
				                $ami7 = $record['EnergyEfficiencyFeatures'];


				                $re1 = explode(',',$ami1);
				                $re2 = explode(',',$ami2);
				                $re3 = explode(',',$ami3);
				                $re4 = explode(',',$ami4);
				                $re5 = explode(',',$ami5);
				                $re6 = explode(',',$ami6);
				                $re7 = explode(',',$ami7);
				                $re = array_merge($re1,$re2,$re3,$re4,$re5,$re6,$re7);
				                $re[]=$ami;
								$re=array_values(array_diff($re,array("null","")));

					            if(count($re) > 0){
									$amenities_titles = "'".implode("','", $re)."'";
									$sql = "SELECT id FROM ".$prefix."iproperty_amenities WHERE title IN (".$amenities_titles.")";
									$result = mysqli_query($conn, $sql);
									if (mysqli_num_rows($result) >0 ) {
										while($row = mysqli_fetch_assoc($result)){
											if(!empty($row["id"])){
												array_push($proamenities, $row["id"]);
											}
										}
									}
								}
							//echo $record['ListingID']."--->".$record['ListingID']."<br/><br/><br/>";
							//echo "<pre>"; print_r($re);
							$propId++;
							if($data['ClassName'] == 'ResidentialDD'){
								$property_cat = 1;
							} else if($data['ClassName'] == 'LotsAndLandDD'){
								$property_cat = 3;
							} else if($data['ClassName'] == 'CommercialDD'){
								$property_cat = 4;
							} else if($data['ClassName'] == 'MultiFamilyDD'){
								$property_cat = 6;
							} else {$property_cat = 0;}

							$_iproperty['values'][] = "('".str_replace("'", "\'", str_replace("''", '"', $record['ListingID']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['ListingID']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['Latitude']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['Longitude']))."',
												 0,
												 785,
												 3962,
												 '".str_replace("'", "\'", str_replace("''", '"', $city))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['BathsTotal']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['Beds']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['GarageOrParkingSpaces']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['GarageType']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['HOAFee']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['OtherFee']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['HOAYN']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['SchoolElementary']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['SchoolHigh']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['SchoolMiddle']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['SqFtApproximateTotal']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['Subdivision']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['PropertyType']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['YearBuilt']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['FireplacesTotal']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['Stories']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['LotSize']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['Style']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['Area']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['County']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['DateTimeModified']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['DateTimeModified']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['DateTimeModified']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['ListAgentCellPhone']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['ListAgentEmail']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['ListAgentID']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['ListAgentFullName']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['ListOfficeID']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['ListOfficeName']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['LegalDescription']))."',
												 1,
												 231,
												 '".str_replace("'", "\'", str_replace("''", '"', $record['PhotosCount']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['PriceType']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['RemarksPublic']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['StreetName']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['StreetNumber']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['StreetTypeSuffix']))."',
                                                                                                 '".str_replace("'", "\'", str_replace("''", '"', $record['StreetDirPrefix']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['ZipCode']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['Zoning']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['PriceList']))."',
												 '".str_replace("'", "\'", str_replace("''", '"', $record['PropertyType']))."',
												 1)";

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
						}//else means insert data
					}
					$rets->FreeResult($search);

					echo "<br/><br/>".count($_iproperty['values']).' Properties Fetched Successfully';

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
