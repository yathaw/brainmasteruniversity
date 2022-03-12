<?php 
	require 'header.php';
    include("confs/config.php");

    if(isset($_POST["btnsave"]))
	{
		$nameErr = null;
	    $colorErr = null;
	    $ideaenddateErr = null;
	    $commentenddateErr = null;

		$id=$_POST['id'];
		$name=$_POST['name'];
	    $color=$_POST['color'];
	    $ideaenddate=$_POST['ideaenddate'];
	    $commentenddate=$_POST['commentenddate'];


		$valid = true;
	    if (empty($name)) {
	        $nameErr = 'The category field is required.';
	        $valid = false;
	    }

	    if (empty($color)) {
	        $colorErr = 'Please pick at least one color';
	        $valid = false;
	    }

	    if (empty($ideaenddate)) {
	        $ideaenddateErr = 'Idea end-date is a required field.';
	        $valid = false;
	    }

	    if (empty($commentenddate)) {
	        $commentenddateErr = 'Comment end-date is a required field.';
	        $valid = false;
	    }

	    if($valid){
		    
		    $categories_sql = "UPDATE categories SET name='$name', color='$color', ideaenddate='$ideaenddate', commentenddate='$commentenddate' WHERE id='$id' ";
			mysqli_query($conn, $categories_sql);


			echo "<script>
    				Swal.fire({
					  	icon: 'success',
					  	title: 'Store Successful',
					  	text: 'You saved the category.',
					  	showConfirmButton: false,
					  	timer: 2000,
        				allowOutsideClick: false

					}).then(function(){
						window.location='category_list.php'
					})
    			</script>
    		"; 
		    

	    }

	}
	if(isset($_GET['cid']))
	{
		$id=$_GET['cid'];

		$category_query=mysqli_query($conn,"SELECT * FROM categories WHERE id='$id'");  
	    $category_numrows=mysqli_num_rows($category_query);
	    if($category_numrows > 0){
	    	$row = mysqli_fetch_array($category_query);

	    	$name = $row['name'];
	    	$color = $row['color'];
	    	$ideaenddate = $row['ideaenddate'];

	    	$commentenddate = $row['commentenddate'];

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
                            <h5 class="d-inline-block">Update Existing Category</h5>
                        </div>

                        <div class="card-body">
                        	
                            <form action='category_edit.php' method='POST' class="form-sample">
                            	<input type="hidden" name="id" value="<?= $id; ?>">
                            	<div class="row mb-3">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">                                             
                                        <label class="form-label" for="inputName"> Name </label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class='bx bx-purchase-tag' ></i>
                                            </span>
                                            <input type="text" class="form-control 
                                            	<?php 
					                            	if(isset($nameErr)) { echo 'is-invalid'; }
					                            ?>

					                            <?php 
					                            	if(isset($valid) && empty($nameErr)){ echo 'is-valid'; }
					                            ?>" id="inputName" placeholder="Enter Category Name Here" name="name" autofocus value="<?= $name; ?>" />
                                        </div>

                                        <span class="text-danger">
                                            <?php if(isset($nameErr)){ echo $nameErr; } ?> 
                                        </span> 
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">                                             
                                        <label class="form-label" for="inputColor"> Color Picker </label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class='bx bx-palette'></i>
                                            </span>
                                            <input type="color" class="form-control form-control-color 
                                            	<?php 
					                            	if(isset($ideaenddateErr)) { echo 'is-invalid'; }
					                            ?>

					                            <?php 
					                            	if(isset($valid) && empty($ideaenddateErr)){ echo 'is-valid'; }
					                            ?>" 
					                            id="inputColor"  name="color"  value="<?= $color; ?>"/>
                                        </div>

                                        <span class="text-danger">
                                            <?php if(isset($colorErr)){ echo $colorErr; } ?> 
                                        </span> 
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">                                             
                                        <label class="form-label" for="inputIdeaenddate"> Idea End Date </label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class='bx bx-calendar-event' ></i>
                                            </span>
                                            <input type="text" class="form-control 
                                            <?php 
				                            	if(isset($colorErr)) { echo 'is-invalid'; }
				                            ?>

				                            <?php 
				                            	if(isset($valid) && empty($colorErr)){ echo 'is-valid'; }
				                            ?>"
				                            id="inputIdeaenddate" name="ideaenddate"  value="<?= $ideaenddate; ?>" />
                                        </div>

                                        <span class="text-danger">
                                            <?php if(isset($ideaenddateErr)){ echo $ideaenddateErr; } ?> 
                                        </span> 
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">                                             
                                        <label class="form-label" for="inputCommentenddate"> Comment End Date </label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class='bx bx-calendar-event' ></i>
                                            </span>
                                            <input type="text" class="form-control <?php 
				                            	if(isset($commentenddateErr)) { echo 'is-invalid'; }
				                            ?>

				                            <?php 
				                            	if(isset($valid) && empty($commentenddateErr)){ echo 'is-valid'; }
				                            ?>" id="inputCommentenddate" name="commentenddate" value="<?= $commentenddate ?>"/>
                                        </div>

                                        <span class="text-danger">
                                            <?php if(isset($commentenddateErr)){ echo $commentenddateErr; } ?> 
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

<script>
	var now = Date.now();


	$("#inputIdeaenddate").datepicker({
	    startDate:now,
	    format:'yyyy-MM-dd',
	    pickedClass: 'picked',
	    highlightedClass: 'highlighted',
	    mutedClass: 'muted',
	    autoHide:true,

	});
  	$('#inputIdeaenddate').on('change', function (e) {

		ideaenddate = $("#inputIdeaenddate").datepicker('getDate');
		$('#inputCommentenddate').prop("readonly", false);

		ideaenddate = new Date(ideaenddate);
		ideaenddate.setDate(ideaenddate.getDate() + 1);

		$("#inputCommentenddate").datepicker({
		    startDate:ideaenddate,
		    format:'yyyy-MM-dd',
		    pickedClass: 'picked',
		    highlightedClass: 'highlighted',
		    mutedClass: 'muted',
		    autoHide:true,

		});
	});

  	$("#inputCommentenddate").datepicker({
	    startDate:now,
	    format:'yyyy-MM-dd',
	    pickedClass: 'picked',
	    highlightedClass: 'highlighted',
	    mutedClass: 'muted',
	    autoHide:true,

	});


</script>