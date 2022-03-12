<?php 
	require 'header.php';
    include("confs/config.php");

?>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require 'app_sidebar.php' ?>

            <div class="layout-page">
                <?php require 'app_header.php' ?>

                <div class="container-xxl flex-grow-1 container-p-y">
                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="d-inline-block">Staff List</h5>
                        </div>
                        <div class="card-body px-3">
                            <div class="table-responsive text-nowrap">
                                <table class="table" id="sampleTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th> 
                                            <th> Experience </th>
                                            <th> Status </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php 
                                            $select="SELECT
                                                users.*,
                                                positions.name AS pname,
                                                departments.name AS dname
                                            FROM
                                                users
                                            LEFT JOIN position_user ON users.id = position_user.user_id
                                            LEFT JOIN positions ON position_user.position_id = positions.id
                                            LEFT JOIN departments ON positions.department_id = departments.id
                                            ORDER BY positions.id
                                            ";
                                            $query=mysqli_query($conn,$select);
                                            $count=mysqli_num_rows($query);
                                            if ($count>0)
                                            {
                                                $number =1;
                                                for ($i=0; $i<$count; $i++)
                                                {
                                                    $data=mysqli_fetch_array($query);
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
                                                    $today = date('Y-m-d');

                                                    $date_diff = abs(strtotime($today) - strtotime($jod));

                                                    $years = floor($date_diff / (365*60*60*24));
                                                    $months = floor(($date_diff - $years * 365*60*60*24)/ (30*60*60*24));

                                                    if($years){
                                                        $experience = $years." Years";
                                                    }else{
                                                        $experience = $months." Months";

                                                    }

                                            ?>

                                            <tr>
                                                <td> <?= $number++; ?>. </td>
                                                <td>
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <div class="avatar-wrapper">
                                                            <div class="avatar me-2">
                                                                <img src="<?= $profile; ?>" class="rounded-circle">
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-truncate"> <?= $name; ?> </span>
                                                            <small class="text-truncate text-muted">
                                                                <?= $pname; ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td> <?= $email; ?> </td>
                                                <td>
                                                    <?= $experience; ?>
                                                </td>
                                                <td> <?= $statusTag; ?> </td>
                                                
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item text-primary detailBtn" href="javascript:void(0)" data-name="<?= $name; ?>" data-profile="<?= $profile; ?>" data-email="<?= $email; ?>" data-jod="<?= $jod; ?>" data-dob="<?= $dob; ?>" data-status="<?= $status; ?>" data-pname="<?= $pname; ?>" data-dname="<?= $dname; ?>" data-experience="<?= $experience; ?>" data-gender="<?= $gender; ?>" data-phone="<?= $phone; ?>" data-address="<?= $address; ?>">
                                                                <i class='bx bxs-user-detail' ></i> Details     
                                                            </a>
                                                            <a class="dropdown-item text-danger removeBtn" href="javascript:void(0)" data-id="<?= $id; ?>">
                                                                <i class='bx bx-user-x' ></i> Resigned 
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                        <?php
                                                }
                                            }
                                        ?>
                                        
                                    </tbody>
                                </table>
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


<div class="modal fade" id="detailModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                        <div class=" d-flex align-items-center flex-column">
                            <img src="" class="img-fluid rounded my-4" id="detailProfile">
                        </div>
                        <div class="text-center">
                            <span class="badge bg-label-secondary" id="detailPname"></span>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mb-3">
                        <h5 class="pb-2 border-bottom mb-4"> Details </h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold me-2"> Email : </span>
                                    <span id="detailEmail"></span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-bold me-2"> Department : </span>
                                    <span id="detailDname"></span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-bold me-2"> Gender : </span>
                                    <span id="detailGender"></span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-bold me-2"> Contact : </span>
                                    <span id="detailPhone"></span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-bold me-2"> Address : </span>
                                    <span id="detailAddress"></span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-bold me-2"> Date of Birth : </span>
                                    <span id="detailDob"></span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-bold me-2"> Joined Date : </span>
                                    <span id="detailJod"></span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-bold me-2"> Status : </span>
                                    <span id="detailStatus"></span>
                                </li>
                                

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Close </button>
            </div>
        </form>
    </div>
</div>


<?php 
	require 'footer.php';
?>

<script type="text/javascript">

    $('.detailBtn').on('click', function(e) {
        var id = $(this).data("id");
        window.location.href = "staff.php?id="+id;

    });

    
    $('.removeBtn').on('click', function(e) {
        var id = $(this).data("id");

        Swal.fire({
            title: 'You are about to delete this staff',
            html: 'This will delete your list. <br> Are you Sure?',
            icon: "question",
            showCancelButton:true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',  
            confirmButtonColor: '#d33',
            cancelButtonColor: '#808080',
            reverseButtons: true,
            dangerMode: true}).then((willDelete)=>{
                console.log(willDelete);
                console.log(willDelete.isConfirmed);

                if (willDelete.isConfirmed != false) 
                {
                    $.ajax({
                        url:'staff_delete.php',
                        data:{id:id},
                        type:'POST',
                        success:function(data){
                            console.log(data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Staff removed successfully!',
                                showConfirmButton: false,
                                timer: 2000,
                                allowOutsideClick: false

                            }).then(function(){
                                window.location.reload();

                            })

                        },
                        error:function(data){
                            //Error Message == 'Title', 'Message body', Last one leave as it is
                            // swal("Oops...", "Something went wrong :(", "error");
                        }
                    });
                    
                }
                else
                {
                    Swal.fire({
                        icon: "info",
                        title: "Changes are not saved",
                        showConfirmButton: false,
                        timer : 1500,
                        allowOutsideClick: false

                    });
                    
                }
            })
    });

</script>