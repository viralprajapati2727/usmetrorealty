<?php
//phpinfo();
error_reporting(E_ALL);

ini_set('display_errors', true);

   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      //$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      $value = explode(".", $_FILES['image']['name']);
      $file_ext = strtolower(array_pop($value));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      $target_path = "media/";
      if(empty($errors)){
         if (!is_dir($target_path)) {
           echo '<div>Debug: ', $target_path, ' is not a directory', "<div />\n";
         }
         if (!is_writable($target_path)) {
            echo '<div>Debug: ', $target_path, ' is not writable', "<div />\n";
         }
         if (!is_file($_FILES['image']['tmp_name'])) {
            echo '<div>Debug: ', $_FILES['image']['tmp_name'], ' file not found', "<div />\n";
         } else {
            $imageData = file_get_contents($_FILES['image']['tmp_name']); // path to file like /var/tmp/...

			// display in view
			echo sprintf('<img src="data:image/png;base64,%s" />', base64_encode($imageData));
            $uploadfile =basename($_FILES['image']['name']);
            if(move_uploaded_file($file_tmp, "media/$uploadfile")){
               echo "successfilly uploaded image.";
            } else {
               echo "fail"; exit;
            }
         }
      }else{
         print_r($errors);
      }
   }
?>
<html>
   <body>
      
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" />
         <input type="submit"/>
      </form>
      
   </body>
</html>











