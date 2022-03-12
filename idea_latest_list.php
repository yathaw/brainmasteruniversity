<?php 
	require 'header.php';
    include("confs/config.php");

    $date = new DateTime();
    $today = $date->format('Y-m-d');

    $categories_select="SELECT * from categories";
    $categories_query=mysqli_query($conn,$categories_select);
    $categories=mysqli_fetch_all($categories_query);



    function time_since($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    if (isset($_GET['page_no']) && $_GET['page_no']!="") {
        $page_no = $_GET['page_no'];
    } 
    else {
        $page_no = 1;
    }

    $total_records_per_page = 5;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2"; 

    $result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM `ideas`");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    
    $second_last = $total_no_of_pages - 1; // total page minus 1

    $latest_ideas_select="SELECT ideas.*, categories.id as cid, categories.name as cname, categories.color as ccolor, users.name as uname, users.profile as uprofile, positions.name as pname from ideas 
            LEFT JOIN categories ON ideas.category_id = categories.id
            LEFT JOIN users ON ideas.user_id = users.id
            LEFT JOIN position_user ON users.id = position_user.user_id
            LEFT JOIN positions ON position_user.position_id = positions.id
            ORDER BY ideas.created_at DESC
            LIMIT $offset, $total_records_per_page";
    $latest_ideas_query=mysqli_query($conn,$latest_ideas_select);
    $latest_ideas=mysqli_fetch_all($latest_ideas_query);

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
                                    <a class="nav-link " href="idea_category_list.php">
                                        <i class='bx bx-collection me-1'></i> Category Group </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="idea_latest_list.php">
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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="d-inline-block"> Update Ideas </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="timeline">

                                    <?php 
                                        foreach($latest_ideas as $key => $latest_idea):

                                        $latest_idea_id = $latest_idea[0];
                                        $latest_idea_name = $latest_idea[1];
                                        $latest_idea_body = $latest_idea[2];
                                        $latest_idea_file = $latest_idea[3];
                                        $latest_idea_category_id = $latest_idea[4];
                                        $latest_idea_user_id = $latest_idea[5];
                                        $latest_idea_status = $latest_idea[6];
                                        $latest_idea_created_at = time_since($latest_idea[7]);
                                        
                                        $latest_idea_category_id = $latest_idea[9];
                                        $latest_idea_category_name = $latest_idea[10];
                                        $latest_idea_category_color = $latest_idea[11];

                                        $latest_idea_user_name = $latest_idea[12];
                                        $latest_idea_user_profile = $latest_idea[13];
                                        $latest_idea_position = $latest_idea[14];


                                        $latest_idea_body_decode = html_entity_decode($latest_idea_body);
                                        $latest_ideas_body_removeHTMLtag = strip_tags($latest_idea_body_decode);

                                        if (strlen($latest_ideas_body_removeHTMLtag) > 120){
                                            $latest_idea_body_limit = substr($latest_ideas_body_removeHTMLtag, 0, 120).'...';
                                        }
                                        else{
                                            $latest_idea_body_limit = $latest_ideas_body_removeHTMLtag;
                                        }

                                        if (strlen($latest_idea_name) > 120){
                                            $latest_idea_title = substr($latest_idea_name, 0, 120).'...';
                                        }
                                        else{
                                            $latest_idea_title = $latest_idea_name;
                                        }

                                        

                                    ?>
                                    <?php if($latest_idea_status == "on"): ?>
                                        <li class="timeline-item timeline-item-transparent">
                                            <a href="idea.php?id=<?= $latest_idea_id; ?>" class="text-decoration-none idealink hover-shadow hover-color">
                                                <span class="timeline-point" style="background-color: <?= $latest_idea_category_color; ?>" ></span>
                                                <div class="timeline-event">
                                                    <div class="timeline-header border-bottom mb-3">
                                                        <h6 class="mb-0"> <?= $latest_idea_category_name; ?> </h6>
                                                        <small class="text-muted"> <?= $latest_idea_created_at; ?> </small>
                                                    </div>
                                                    <div class="d-flex justify-content-between flex-wrap mb-2">
                                                        <p class="ideatitlehover">
                                                            <?= $latest_idea_title; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="timeline-item timeline-item-transparent">
                                            <a href="idea.php?id=<?= $latest_idea_id; ?>" class="text-decoration-none idealink hover-shadow hover-color">

                                                <span class="timeline-point " style="background-color: <?= $latest_idea_category_color; ?>" ></span>
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0"> <?= $latest_idea_category_name; ?> </h6>
                                                        <small class="text-muted"> <?= $latest_idea_created_at; ?> </small>
                                                    </div>
                                                    <p class="ideatitlehover">
                                                        <?= $latest_idea_title; ?>
                                                    </p>
                                                    <hr />
                                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                                        <div class="d-flex flex-wrap">
                                                            <div class="avatar me-3">
                                                                <img src="<?= $latest_idea_user_profile; ?>" alt="Avatar" class="rounded-circle" />
                                                            </div>
                                                            <div>
                                                                <p class="text-secondary mb-0"> <?= $latest_idea_user_name; ?> </p>
                                                                <span class="text-muted"> <?= $latest_idea_position; ?> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php 
                                        endif;
                                        endforeach; 
                                    ?>

                                        <li class="timeline-end-indicator">
                                            <i class="bx bx-check-circle"></i>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">

                                            <?php 
                                                if($page_no > 1){ echo "<li class='page-item'><a class='page-link' href='?page_no=1'> <i class='bx bx-chevrons-left' ></i>  </a></li>"; }
                                             ?>
                                                
                                                <li <?php if ($page_no <= 1)
                                            {
                                                echo "class='page-item disabled'";
                                            } ?>>
                                                <a class='page-link' <?php if ($page_no > 1)
                                            {
                                                echo "href='?page_no=$previous_page'";
                                            } ?>> <i class='bx bxs-chevron-left'></i> </a>
                                                </li>
                                                   
                                                <?php
                                            if ($total_no_of_pages <= 10)
                                            {
                                                for ($counter = 1;$counter <= $total_no_of_pages;$counter++)
                                                {
                                                    if ($counter == $page_no)
                                                    {
                                                        echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                                    }
                                                    else
                                                    {
                                                        echo "<li class='page-item'>
                                                                <a class='page-link' href='?page_no=$counter'>$counter</a>
                                                            </li>";
                                                    }
                                                }
                                            }
                                            elseif ($total_no_of_pages > 10)
                                            {

                                                if ($page_no <= 4)
                                                {
                                                    for ($counter = 1;$counter < 8;$counter++)
                                                    {
                                                        if ($counter == $page_no)
                                                        {
                                                            echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                                        }
                                                        else
                                                        {
                                                            echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                                        }
                                                    }
                                                    echo "<li class='page-item'>
                                                            <a class='page-link'>...</a>
                                                        </li>";
                                                    echo "<li class='page-item'>
                                                            <a class='page-link' href='?page_no=$second_last'>$second_last</a>
                                                        </li>";
                                                    echo "<li class='page-item'>
                                                            <a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a>
                                                        </li>";
                                                }

                                                elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4)
                                                {
                                                    echo "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
                                                    echo "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
                                                    echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                                    for ($counter = $page_no - $adjacents;$counter <= $page_no + $adjacents;$counter++)
                                                    {
                                                        if ($counter == $page_no)
                                                        {
                                                            echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                                        }
                                                        else
                                                        {
                                                            echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                                        }
                                                    }
                                                    echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                                    echo "<li class='page-item'><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
                                                    echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                                }

                                                else
                                                {
                                                    echo "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
                                                    echo "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
                                                    echo "<li class='page-item'><a class='page-link'>...</a></li>";

                                                    for ($counter = $total_no_of_pages - 6;$counter <= $total_no_of_pages;$counter++)
                                                    {
                                                        if ($counter == $page_no)
                                                        {
                                                            echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                                        }
                                                        else
                                                        {
                                                            echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                                
                                                <li class='page-item' <?php if ($page_no >= $total_no_of_pages)
                                            {
                                                echo "class='page-item disabled'";
                                            } ?>>
                                                <a class='page-link'<?php if ($page_no < $total_no_of_pages)
                                            {
                                                echo "href='?page_no=$next_page'";
                                            } ?>> <i class='bx bxs-chevron-right'></i> </a>
                                                </li>
                                                <?php if ($page_no < $total_no_of_pages)
                                            {
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'> <i class='bx bxs-chevrons-right' ></i> </a></li>";
                                            } ?>


                                        </ul>
                                    </nav>
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
