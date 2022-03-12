<?php 
    include("confs/config.php");

	session_start();

	$id = $_POST['id'];
	$delete="DELETE FROM categories WHERE id=$id";
	$query=mysqli_query($conn,$delete);

	echo 'Data Inserted';

?>