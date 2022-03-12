<?php 
    include("confs/config.php");


	$categories_select = "SELECT categories.id, categories.name FROM categories";
	$categories_query=mysqli_query($conn,$categories_select);
    $categories=mysqli_fetch_all($categories_query);

    $categoryLists = array();
    $ideacountLists = array();
    $commentcountLists = array();
    $likecountLists = array();
    $dislikecountLists = array();



    foreach($categories as $key => $category){

        $cid = $category[0];
        $cname = $category[1];

        $commenttotal = 0;
    	$idea_ids = array();


        $ideas_query = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM `ideas` WHERE category_id = $cid");
	    $idea_total = mysqli_fetch_array($ideas_query);
	    $idea_total = $idea_total['total_records'];

	    $idea_comment_select ="SELECT ideas.id, COUNT(comments.id) as commenttotal
								FROM ideas 
								LEFT OUTER JOIN comments ON ideas.id = comments.idea_id
								WHERE ideas.category_id = $cid
								GROUP BY comments.idea_id";
		$idea_comment_query=mysqli_query($conn,$idea_comment_select);
    	$idea_comments=mysqli_fetch_all($idea_comment_query);

    		foreach($idea_comments as $ickey => $idea_comment){
        		array_push($idea_ids, $idea_comment[0]);
    			$commenttotal += $idea_comment[1];
    		}

	    $idea_ids = implode("','",$idea_ids);

	    $like_query=$query=mysqli_query($conn, "SELECT count(*) as total FROM reacts WHERE react = 1 AND idea_id IN ('".$idea_ids."')");
	    $like_total = mysqli_fetch_array($like_query);
	    $like_total = $like_total['total'];

	    $dislike_query=$query=mysqli_query($conn, "SELECT count(*) as total FROM reacts WHERE react = 0 AND idea_id IN ('".$idea_ids."')");
    	$dislike_total = mysqli_fetch_array($dislike_query);
	    $dislike_total = $dislike_total['total'];


        array_push($categoryLists, $cname);
        array_push($ideacountLists, $idea_total);
        array_push($commentcountLists, $commenttotal);
        array_push($likecountLists, $like_total);
        array_push($dislikecountLists, $dislike_total);
    }

	$myData  = array(
        "categories" => $categoryLists,
        "ideas"   => $ideacountLists,
        "comments"   => $commentcountLists,
        "likes" => $likecountLists,
        "dislikes"   => $dislikecountLists

    );

	echo json_encode($myData);
?>