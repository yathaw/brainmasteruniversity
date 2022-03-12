<?php 
    include("confs/config.php");

	$id = $_POST['id'];
	$delete="DELETE FROM positions WHERE id=$id";
	$query=mysqli_query($conn,$delete);

	echo 'Data Inserted';

?>