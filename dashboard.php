<?php 
	require 'header.php';
    include("confs/config.php");

    $sess_userid = $_SESSION['sess_user']['id'];
    $sess_username = $_SESSION['sess_user']['name'];

    $ideas = mysqli_query($conn,"SELECT id FROM `ideas` WHERE `user_id` = $sess_userid");
    $ideas=mysqli_fetch_all($ideas);

    $idea_ids = array();
    foreach($ideas as $ideas){
        array_push($idea_ids, $ideas[0]);
    }
    $idea_ids = implode("','",$idea_ids);

    $like_query=$query=mysqli_query($conn, "SELECT * FROM reacts WHERE react = 1 AND idea_id IN ('".$idea_ids."')");
    $like_total=mysqli_num_rows($like_query);


    $dislike_query=$query=mysqli_query($conn, "SELECT * FROM reacts WHERE react = 0 AND idea_id IN ('".$idea_ids."')");
    $dislike_total=mysqli_num_rows($dislike_query);

    if ($like_total > $dislike_total) {
        $stamp = $like_total - $dislike_total;
    }else{
        $stamp = 0;
    }

    $idea_result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM `ideas`");
    $idea_total_records = mysqli_fetch_array($idea_result_count);
    $idea_total_records = $idea_total_records['total_records'];

    $category_result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM `categories`");
    $category_total_records = mysqli_fetch_array($category_result_count);
    $category_total_records = $category_total_records['total_records'];

    $categories_select="SELECT * from categories";
    $categories_query=mysqli_query($conn,$categories_select);
    $categories=mysqli_fetch_all($categories_query);

?>

	<div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require 'app_sidebar.php' ?>

            <div class="layout-page">
                <?php require 'app_header.php' ?>

                <div class="container-xxl flex-grow-1 container-p-y">

                	<div class="row">

                		<div class="col-md-12 col-lg-4 mb-4">
					      	<div class="card mb-4">
					          	<div class="d-flex align-items-end row">
					              	<div class="col-8">
					                  	<div class="card-body">
					                      	<h6 class="card-title mb-1 text-nowrap">Hello <?= $sess_username; ?>!</h6>
					                      	<small class="d-block mb-3 text-nowrap">You have</small>
					                      	<h5 class="card-title text-primary mb-1"> <?= $stamp; ?> </h5>
					                      	<small class="d-block mb-4 pb-1 text-muted"> Stamps </small>
					                      <a href="javascript:;" class="btn btn-sm btn-primary">View Marks</a>
					                  	</div>
					              	</div>
					              	<div class="col-4 pt-3 ps-0">
					                  	<img src="assets/img/prize-light.png" width="90" height="140" class="rounded-start" alt="View Sales">
					              	</div>
					          	</div>
					      	</div>
					      	<div class="card mb-4">
					      		<div class="d-flex align-items-end row">
					              	<div class="co-10">
					                  	<div class="card-body">
					      					<div id='calendar'></div>
					      				</div>
					      			</div>
					      		</div>
					      	</div>
					  	</div>
						<!-- Total Revenue -->
						<div class="col-12 col-lg-8 col-md-8 mb-4">
						    <div class="card">
				                <h5 class="card-header m-0 me-2 pb-3">Total Analytics</h5>

				                <div id="totalanalyticsbycategoryChart" class="px-2"></div>

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
<script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>
<script src="assets/js/analytics.js"></script>
<script src="assets/vendor/fullcalendar/main.js"></script>
