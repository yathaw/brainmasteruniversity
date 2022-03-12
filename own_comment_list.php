<?php 
	require 'header.php';
    include("confs/config.php");
    $date = new DateTime();
    $today = $date->format('Y-m-d');


    $sess_userid = $_SESSION['sess_user']['id'];


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

    $result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM `comments` WHERE `user_id` = $sess_userid");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    
    $second_last = $total_no_of_pages - 1; // total page minus 1

    $own_comments_select="SELECT comments.*, ideas.title as title, ideas.status as ideastatus, users.id as uid, users.name as uname, 
                    users.profile as uprofile, positions.name as pname 
                    FROM comments 
                    LEFT JOIN ideas ON comments.idea_id = ideas.id
                    LEFT JOIN users ON ideas.user_id = users.id
                    LEFT JOIN position_user ON users.id = position_user.user_id
                    LEFT JOIN positions ON position_user.position_id = positions.id
                    WHERE comments.user_id = '$sess_userid'
                    ORDER BY comments.created_at DESC";
    $own_comments_query=mysqli_query($conn,$own_comments_select);
    $own_comments_count=mysqli_num_rows($own_comments_query);

    $own_comments=mysqli_fetch_all($own_comments_query);
?>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require 'app_sidebar.php' ?>

            <div class="layout-page">
                <?php require 'app_header.php' ?>

                <div class="container-xxl flex-grow-1 container-p-y">
                    <?php if($own_comments_count > 0): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="d-inline-block"> Own comment </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="timeline">

                                    <?php 
                                        foreach($own_comments as $key => $own_comment):

                                        $comment_id = $own_comment[0];
                                        $comment_body = $own_comment[1];

                                        $comment_status = $own_comment[2];
                                        $comment_idea_id = $own_comment[3];

                                        $comment_created_at = time_since($own_comment[5]);
                                        
                                        $comment_ideas = $own_comment[7];
                                        $comment_idea_status = $own_comment[8];

                                        $comment_userid = $own_comment[9];
                                        $comment_user = $own_comment[10];
                                        $comment_userprofile = $own_comment[11];
                                        $comment_userposiiton = $own_comment[12];

                                        $session_user = $_SESSION['sess_user']['id'];

                                        if (strlen($comment_ideas) > 120){
                                            $own_comment_idea_title = substr($comment_ideas, 0, 120).'...';
                                        }
                                        else{
                                            $own_comment_idea_title = $comment_ideas;
                                        }

                                    ?>

                                    <li class="timeline-item timeline-item-dark mb-4">
                                        <?php if($comment_status == "on"): ?>
                                            <span class="timeline-indicator timeline-indicator-danger">
                                                <i class='bx bx-hide' ></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="timeline-indicator timeline-indicator-primary">
                                                <i class='bx bx-show'></i>
                                            </span>
                                        <?php 
                                            endif;
                                        ?>
                                        <div class="timeline-event">
                                            <div class="timeline-header">
                                                <h6 class="mb-0"><?= $comment_created_at; ?></h6>
                                                <a href="idea.php?id=<?= $comment_idea_id; ?>" class="btn btn-primary btn-sm my-sm-0 my-3"> View </a>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                                                    <div class="d-flex flex-wrap align-items-center w-100">
                                                        <?= $comment_body; ?>
                                                    </div>
                                                    
                                                </li>
                                              
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap pb-0 px-0">
                                                    <div class="d-flex flex-sm-row flex-column align-items-center">
                                                        <?php if($comment_idea_status == "off"): ?>
                                                            <img src="<?= $comment_userprofile; ?>" class="rounded-circle me-3" alt="avatar" height="24" width="24" />
                                                        <?php else: ?>
                                                            <span class="avatar-initial rounded-circle bg-label-danger"> AN </span>
                                                        <?php endif; ?>

                                                        <div class="user-info">
                                                                <p class="my-0"> 
                                                                    <?php if($comment_idea_status == "off"): ?>

                                                                    <?= $comment_user; ?>
                                                                    <?php else: ?>
                                                                        Anonymous User
                                                                    <?php endif; ?>
                                                                     

                                                                </p>



                                                            <span class="text-muted"> <?= $own_comment_idea_title; ?> </span>
                                                        </div>


                                                    </div>
                                                </li>
                                          </ul>
                                      </div>
                                  </li>
                                    <?php
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

                    <?php else: ?>

                        <div class="card p-5">
                            <div class="card-body text-center">
                                <h2 class="mb-2 mx-2 "> There is no your own comment post. :( </h2>

                                <p class="mb-4 mx-2"> More comments for you will be shown here as your suggest comment are uploaded, so check back soon. </p>
                                <div class="row justify-content-center">
                                    <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 col-12 text-center">
                                        <img src="assets/img/empty.png" class="img-fluid">
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endif; ?>

                    
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
