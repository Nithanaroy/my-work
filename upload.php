<?php
$headers = getallheaders ();
$protocol = $_SERVER [SERVER_PROTOCOL];
$file = new stdClass ();
$file->name = basename($headers['X-File-Name']);
$file->size = $headers['X-File-Size'];
 
$file->content = file_get_contents('php://input');
echo "Submitted file name: ".$file->name;
$flag = ($headers['X-File-IsNew'] == 1 ? 0: FILE_APPEND);
file_put_contents ( $file->name, $file->content, $flag);
?>