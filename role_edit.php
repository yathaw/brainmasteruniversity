<?php 
	require 'header.php';
    include("confs/config.php");
    
    $departments_select="SELECT * from departments";
    $departments_query=mysqli_query($conn,$departments_select);
    $departments_count=mysqli_num_rows($departments_query);
    $departments=mysqli_fetch_all($departments_query);

    if(isset($_POST["btnsave"]))
	{
		$nameErr = null;
	    $departmentErr = null;

	    $id = $_POST['id'];
		$name=$_POST['name'];
	    $departmentid=$_POST['departmentid'];

		$valid = true;
	    if (empty($name)) {
	        $nameErr = 'The category field is required.';
	        $valid = false;
	    }

	    if (empty($departmentid)) {
	        $departmentErr = 'Please pick at least one department';
	        $valid = false;
	    }

	    if($valid){
	    	$positions_sql = "UPDATE positions SET name='$name', department_id='$departmentid' WHERE id='$id' ";
			mysqli_query($conn, $positions_sql);


			echo "<script>
    				Swal.fire({
					  	icon: 'success',
					  	title: 'Store Successful',
					  	text: 'You saved the category.',
					  	showConfirmButton: false,
					  	timer: 2000,
        				allowOutsideClick: false

					}).then(function(){
						window.location='role_list.php'
					})
    			</script>
    		"; 
		     

	    }

	}

	if(isset($_GET['id']))
	{
		$id=$_GET['id'];

		$position_query=mysqli_query($conn,"SELECT * FROM positions WHERE id='$id'");  
	    $position_numrows=mysqli_num_rows($position_query);
	    if($position_numrows > 0){
	    	$row = mysqli_fetch_array($position_query);

	    	$name = $row['name'];
	    	$departmentid = $row['department_id'];

	    } else{
	    	header("location: 404.php");
	    }
	}

?>
	
	<div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require 'app_sidebar.php' ?>

            <div class="layout-page">
                <?php require 'app_header.php' ?>

                <div class="container-xxl flex-grow-1 container-p-y">

                	<div class="card">
                        <div class="card-header">
                            <h5 class="d-inline-block">Edit Existing Poistion</h5>
                        </div>

                        <div class="card-body">

                        	<?php if(isset($_SESSION['store_reject']) && !empty($_SESSION['store_reject']) ): ?>

						        <div class="alert alert-danger alert-dismissible fade show" role="alert">
						            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
						                <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
						                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
						                </symbol>
						            </svg>
						            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
						            
						            <?= $_SESSION['store_reject']; ?>
						            
						            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						        </div>

					        <?php endif ?>
                        	
                            <form action='role_edit.php' method='POST' class="form-sample">
                            	<input type="hidden" name="id" value="<?= $id; ?>">
                            	<div class="row mb-3">
                                    <div class="col-12">                                             
                                        <label class="form-label" for="inputName"> Name </label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class='bx bx-label'></i>
                                            </span>
                                            <input type="text" class="form-control 
                                            	<?php 
					                            	if(isset($nameErr)) { echo 'is-invalid'; }
					                            ?>

					                            <?php 
					                            	if(isset($valid) && empty($nameErr)){ echo 'is-valid'; }
					                            ?>" id="inputName" placeholder="Enter Position Name Here" name="name" autofocus value="<?= $name; ?>" />
                                        </div>

                                        <span class="text-danger">
                                            <?php if(isset($nameErr)){ echo $nameErr; } ?> 
                                        </span> 
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">                                             
                                        <label class="form-label" for="inputColor"> Department  </label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class='bx bx-sitemap'></i>
                                            </span>
                                            <select class="select2" name="departmentid" id="inputDepartmentid">
					                            <option></option>
					                            <?php 
					                                foreach($departments as $key => $department):
					                            ?>
					                            <option value="<?= $department[0]; ?>" 
                                                        <?php if($departmentid == $department[0]) {
                                                                echo "selected"; 
                                                        	} 
                                                        ?> 
                                                    > 
					                                <?= $department[1]; ?> 
					                            </option>
					                            <?php endforeach ?>
					                        </select>
                                        </div>

                                        <span class="text-danger">
                                            <?php if(isset($departmentErr)){ echo $departmentErr; } ?> 
                                        </span> 
                                    </div>

                                </div>


                                <div class="row ">
                                	<div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                		<button class="btn btn-secondary me-md-2" type="reset"> 
                                            <i class="fa-solid fa-arrow-rotate-right"></i> Cancel 
                                        </button>
                                        <button class="btn btn-primary" type="submit" name='btnsave'> 
                                            <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes 
                                        </button>
                                	</div>
                                </div>

                        	</form>

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

