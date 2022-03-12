<?php 
    include("confs/config.php");

	session_start();

	$id = $_POST['commentid'];
	$delete="DELETE FROM comments WHERE id=$id";
	$query=mysqli_query($conn,$delete);

	echo 'Data Inserted';

?>