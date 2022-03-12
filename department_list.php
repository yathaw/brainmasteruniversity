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
                            <h5 class="d-inline-block">Department List</h5>

                            <a href="javascript:void(0)" class="btn btn-primary float-end addBtn"> 
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
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php 
                                            $select="SELECT * from departments";
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
                                                ?>


                                                <tr>   
                                                    <td> <?= $number++; ?>. </td>
                                                    <td> <?= $name; ?> </td>
                                                    
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item text-warning fw-bold editBtn" href="javascript:void(0)" data-id="<?= $id; ?>" data-name="<?= $name; ?>">
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="showOffcanvas" aria-labelledby="offcanvasBackdropLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasBackdropLabel" class="offcanvas-title">Add Departments</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body my-auto mx-0 flex-grow-0">
        <input type="hidden" id="inputId">
        <div class="row mb-3">
            <div class="col-12 form-group">
                <label class="form-label" for="inputName"> Name</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text">
                        <i class='bx bx-label'></i>
                    </span>
                    <input type="text" class="form-control" id="inputName" placeholder="IT Department" name="name" autofocus />
                </div>

                <p class="text-danger d-block" id="nameErr">
                </p>
            </div>
        </div>

        <button type="button" id="savechanges" class="btn btn-primary mb-2 d-grid w-100">Save Changes</button>
        <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas"> Cancel </button>
    </div>
</div>

<?php 
	require 'footer.php';
?>

<script type="text/javascript">
    var myOffcanvas = document.getElementById('showOffcanvas');
        
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
    $('.addBtn').on('click', function(e) {
        
        bsOffcanvas.show();
        $('#savechanges').addClass('saveBtn');
        $('#savechanges').removeClass('updateBtn');


    });

    $('.editBtn').on('click', function(e) {
        var id = $(this).data("id");
        var name = $(this).data("name");
        
        $('#inputName').val(name);
        $('#inputId').val(id);

        bsOffcanvas.show();

        $('#savechanges').addClass('updateBtn');
        $('#savechanges').removeClass('saveBtn');


    });

    $("#showOffcanvas").on('click','.saveBtn', function(event){

        $('#nameErr').text('');
        $('#inputName').removeClass('border-danger');

        var name = $('#inputName').val();

        if(name == ""){
            $('#nameErr').text('Please enter department name.');
            $('#inputName').addClass('border-danger');
        }else{
            console.log(name);
            $.ajax({
                url:"department_add.php",
                type: 'POST',
                data: {
                    name: name
                },
                success:function(data){             
                    var parsedJson = $.parseJSON(data);
                    if(parsedJson.status == "Error"){
                        $('#nameErr').text(parsedJson.msg);
                        $('#inputName').addClass('border-danger');
                    }else{
                        bsOffcanvas.hide();
                                 
                        Swal.fire({
                            icon: "success",
                            text: "Your data has been successfully saved!",
                            buttons: false,
                            timer : 1500,
                            allowOutsideClick: false

                        }).then(function(){
                            window.location.reload();

                        })
                    }


                }
            })
        } 
    });

    $("#showOffcanvas").on('click','.updateBtn', function(event){

        $('#nameErr').text('');
        $('#inputName').removeClass('border-danger');

        var name = $('#inputName').val();
        var id = $('#inputId').val();


        if(name == ""){
            $('#nameErr').text('Please enter department name.');
            $('#inputName').addClass('border-danger');
        }else{
            console.log(name);
            $.ajax({
                url:"department_edit.php",
                type: 'POST',
                data: {
                    name: name,
                    id: id,

                },
                success:function(data){             
                    
                    bsOffcanvas.hide();
                             
                    Swal.fire({
                        icon: "success",
                        text: "Your data has been successfully updated!",
                        buttons: false,
                        timer : 1500,
                        allowOutsideClick: false

                    }).then(function(){
                        window.location.reload();

                    })


                }
            })
        } 
    });

    $('.removeBtn').on('click', function(e) {
        var id = $(this).data("id");

        Swal.fire({
            title: 'You are about to delete a department',
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
                        url:'department_delete.php',
                        data:{id:id},
                        type:'POST',
                        success:function(data){
                            console.log(data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Department removed successfully!',
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