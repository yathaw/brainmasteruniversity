<?php 
	require 'header.php';
    include("confs/config.php");

    $date = new DateTime();
    $today = $date->format('Y-m-d');

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
                        <div class="col-12">
                            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                                <li class="nav-item">
                                    <a class="nav-link active" href="idea_category_list.php">
                                        <i class='bx bx-collection me-1'></i> Category Group </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="idea_latest_list.php">
                                        <i class='bx bxs-megaphone me-1'></i> All Updates </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="idea_trending_list.php">
                                        <i class='bx bx-trending-up me-1' ></i> Trending List </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <?php 
                            foreach($categories as $key => $category):
                            $category_id = $category[0];
                            $category_name = $category[1];
                            $category_color = $category[2];
                            $category_ideaenddate = $category[3];
                            $category_commentenddate = $category[4];
                            $category_createdat = $category[5];

                            $idea_category_select="SELECT * from ideas WHERE category_id = $category_id";
                            $idea_category_query=mysqli_query($conn,$idea_category_select);
                            $idea_category_count=mysqli_num_rows($idea_category_query);

                        ?>
                        <div class="col-md-6 col-xl-4">
                            <a href="idea_list.php?cid=<?= $category_id; ?>">
                                <div class="card text-white mb-3" style="background-color: <?= $category_color; ?>">
                                    <div class="card-header"> 
                                        <span class="badge badge-center rounded-pill bg-info"> <?= $idea_category_count; ?> </span>
                                        <?php if ($idea_category_count > 1) { echo "Ideas";} else {echo "Idea";} ?> 
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-white"> <?= $category_name; ?> </h5>
                                    </div>
                                </div>
                            </a>
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