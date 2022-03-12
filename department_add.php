<?php 
    include("confs/config.php");

    $name=$_POST['name'];

    $department_query=mysqli_query($conn,"SELECT * FROM departments WHERE name LIKE '%$name%' ");  
    $department_numrows=mysqli_num_rows($department_query); 
    if($department_numrows <= 0){
        
        $departments_sql = "INSERT INTO departments(name) VALUES ('$name')";
        mysqli_query($conn, $departments_sql);

        $array = array(
            'status' => 'Success',
            'msg' => 'Saved!'
        );
        
    } 
    else{ 
        $array = array(
            'status' => 'Error',
            'msg' => 'Sorry! The department name already exists!'
        );
    }

    echo json_encode($array);


?>