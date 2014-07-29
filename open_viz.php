<?php
	
	session_start();

	$allowedExts = array("viz");
	$temp = explode(".", $_FILES["viz_file"]["name"]);
	$extension = end($temp);
	if (($_FILES["viz_file"]["size"] < 20000)
		&& in_array($extension, $allowedExts)) {
	  if ($_FILES["viz_file"]["error"] > 0) {
		echo "Error: " . $_FILES["viz_file"]["error"] . "<br>";
	  } else {
	  	$viz_file = file_get_contents($_FILES["viz_file"]["tmp_name"]);
	  	$_SESSION["new_upload"] = true;
	  	$_SESSION["viz_file"] = $viz_file;
	  }
	}
	
	header( 'Location: index.php' ) ;
?>