<?php 
    include("confs/config.php");


	$categories_select = "SELECT categories.*, COUNT(ideas.id) as ideatotal
                            FROM categories 
                            LEFT OUTER JOIN ideas ON categories.id = ideas.category_id
                            GROUP BY ideas.category_id";
	$categories_query=mysqli_query($conn,$categories_select);
    $categories=mysqli_fetch_all($categories_query);


    foreach($categories as $key => $category){

        $start = date("Y-m-d", strtotime($category[5]));
        $end = date("Y-m-d", strtotime($category[3]));


        $data[] = array(
            'id'        => $category[0],
            'title'     => $category[1],
            'start'     => $start,
            'end'       => $end,
            'backgroundColor' =>  $category[2],
            'borderColor' =>  $category[2],
         );

    }

	echo json_encode($data);
?>