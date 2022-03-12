<?php 
    include("confs/config.php");


	$categories_select = "SELECT categories.*, COUNT(ideas.id) as ideatotal
                            FROM categories 
                            LEFT OUTER JOIN ideas ON categories.id = ideas.category_id
                            GROUP BY ideas.category_id";
	$categories_query=mysqli_query($conn,$categories_select);
    $categories=mysqli_fetch_all($categories_query);


    foreach($categories as $key => $category){

        $data[] = array(
            'id'        => $category[0],
            'title'     => $category[1],
            'start'     => $category[5],
            'end'       => $category[3]
         );

    }

	echo json_encode($data);
?>