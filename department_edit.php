<?php 
    include("confs/config.php");

    $name=$_POST['name'];
    $id=$_POST['id'];

    
    $departments_sql = "UPDATE departments SET name='$name' WHERE id='$id' ";
    mysqli_query($conn, $departments_sql);

    $array = array(
        'status' => 'Success',
        'msg' => 'Saved!'
    );
        

    echo json_encode($array);


?>