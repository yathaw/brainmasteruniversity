<?php 
	require 'header.php';
    include("confs/config.php");

    $date = new DateTime();
    $today = $date->format('Y-m-d');

    $categories_select="SELECT * from categories";
    $categories_query=mysqli_query($conn,$categories_select);
    $categories=mysqli_fetch_all($categories_query);

    $trending_idea_comment_select="SELECT idea_id, count(*) AS counter FROM comments GROUP BY idea_id order by counter desc limit 5";
    $trending_idea_comment_query=mysqli_query($conn,$trending_idea_comment_select);
    $trending_idea_comments=mysqli_fetch_all($trending_idea_comment_query);

    $trending_idea_comment_ids = array();
    foreach($trending_idea_comments as $trending_idea_comment){
        $trending_idea_comment_ids[] = $trending_idea_comment[0];
    }

    $trending_idea_react_select="SELECT idea_id, count(*) AS counter FROM reacts WHERE react = 1 GROUP BY idea_id order by counter desc";
    $trending_idea_react_query=mysqli_query($conn,$trending_idea_react_select);
    $trending_idea_reacts=mysqli_fetch_all($trending_idea_react_query);

    $trending_idea_react_ids = array();
    foreach($trending_idea_reacts as $trending_idea_react){
        $trending_idea_react_ids[] = $trending_idea_react[0];
    }

    $trending_idea_comment_react_ids = array_unique(array_merge($trending_idea_comment_ids,$trending_idea_react_ids), SORT_REGULAR);
    $trending_idea_comment_react_ids = join("','",$trending_idea_comment_react_ids);   

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

    $result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM `ideas` WHERE ideas.id IN ('$trending_idea_comment_react_ids') ");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    
    $second_last = $total_no_of_pages - 1; // total page minus 1

    $trending_ideas_select="
            SELECT ideas.id as id, ideas.title as title, ideas.created_at as created_at, ideas.status as status,
                    categories.name as cname, categories.color as ccolor, 
                    users.name as uname, users.profile as uprofile, positions.name as pname
            FROM ideas 
            LEFT JOIN categories ON ideas.category_id = categories.id
            LEFT JOIN users ON ideas.user_id = users.id
            LEFT JOIN position_user ON users.id = position_user.user_id
            LEFT JOIN positions ON position_user.position_id = positions.id
            LEFT JOIN comments ON ideas.id = comments.idea_id
            LEFT JOIN reacts ON ideas.id = reacts.idea_id
            WHERE ideas.id IN ('$trending_idea_comment_react_ids')
            GROUP BY comments.idea_id, reacts.idea_id
            ORDER BY created_at DESC
            LIMIT $offset, $total_records_per_page";


    $trending_ideas_query=mysqli_query($conn,$trending_ideas_select);
    $trending_ideas=mysqli_fetch_all($trending_ideas_query);

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
                                    <a class="nav-link " href="idea_latest_list.php">
                                        <i class='bx bxs-megaphone me-1'></i> All Updates </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="idea_trending_list.php">
                                        <i class='bx bx-trending-up me-1' ></i> Trending List </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="d-inline-block"> Trending Ideas </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="timeline">

                                    <?php 
                                        foreach($trending_ideas as $key => $trending_idea):

                                        $trending_idea_id = $trending_idea[0];
                                        $trending_idea_name = $trending_idea[1];
                                        $trending_idea_created_at = time_since($trending_idea[2]);
                                        $trending_idea_status = $trending_idea[3];
                                        
                                        $trending_idea_category_name = $trending_idea[4];
                                        $trending_idea_category_color = $trending_idea[5];
                                        $trending_idea_user_name = $trending_idea[6];
                                        $trending_idea_user_profile = $trending_idea[7];
                                        $trending_idea_position_name = $trending_idea[8];

                                        if (strlen($trending_idea_name) > 120){
                                            $trending_idea_title = substr($trending_idea_name, 0, 120).'...';
                                        }
                                        else{
                                            $trending_idea_title = $trending_idea_name;
                                        }

                                    ?>
                                    <?php if($trending_idea_status == "on"): ?>
                                        <li class="timeline-item timeline-item-transparent">
                                            <a href="idea.php?id=<?= $trending_idea_id; ?>" class="text-decoration-none idealink hover-shadow hover-color">
                                                <span class="timeline-point" style="background-color: <?= $trending_idea_category_color; ?>" ></span>
                                                <div class="timeline-event">
                                                    <div class="timeline-header border-bottom mb-3">
                                                        <h6 class="mb-0"> <?= $trending_idea_category_name; ?> </h6>
                                                        <small class="text-muted"> <?= $trending_idea_created_at; ?> </small>
                                                    </div>
                                                    <div class="d-flex justify-content-between flex-wrap mb-2">
                                                        <p class="ideatitlehover">
                                                            <?= $trending_idea_title; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="timeline-item timeline-item-transparent">
                                            <a href="idea.php?id=<?= $trending_idea_id; ?>" class="text-decoration-none idealink hover-shadow hover-color">

                                                <span class="timeline-point " style="background-color: <?= $trending_idea_category_color; ?>" ></span>
                                                <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0"> <?= $trending_idea_category_name; ?> </h6>
                                                    <small class="text-muted"> <?= $trending_idea_created_at; ?> </small>
                                                </div>
                                                <p class="ideatitlehover">
                                                    <?= $trending_idea_title; ?>
                                                </p>
                                                <hr />
                                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                                        <div class="d-flex flex-wrap">
                                                            <div class="avatar me-3">
                                                                <img src="<?= $trending_idea_user_profile; ?>" alt="Avatar" class="rounded-circle" />
                                                            </div>
                                                            <div>
                                                                <p class="text-secondary mb-0"> <?= $trending_idea_user_name; ?> </p>
                                                                <span class="text-muted"> <?= $trending_idea_position_name; ?> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php 
                                        endif;
                                        endforeach ?>

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
