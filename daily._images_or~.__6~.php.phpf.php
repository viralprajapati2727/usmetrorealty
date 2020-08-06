<?php
    require_once("phrets.php");
    require_once("configuration.php");
    $config = new JConfig();
    $prefix = $config->dbprefix;
    $conn = mysqli_connect($config->host, $config->user, $config->password, $config->db);
    if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

    $rets_login_url = "http://rets.rmlsweb.com:6103/rets/login";
    $rets_username = "DS04US51";
    $rets_password = "DS0807US";

    date_default_timezone_set('US/Arizona');
    $date = date('Y-m-d')."+";
    $date2 = date('h:i:s');
    echo "OR Images starting at:"; echo $date2;


    $rets = new phRETS;
    $connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);
    if (!$connect) {
        print_r($rets->Error()); exit;
    }

    $_iproperty_images = array();

    /*$sql1 = "SELECT * FROM  `set_oregon_images_offset` ORDER BY id LIMIT 1";
    $result1 = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result1) > 0) {
        $row = mysqli_fetch_assoc($result1);
        $offset = $row['offset_number'];
        $set_offset_id = $row['id'];
    }*/

    $sql = "SELECT int_list_id, id FROM  ".$prefix."iproperty WHERE locstate=3962 AND for_picture = 0 LIMIT 0, 100" ;
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        //echo ' rows found=';echo (mysqli_num_rows($result));
       $count = 0;
        $total_photos = 0;
        $records_updated_count = 0;
        while($row = mysqli_fetch_assoc($result)){
            echo "WHILE <row int_list_id>"; print_r($row['int_list_id']);
            $skipProperty=false;
            $propId = $row['id'];
            //echo " <propId>"; print_r($propId);
            $photo_count = 0;
            $photos = '';
            if(!empty($row['int_list_id'])){
                $photos = $rets->GetObject("Property", "Photo", $row['int_list_id'], "*", 1);
            }else {
                    echo "<int_list_id is empty-delete this record>";
                    $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$propId;
                    $update_status_exe = mysqli_query($conn, $update_status);
                    //echo " <update_status_exe>"; print_r($update_status_exe);
            }
            echo "<processing photos>"; 
            $ch = "SELECT `propid` FROM  ".$prefix."iproperty_images WHERE 'propid' =".$propId;
            $check = mysqli_query($conn, $ch);
            $check_result = mysqli_fetch_assoc($check);
            if(empty($check_result)){
                //echo " <empty check_result>"; //print_r($check_result);
                if(count($photos) > 0){ //kalim changed it to >0 from >1
                $ordering = 0;
                $my_values = array();
                $insert_photos = 0;
                foreach ($photos as $photo) {
                    //echo " <processing each photo>"; //print_r($photo);
                    if($insert_photos >= 20){
                            //break; //AZ only processes 20 pics - activate if needed for OR
                        }
                    $insert_photos++;
                    $photo = (array)$photo;
                    $data = array();
                    $elemI = 0;
                    foreach ($photo as $key => $value) {
                        //echo " <key>"; print_r($key);
                        //echo " <value>"; print_r($value);
                        $elemI++;
                        $title = '';
                        $file_ext = '';
                        $path = '';
                        $fname = '';
                        if($elemI == 5){ //element 5=location (file location path)
                            $filenames = explode('.', basename($value, ".").PHP_EOL);
                            $fileI = 0;
                            foreach ($filenames as $filename) {
                                $fileI++;
                                if($fileI == count($filenames)){
                                    $type = trim('.'.$filename);
                                } else {
                                    $fname .= $filename;
                                }
                            }
                            $slashes = explode('/', $value);
                            $slashI = 0;
                            foreach ($slashes as $slash) {
                                $slashI++;
                                if($slashI < count($slashes)){
                                    $path .= $slash.'/';
                                }
                            }
                        } else if($elemI == 6){
                            $title = $value;
                            //echo " <setting title>"; print_r($title);
                        }
                        if(!empty($fname) && !empty($type) && !empty($path)){
                            //echo " <fname>"; print_r($fname);
                            //echo " <type>"; print_r($type);
                            //echo " <path>"; print_r($path);
                            //echo " <title>"; print_r($title);

                            $file_name = "'$fname'";
                            $ch = "SELECT `fname` FROM  `".$prefix."iproperty_images` WHERE `fname` =".$file_name;
                            $check = mysqli_query($conn, $ch);
                            $c = mysqli_fetch_assoc($check);
                            if($c['fname']){
                                //echo " <fname is not empty skipping>";print_r($c['fname']);
                                    echo " <fname is not empty DELETING property for file:>";print_r($c['fname']);
                                    //continue;
                                    $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$propId;
                                    $update_status_exe = mysqli_query($conn, $update_status);
                                    //echo " <deleting this propId:>";print_r($propId);
                                    $skipProperty=true;
                                    break;
                            }
                            // echo $path."<br/><br/>";
                            $imgvalues = array($propId, "'".$title."'", "'".$fname."'", "'".$type."'", 1, "'".$path."'", 785, "'".$ordering."'", 1);
                            $my_values[$ordering] = "(".implode(",", $imgvalues).")";
                            $total_photos++;
                            //echo " <collecting imgvalues>";print_r($imgvalues);
                            echo " <total_photos>";print_r($total_photos);
                        } else {
                            //echo " <fname, type path empty>";
                            //do not need to log anything here  
                        }
                        
                    }
                    if($skipProperty){echo " <breaking again>";break;}
                    $ordering++;
                }
                if($skipProperty){echo " <continuing this time>";continue;} //
                
                    $img_values = implode(",", $my_values);
                    $_iproperty_images['columns'] = "(propid, title, fname, type, remote, path, owner, ordering, state)";
                    $_iproperty_images['values'][$photo_count++] = $img_values;
                    //echo " <columns>"; print_r($_iproperty_images['columns']);
                    //echo " <img_values>"; print_r($img_values);
                }else {
                    echo " <photos count NOT more then 0>"; print_r(count($photos));
                    //Just delete this record also from the iproperty db
                }
                
                $values = implode(",", $_iproperty_images['values']);
                
                if(empty($values)){
                    //empty values - delete this record.
                    echo " <values empty deleting>";
                    $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$propId;
                    $update_status_exe = mysqli_query($conn, $update_status);
                    //echo " <deleting this propId:>";print_r($propId);
                } else {
	            //echo $values."<br/><br/>";
	            //echo " <inserting values>"; //print_r($values);
	            $sql = "INSERT INTO ".$prefix."iproperty_images ".$_iproperty_images['columns']." VALUES ".$values;
	            $inserted_properties = mysqli_query($conn, $sql);
	                
	            echo " <inserted_properties>";
	                
	            if($inserted_properties){
	                //echo " <inserted_properties updating>";
	                $sql = "UPDATE ".$prefix."iproperty SET for_picture = 1 WHERE id=".$propId;
	                mysqli_query($conn, $sql);
	                $records_updated_count++;
	            } else {
	                echo " <inserted_properties not true>"; 
	            }
                }         
                
  
            $count++;
            } else {
                //Kalim Added this else statement - >>>>actually delete this record if already exists...instead of update
                echo " <Property already exists in images db - delete from iproperty table>"; 
                $update_status = "DELETE FROM ".$prefix."iproperty WHERE id=".$propId;
                $update_status_exe = mysqli_query($conn, $update_status);
                //echo " <deleting this propId:>";print_r($propId);
            }
        }
    } else {
            echo "There are no more images for properties please close this cron and execute the cron 'Check and insert properties of today'";exit;
        }
    if($count > 0){
        echo " Count before updating offset=".$count;
        $sql = "SELECT offset_number FROM set_oregon_images_offset WHERE id = 1 ";
            $result = mysqli_query($conn, $sql);
            echo " <offset result>"; print_r($result);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $offset = $row['offset_number'];
            }
            $sql = "UPDATE set_oregon_images_offset SET offset_number = ".($offset+$count)." WHERE id=1 ";
            $resultQ2 = mysqli_query($conn, $sql);
            echo " <resultQ2>"; print_r($resultQ2);
    }
    echo " ".$count.' Properties<br/>'.$total_photos." Total Photos ".$records_updated_count." Updated Count";

    mysqli_close($conn);
?>




















