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
                            <h5 class="d-inline-block">Category List</h5>

                            <a href="category_add.php" class="btn btn-primary float-end"> 
                                <i class='bx bx-plus me-1' ></i>  Add New 
                            </a>
                        </div>
                        <div class="card-body px-3">

                            <div class="table-responsive text-nowrap">
                                <table class="table" id="sampleTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Color</th> 
                                            <th>Idea End Date</th>
                                            <th> Comment End Date </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php 
                                            $select="SELECT * from categories";
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
                                                    $color=$data['color'];
                                                    $ideaEndDate=$data['ideaenddate'];
                                                    $commentEndDate=$data['commentenddate'];
                                                ?>


                                                <tr>   
                                                    <td> <?= $number++; ?>. </td>
                                                    <td> <?= $name; ?> </td>
                                                    <td> 
                                                        <div style='background-color: <?= $color ?>; width:100px;  height:30px' ></div> 
                                                    </td>
                                                    <td> <?= $ideaEndDate; ?> </td>
                                                    <td> <?= $commentEndDate ?> </td>

                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item text-warning fw-bold" href="category_edit.php?cid=<?= $id; ?>">
                                                                    <i class='bx bx-edit-alt' ></i> Edit     
                                                                </a>
                                                                <a class="dropdown-item text-danger removeBtn" href="javascript:void(0)" data-id="<?= $id; ?>">
                                                                    <i class='bx bx-trash' ></i> Remove 
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



<?php 
	require 'footer.php';
?>

<script type="text/javascript">
    $('.removeBtn').on('click', function(e) {
        var id = $(this).data("id");

        Swal.fire({
            title: 'You are about to delete a category',
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
                        url:'category_delete.php',
                        data:{id:id},
                        type:'POST',
                        success:function(data){
                            console.log(data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Category removed successfully!',
                                showConfirmButton: false,
                                timer: 2000
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
                        timer : 1500
                    });
                    
                }
            })
    });
</script>