<?php 
    include("confs/config.php");
	
	$categoryid = $_POST['categoryid'];

	$topideas_select = "SELECT ideas.title, ideas.id, COUNT(comments.id) as commenttotal
						FROM ideas 
						LEFT OUTER JOIN comments ON ideas.id = comments.idea_id
						WHERE ideas.category_id = $categoryid
						GROUP BY comments.idea_id";
	$topideas_query=mysqli_query($conn,$topideas_select);
    $topideas=mysqli_fetch_all($topideas_query);

    $analytics = array();

    $titles = array();
    $comments = array();
    $likes = array();
    $dislikes = array();

    
    foreach($topideas as $key => $topidea){

        $topidea_title = $topidea[0];
        $topidea_id = $topidea[1];
        $topidea_commenttotal = $topidea[2];

        $like_select="SELECT * FROM reacts WHERE react = 1 AND idea_id = '$topidea_id'";
	    $like_query=mysqli_query($conn,$like_select);
	    $like_total=mysqli_num_rows($like_query);

	    $dislike_select="SELECT * FROM reacts WHERE react = 0 AND idea_id = '$topidea_id'";
	    $dislike_query=mysqli_query($conn,$dislike_select);
	    $dislike_total=mysqli_num_rows($dislike_query);

	    $newdata =  array (
	      	'idea_id' => $topidea_id,
	      	'commenttotal' => $topidea_commenttotal,
	      	'liketotal' => $like_total,
	      	'disliketotal' => $dislike_total,

	    );

	    if (strlen($topidea_title) > 30){
            $topidea_title = substr($topidea_title, 0, 30).'...';
        }
        else{
            $topidea_title = $topidea_title;
        }

        array_push($analytics, $newdata);

        array_push($titles, $topidea_title);
        array_push($comments, $topidea_commenttotal);
        array_push($likes, $like_total);
        array_push($dislikes, $dislike_total);

    }

    $myData  = array(
        "titles" => $titles,
        "comments"   => $comments,
        "likes"   => $likes,
        "dislikes"   => $dislikes,

    );

	echo json_encode($myData);


?>