<?php 
    include("confs/config.php");

	session_start();

	$comment = $_POST['comment'];
	$comment = mysqli_escape_string($conn, $comment);	
	
	$status = $_POST['status'];
	$ideaid = $_POST['ideaid'];
	$userid = $_SESSION['sess_user']['id'];
	$id = $_POST['commentid'];

	if($id){
		var_dump($status);
		$comments_sql = "UPDATE comments SET body='$comment', status='$status', idea_id='$ideaid', user_id='$userid' WHERE id='$id'";
		mysqli_query($conn, $comments_sql);
	}else{
		$comments_sql = "INSERT INTO comments(body, status, idea_id, user_id) VALUES 
			    ('$comment','$status','$ideaid','$userid')";
		mysqli_query($conn, $comments_sql);
	}

	$commentid = $conn->insert_id;

	header("location:mail/comment.php/?id=$commentid");

	echo mysqli_error($conn);
	echo 'Data Inserted';

?>