<?php 
    include("confs/config.php");

    $cid = $_GET['id'];
	
	$category_query=mysqli_query($conn,"SELECT * FROM categories WHERE id='$cid'");  
	$category=mysqli_fetch_array($category_query);

	$categoryname = $category['name'];

	// Fetch records from database 
	$latest_ideas_select="SELECT ideas.*, categories.id as cid, categories.name as cname, categories.color as ccolor, users.name as uname, users.profile as uprofile, positions.name as pname from ideas 
            LEFT JOIN categories ON ideas.category_id = categories.id
            LEFT JOIN users ON ideas.user_id = users.id
            LEFT JOIN position_user ON users.id = position_user.user_id
            LEFT JOIN positions ON position_user.position_id = positions.id
            WHERE ideas.category_id = $cid
            ORDER BY ideas.created_at DESC";
    $latest_ideas_query=mysqli_query($conn,$latest_ideas_select);
    $latest_ideas_numrows=mysqli_num_rows($latest_ideas_query);

    if($latest_ideas_numrows > 0){
	    $latest_ideas=mysqli_fetch_all($latest_ideas_query);

	    $delimiter = ","; 
	    $filename = "$categoryname-idea-data_" . date('Y-m-d') . ".csv"; 
	     
	    // Create a file pointer 
	    $f = fopen('php://memory', 'w'); 
	     
	    // Set column headers 
	    $fields = array('#', 'Title', 'Body', 'Like', 'Dislike', 'Created By', 'Created Date'); 
	    fputcsv($f, $fields, $delimiter); 
		$i=1;
		foreach($latest_ideas as $latest_idea){
			$latest_idea_id = $latest_idea[0];
	        $latest_idea_name = $latest_idea[1];
	        $latest_idea_body = $latest_idea[2];
	        $latest_idea_file = $latest_idea[3];
	        $latest_idea_category_id = $latest_idea[4];
	        $latest_idea_user_id = $latest_idea[5];
	        $latest_idea_status = $latest_idea[6];
	        $latest_idea_created_at = date("d M, Y", strtotime($latest_idea[7]));
	        
	        $latest_idea_category_id = $latest_idea[9];
	        $latest_idea_category_name = $latest_idea[10];
	        $latest_idea_category_color = $latest_idea[11];

	        $latest_idea_user_name = $latest_idea[12];
	        $latest_idea_user_profile = $latest_idea[13];
	        $latest_idea_position = $latest_idea[14];

	        if ($latest_idea_status == "on") {
	        	$user = 'Anonymous';
	        }else{
	        	$user = $latest_idea_user_name;
	        }
	        $latest_idea_body_decode = html_entity_decode($latest_idea_body);
	        $latest_ideas_body_removeHTMLtag = strip_tags($latest_idea_body_decode);

	        $like_select="SELECT * FROM reacts WHERE react = 1 AND idea_id = '$latest_idea_id'";
		    $like_query=mysqli_query($conn,$like_select);
		    $idea_like_total=mysqli_num_rows($like_query);

		    $dislike_select="SELECT * FROM reacts WHERE react = 0 AND idea_id = '$latest_idea_id'";
		    $dislike_query=mysqli_query($conn,$dislike_select);
		    $idea_dislike_total=mysqli_num_rows($dislike_query);

		    $lineData = array($i++, $latest_idea_name, $latest_ideas_body_removeHTMLtag, $idea_like_total, $idea_dislike_total, $user, $latest_idea_created_at); 
		    
		    fputcsv($f, $lineData, $delimiter); 
		    
		}
		// Move back to beginning of file 
	    fseek($f, 0); 
	     
	    // Set headers to download file rather than displayed 
	    header('Content-Type: text/csv'); 
	    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
	     
	    //output all remaining data on a file pointer 
	    fpassthru($f); 

		exit; 
	}
?>