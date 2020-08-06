<?php
        require_once("phrets.php");
        require_once("configuration.php");
        $config = new JConfig();
        $prefix = $config->dbprefix;
        // Create connection
        $conn = mysqli_connect($config->host, $config->user, $config->password, $config->db);
        if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

        //$rets_login_url = 'http://retsgw.flexmls.com:6103/rets2_3/Login';
        $rets_login_url = "http://retsgw.flexmls.com/rets2_3/Login";
        $rets_username = 'az.rets.usmr01b';
        $rets_password = 'puces-uronic23';

        date_default_timezone_set('US/Arizona');
        $date = date('Y-m-d')."+";
        $date2 = date('h:i:s');
        echo "Cleanup starting at:"; echo $date2;

        $rets = new phRETS;
        $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
        if (!$connect) {
            print_r($rets->Error()); exit;
        }

        $_iproperty_images = array();

        /*$sql1 = "SELECT * FROM  `set_images_offset` ORDER BY id LIMIT 1";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) > 0) {
            $row = mysqli_fetch_assoc($result1);
            $offset = $row['offset_number'];
            $set_offset_id = $row['id'];
        }*/

		
	$limit_count=0;
	while($limit_count<6){
        //$sql = "SELECT int_list_id, id FROM  ".$prefix."iproperty WHERE locstate=3921 AND for_picture = 0 LIMIT 0, 100";
	    //$sql = "SELECT int_list_id, id FROM  ".$prefix."iproperty WHERE locstate=3962 AND created < '2018-01-01'" ;
        $sql = "SELECT int_list_id, id, mls_id FROM  ".$prefix."iproperty WHERE locstate=3921 LIMIT ".$limit_count.", 5";
		$limit_count=$limit_count+5;
        //echo $sql;

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo ' ****Rows found=';print_r(mysqli_num_rows($result));

            $count = 0;
            $records_updated_count = 0;
			
            while($row = mysqli_fetch_assoc($result)){
                //echo "WHILE <row int_list_id>"; print_r($row['int_list_id']);
                //echo "<row id>"; print_r($row['id']);
                echo "<***row mls_id>"; print_r($row['mls_id']);

                $propId = $row['id'];
				$mls_id=$row['mls_id'];
                //echo " <propId>"; print_r($propId);

                if(!empty($row['int_list_id'])){
					
					//Need to check the existance of a property
                    //$status = $rets->GetObject("Property", "LIST_15", $row['int_list_id'], "*", 1);
					//echo " <status>"; print_r($status);
					
					//*****************************
					//$query = "(ListPrice=ListPr_276),(ModificationTimestamp=LastTr_260),(ListingStatus=Status_383),({$rets_modtimestamp_field}={$previous_start_time}+)";

					//$query = "(LIST_87=".$date.")";
					//$query = "(LIST_87=2017-01-05+)";
					//$query = "(LIST_105=2017-01-05+)"; //LIST_105 = mls_id  ---- int_list_id=mls_id  ---use mls_id now getting it
					
					
					//$query = "((DateTimeModified=2017-06-01+),(PhotoDateTimeModified=2017-06-01+),(ListingStatus=|ACT,BMP,SSP,PEN,POP))";
						   
					//(Status=|ACT,SOLD)

					//$query = "(LIST_105=".$mls_id.")"; //LIST_105 = mls_id , LIST_15=status
					//$query = "((LIST_105=".$mls_id."),(LIST_15=Active))"; //LIST_105 = mls_id , LIST_15=status
					$query = "((LIST_105=".$mls_id."),(ListingStatus=|ACT,CAN,BMP,SSP,PEN,POP,SLD))"; //LIST_105 = mls_id , LIST_15=status
					//echo "<query>"; print_r($query);
					
					
					//$timestamp_field = 'LIST_87';
					$property_classes = ['A', 'B', 'C', 'D', 'E', 'F'];

					$record_found=0;
					foreach ($property_classes as $pc) {
						// generate the DMQL query
						//$query = "({$timestamp_field}=2000-01-01T00:00:00+)";

						// make the request and get the results
						//$results = $rets->Search('Property', $pc, $query);
						$record = $rets->Search('Property', $pc, $query);
						//echo "<<<<record>"; print_r($record);
						
						 if((count($record))>0){ 
						 //if ($record['StandardStatus'] = '') {
							$record_found++;

							if ($record['StandardStatus'] = 'Active') {
								echo "<listing active>"; print_r($record['StandardStatus'] );
							}else{
								 echo "<listing NOT active--delete record>"; print_r($record['StandardStatus'] );
							}
							break;

						}else{
							echo "<record not found>"; print_r(count($record));
						}
						
						
	                    //echo "<Results>"; print_r($results);

						// save the results in a local file
						//file_put_contents('data/Property_' . $pc . '.csv', $results->toCSV());
					}
					if($record_found<=0){
						//delete this record
						echo "<zero count----delete MLS>".$mls_id;
					}
					
					
/*					
					$types = $rets->GetMetadataTypes();
					foreach ($types as $type) {
						if($type['Resource'] == "Property"){
							
							foreach ($type['Data'] as $data) {
									$fields = $rets->GetMetadataTable($type['Resource'], $data['ClassName']);
									$results = $rets->Search($type['Resource'], $data['ClassName'] ,$query, array("Limit" => $limit, "Offset"=> $offset));
				                   echo "<Types >"; print_r($type);
				                   echo "<Fields >"; print_r($fields);
				                    echo "<Results >"; print_r($results);
									//$keys = array();
									//	foreach ($fields as $key) {
									//		$keys[] = $key['SystemName'];
									//	}
									

				                    //echo "<data[ClassName]>"; print_r($data['ClassName'] );
				                    //echo "<data[LIST_15]>"; print_r($data['LIST_15'] );

									if($data['ClassName'] == 'A'){
										echo "ClassName=A";
										foreach ($results as $record) {
											echo "<br> after records foreach";
											echo "<record[LIST_15]>"; print_r($record['LIST_15'] );
											if ($record['LIST_15'] != 'Active') {
											   echo "AZ A LISTING NOT ACTIVE";
											}
										}
									}
							


							

							}
						}
					}
					
*/
					
					
					
					
					//*****************************
					
					
					
					
                } else {
                    echo "<int_list_id is empty-delete this record>";
                    //$update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$propId;
                    //$update_status_exe = mysqli_query($conn, $update_status);
                    
					//echo " <update_status_exe>"; print_r($update_status_exe);
					
					//also delete from other tables
					
					
                }

                $count++;
			}
        } else {
            echo "There are no more property records";exit;
        }


	}//infinative while
 		
		
		
		





        if($count > 0){
			echo " Count before updating offset=".$count;
        }
        echo " ".$count.' Properties<br/>'.$total_photos." Total Photos ".$records_updated_count." Updated Count";

        mysqli_close($conn);
?>