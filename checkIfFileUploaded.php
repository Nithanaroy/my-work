<?php 
	$filename = $_GET['filename'];
	$exists = file_exists($filename);
	if($exists)
		echo filesize($filename);
	else
		echo "0";
?>