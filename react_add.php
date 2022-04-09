<?php 
    include("confs/config.php");

	session_start();

	$react = $_POST['react'];	
	$ideaid = $_POST['ideaid'];
	$userid = $_SESSION['sess_user']['id'];
	$status = $_POST['status'];

	if($status == 'react'){

		$reacts_sql = "INSERT INTO reacts(react, idea_id, user_id) VALUES 
			    ('$react','$ideaid','$userid')";
		mysqli_query($conn, $reacts_sql);

		$reactid = $conn->insert_id;

		header("location:mail/react.php/?id=$reactid");

	}else{

		$react_sql = "SELECT * FROM reacts WHERE idea_id = '$ideaid' AND user_id = '$userid' AND react = '$react' ";
		$result = mysqli_query($conn, $react_sql);
    	$react = mysqli_fetch_array($result);

    	$reactid = $react['id'];

    	$reactdelete_sql="DELETE FROM reacts WHERE id=$reactid";
		mysqli_query($conn,$reactdelete_sql);

	}

	echo mysqli_error($conn);
	echo 'Data Inserted';

?>