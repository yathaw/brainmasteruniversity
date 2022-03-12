<?php 
	require 'header.php';
    include("confs/config.php");

    $positions_select="SELECT * from positions";
    $positions_query=mysqli_query($conn,$positions_select);
    $positions=mysqli_fetch_all($positions_query);

    

?>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require 'app_sidebar.php' ?>

            <div class="layout-page">
                <?php require 'app_header.php' ?>

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row mb-3">
                        <div class="col-10">
                            <h5 class="d-inline-block">Role List</h5>
                        </div>
                        <div class="col-2">
                            <a href="role_add.php" class="btn btn-primary"> 
                                <i class='bx bx-plus me-1' ></i>  Add New 
                            </a>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <?php 
                            foreach($positions as $key => $position):
                            $id = $position[0];
                            $name = $position[1];
                            $departmentid = $position[2];


                            $position_user_select="SELECT
                                                users.*
                                            FROM
                                                users
                                            LEFT JOIN position_user ON users.id = position_user.user_id
                                            LEFT JOIN positions ON position_user.position_id = positions.id
                                            LEFT JOIN departments ON positions.department_id = departments.id
                                            WHERE position_user.position_id = $id 
                                            ORDER BY positions.id";
                            $position_user_query=mysqli_query($conn,$position_user_select);
                            $position_user_count=mysqli_num_rows($position_user_query);

                            $users=mysqli_fetch_all($position_user_query);

                        ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100 text-white mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-normal"> Total <?= $position_user_count; ?> Staff </h6>
                                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                                <?php 
                                                    foreach($users as $user):
                                                    $userid = $user[0];
                                                    $username = $user[1];
                                                    $userprofile = $user[2];
                                                ?>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="<?= $username; ?>" class="avatar avatar-sm pull-up detailBtn" data-id="<?= $userid; ?>">
                                                    <img class="rounded-circle" src="<?= $userprofile; ?>" alt="Avatar">
                                                </li>

                                            <?php endforeach; ?>
                                                
                                            </ul>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="role-heading">
                                            <h4 class="mb-1"> <?= $name; ?> </h4>
                                            <a href="role_edit.php?id=<?= $id; ?>" class="text-decoration-none"> Edit Role</a>
                                            

                                        </div>
                                        <a href="javascript:void(0)" class="text-decoration-none text-danger removeBtn" data-id="<?= $id; ?>"> <i class='bx bx-x' ></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php endforeach ?>

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
<script type="text/javascript">
    $('.removeBtn').on('click', function(e) {
        var id = $(this).data("id");

        Swal.fire({
            title: 'You are about to delete a role',
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
                        url:'role_delete.php',
                        data:{id:id},
                        type:'POST',
                        success:function(data){
                            console.log(data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Role removed successfully!',
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
                        allowOutsideClick: false,
                        timer : 1500
                    });
                    
                }
            })
    });
</script>