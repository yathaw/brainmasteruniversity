<?php 
	require 'header.php';
    include("confs/config.php");
    
    if(isset($_POST["btnsave"]))
	{
		unset($_SESSION['oldvalue']);
		unset($_SESSION['store_reject']);

		$nameErr = null;
	    $colorErr = null;
	    $ideaenddateErr = null;
	    $commentenddateErr = null;

		$_SESSION['oldvalue']=array(); 

		$name=$_POST['name'];
	    $color=$_POST['color'];
	    $ideaenddate=$_POST['ideaenddate'];
	    $commentenddate=$_POST['commentenddate'];


		$_SESSION['oldvalue']['name'] = $name;
		$_SESSION['oldvalue']['color'] = $color;
		$_SESSION['oldvalue']['ideaenddate'] = $ideaenddate;
		$_SESSION['oldvalue']['commentenddate'] = $commentenddate;

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
	    	$category_query=mysqli_query($conn,"SELECT * FROM categories WHERE name LIKE '%$name%' ");  
		    $category_numrows=mysqli_num_rows($category_query); 
		    if($category_numrows <= 0){
		    	
		    	$categories_sql = "INSERT INTO categories(name, color, ideaenddate, commentenddate) VALUES 
				    ('$name','$color','$ideaenddate','$commentenddate')";
				mysqli_query($conn, $categories_sql);

				unset($_SESSION['oldvalue']);
				unset($_SESSION['store_reject']);

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
		    else{ 
		    	$_SESSION['store_reject'] = "Sorry! The category name already exists!";
		    }
		     

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
                            <h5 class="d-inline-block">Create New Category</h5>
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
                        	
                            <form action='category_add.php' method='POST' class="form-sample">
                            	<div class="row mb-3">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">                                             
                                        <label class="form-label" for="inputName"> Name </label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">
                                                <i class='bx bx-purchase-tag' ></i>
                                            </span>
                                            <input type="text" class="form-control 
                                            	<?php 
					                            	if(isset($nameErr) || isset($_SESSION['store_reject']) && !empty($_SESSION['store_reject']) ) { echo 'is-invalid'; }
					                            ?>

					                            <?php 
					                            	if(isset($valid) && empty($nameErr) && isset($_SESSION['store_reject']) && empty($_SESSION['store_reject']) ){ echo 'is-valid'; }
					                            ?>" id="inputName" placeholder="Enter Category Name Here" name="name" autofocus value="<?php if(isset($_SESSION['oldvalue']['name']) && !empty($_SESSION['oldvalue']['name']) ){ echo $_SESSION['oldvalue']['name']; } ?>" />
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
					                            id="inputColor"  name="color"  value="<?php if(isset($_SESSION['oldvalue']['color']) && !empty($_SESSION['oldvalue']['color']) ){ echo $_SESSION['oldvalue']['color']; } ?>"/>
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
				                            id="inputIdeaenddate" name="ideaenddate"  value="<?php if(isset($_SESSION['oldvalue']['ideaenddate']) && !empty($_SESSION['oldvalue']['ideaenddate']) ){ echo $_SESSION['oldvalue']['ideaenddate']; } ?>" />
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
				                            ?>" id="inputCommentenddate" name="commentenddate"  <?php if(isset($_SESSION['oldvalue']['commentenddate']) && empty($_SESSION['oldvalue']['commentenddate']) ){ ?> readonly <?php } ?> value="<?php if(isset($_SESSION['oldvalue']['commentenddate']) && !empty($_SESSION['oldvalue']['commentenddate']) ){ echo $_SESSION['oldvalue']['commentenddate']; } ?>"/>
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



</script>