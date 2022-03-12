<?php 
	require 'header.php';
    include("confs/config.php");

    $id=$_GET['id'];
    $select="SELECT
            users.*,
            positions.name AS pname,
            departments.name AS dname
        FROM
            users
        LEFT JOIN position_user ON users.id = position_user.user_id
        LEFT JOIN positions ON position_user.position_id = positions.id
        LEFT JOIN departments ON positions.department_id = departments.id
        WHERE users.id = $id
        ";
    $query=mysqli_query($conn,$select);
    $user_numrows=mysqli_num_rows($query);

    if($user_numrows > 0){
        $data = mysqli_fetch_array($query);

        $id=$data['id'];
        $name=$data['name'];
        $profile=$data['profile'];
        $email=$data['email'];
        $jod=$data['joindate'];
        $dob=$data['dob'];
        $status=$data['status'];
        $pname=$data['pname'];
        $dname=$data['dname'];
        $gender=$data['gender'];
        $phone=$data['phone'];
        $address=$data['address'];

        if ($status == 'Active') {
            $statusTag = "<span class='badge bg-label-primary'>CURRENT</span>";
        }else{
            $statusTag = "<span class='badge bg-label-warning'>RESIGNED</span>";

        }

    } else{
        header("location: 404.php");
    }

?>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require 'app_sidebar.php' ?>

            <div class="layout-page">
                <?php require 'app_header.php' ?>

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row mb-3">
                        <div class="col-10">
                            <h5 class="d-inline-block">Staff Detail</h5>
                        </div>
                        <div class="col-2">
                            <a href="staff_list.php" class="btn btn-primary"> 
                                <i class='bx bx-chevrons-left me-1' ></i>  Go Back
                            </a>
                        </div>
                    </div>

                    <div class="row g-4">

                        <div class="col-xl-4 col-lg-5 col-md-5 col-sm-12 col-12">
                            <div class="card mb-4 h-100">
                                <div class="card-body">

                                    <div class=" d-flex align-items-center flex-column">
                                        <img src="<?= $profile; ?>" class="img-fluid rounded my-4">
                                    </div>
                                    <div class="text-center">
                                        <h4 class="mb-2"> <?= $name; ?> </h4>
                                        <span class="badge bg-label-secondary"> <?= $pname; ?> </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8 col-lg-7 col-md-7 col-sm-12 col-12">
                            <div class="card mb-4 h-100">
                                <div class="card-body">
                                    <h5 class="pb-2 border-bottom mb-4"> Details </h5>
                                    <div class="info-container">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-bold me-2"> Email : </span>
                                                <span> <?= $email; ?> </span>
                                            </li>

                                            <li class="mb-3">
                                                <span class="fw-bold me-2"> Department : </span>
                                                <span> <?= $dname; ?> </span>
                                            </li>

                                            <li class="mb-3">
                                                <span class="fw-bold me-2"> Gender : </span>
                                                <span> <?= $gender; ?> </span>
                                            </li>

                                            <li class="mb-3">
                                                <span class="fw-bold me-2"> Contact : </span>
                                                <span> <?= $phone; ?> </span>
                                            </li>

                                            <li class="mb-3">
                                                <span class="fw-bold me-2"> Address : </span>
                                                <span> <?= $address; ?> </span>
                                            </li>

                                            <li class="mb-3">
                                                <span class="fw-bold me-2"> Date of Birth : </span>
                                                <span> <?= date('M d Y',strtotime($dob)); ?> </span>
                                            </li>

                                            <li class="mb-3">
                                                <span class="fw-bold me-2"> Joined Date : </span>
                                                <span> <?= date('M d Y',strtotime($jod)); ?> </span>
                                            </li>

                                            <li class="mb-3">
                                                <span class="fw-bold me-2"> Status : </span>
                                                <span> <?= $statusTag; ?> </span>
                                            </li>
                                            

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>


                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>



<?php 
	require 'footer.php';
?>
