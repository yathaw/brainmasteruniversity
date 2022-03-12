<?php 
    include("confs/config.php");

	session_start();

	$id = $_POST['id'];
	$status = 'Inactive';
	$users_sql = "UPDATE users SET status='$status' WHERE id='$id' ";
	mysqli_query($conn, $users_sql);

	echo 'Data Inserted';

?>