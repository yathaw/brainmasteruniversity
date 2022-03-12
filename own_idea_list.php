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

    $result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM `ideas` WHERE `user_id` = $sess_userid");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    
    $second_last = $total_no_of_pages - 1; // total page minus 1

    $own_ideas_select="SELECT ideas.*, categories.id as cid, categories.name as cname, categories.color as ccolor, users.name as uname, users.profile as uprofile, positions.name as pname from ideas 
            LEFT JOIN categories ON ideas.category_id = categories.id
            LEFT JOIN users ON ideas.user_id = users.id
            LEFT JOIN position_user ON users.id = position_user.user_id
            LEFT JOIN positions ON position_user.position_id = positions.id
            WHERE ideas.user_id = $sess_userid
            ORDER BY ideas.created_at DESC
            LIMIT $offset, $total_records_per_page";
    $own_ideas_query=mysqli_query($conn,$own_ideas_select);
    $own_ideas_count=mysqli_num_rows($own_ideas_query);

    $own_ideas=mysqli_fetch_all($own_ideas_query);
?>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require 'app_sidebar.php' ?>

            <div class="layout-page">
                <?php require 'app_header.php' ?>

                <div class="container-xxl flex-grow-1 container-p-y">
                    <?php if($own_ideas_count > 0): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="d-inline-block"> Own Idea </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="timeline">

                                    <?php 
                                        foreach($own_ideas as $key => $own_idea):

                                        $own_idea_id = $own_idea[0];
                                        $own_idea_name = $own_idea[1];
                                        $own_idea_body = $own_idea[2];
                                        $own_idea_file = $own_idea[3];
                                        $own_idea_category_id = $own_idea[4];
                                        $own_idea_user_id = $own_idea[5];
                                        $own_idea_status = $own_idea[6];
                                        $own_idea_created_at = time_since($own_idea[7]);
                                        
                                        $own_idea_category_id = $own_idea[9];
                                        $own_idea_category_name = $own_idea[10];
                                        $own_idea_category_color = $own_idea[11];

                                        $own_idea_user_name = $own_idea[12];
                                        $own_idea_user_profile = $own_idea[13];
                                        $own_idea_position = $own_idea[14];


                                        $own_idea_body_decode = html_entity_decode($own_idea_body);
                                        $own_ideas_body_removeHTMLtag = strip_tags($own_idea_body_decode);

                                        if (strlen($own_ideas_body_removeHTMLtag) > 120){
                                            $own_idea_body_limit = substr($own_ideas_body_removeHTMLtag, 0, 120).'...';
                                        }
                                        else{
                                            $own_idea_body_limit = $own_ideas_body_removeHTMLtag;
                                        }

                                        if (strlen($own_idea_name) > 120){
                                            $own_idea_title = substr($own_idea_name, 0, 120).'...';
                                        }
                                        else{
                                            $own_idea_title = $own_idea_name;
                                        }

                                        

                                    ?>

                                    <li class="timeline-item timeline-item-transparent">
                                        <?php if($own_idea_status == "on"): ?>
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
                                                <span class="mb-0 badge" style="background-color: <?= $own_idea_category_color; ?> "> <?= $own_idea_category_name; ?> </span>
                                                <small class="text-muted"> <?= $own_idea_created_at; ?> </small>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                                                    
                                                    <p class="ideatitlehover">
                                                        <?= $own_idea_title; ?>
                                                    </p>
                                                    <div>
                                                        <a href="idea.php?id=<?= $own_idea_id; ?>" class="btn btn-primary btn-sm my-sm-0 my-3" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Detail">
                                                            <i class='bx bx-link' ></i>
                                                        </a>

                                                        <a href="idea_edit.php?id=<?= $own_idea_id; ?>" class="btn btn-warning btn-sm my-sm-0 my-3" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Edit"> 
                                                            <i class='bx bxs-edit-alt' ></i> 
                                                        </a>

                                                        <button type="button" class="btn btn-danger btn-sm my-sm-0 my-3 removeBtn" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="bottom" title="Remove" data-id="<?= $own_idea_id; ?>"> 
                                                            <i class='bx bx-x' ></i> 
                                                        </button>
                                                    </div>
                                                </li>
                                                <?php 
                                                    $comment_select="SELECT comments.*, users.id as uid, users.name as uname, users.profile as uprofile, positions.name as pname 
                                                        FROM comments 
                                                        LEFT JOIN users ON comments.user_id = users.id
                                                        LEFT JOIN position_user ON users.id = position_user.user_id
                                                        LEFT JOIN positions ON position_user.position_id = positions.id
                                                        WHERE comments.idea_id = '$own_idea_id'
                                                        AND comments.user_id != '$sess_userid'
                                                        ORDER BY comments.created_at DESC
                                                        ";
                                                    $comment_query=mysqli_query($conn,$comment_select);
                                                    $comment_count=mysqli_num_rows($comment_query);
                                                    $idea_comments=mysqli_fetch_all($comment_query);

                                                    if($comment_count > 0){
                                                ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap pb-0 px-0">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <ul class="list-unstyled users-list d-flex align-items-center avatar-group m-0 my-3 me-2">
                                                            <?php 
                                                                foreach($idea_comments as $idea_comment):

                                                                $comment_user = $idea_comment[8];
                                                                $comment_userprofile = $idea_comment[9];

                                                            ?>
                                                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="<?= $comment_user; ?>" class="avatar avatar-xs pull-up">
                                                                <img class="rounded-circle" src="<?= $comment_userprofile; ?>" alt="Avatar" />
                                                            </li>

                                                            <?php endforeach; ?>
                                                            
                                                        </ul>
                                                        <span>Commented on your post.</span>
                                                    </div>
                                                </li>
                                                <?php } ?>
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
                                <h2 class="mb-2 mx-2 "> There is no your own idea post. :( </h2>

                                <p class="mb-4 mx-2"> More ideas for you will be shown here as your suggest idea are uploaded, so check back soon. </p>
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

<script type="text/javascript">
    $('.removeBtn').on('click', function(e) {
        var id = $(this).data("id");

        Swal.fire({
            title: 'You are about to delete a role',
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
                        url:'idea_delete.php',
                        data:{id:id},
                        type:'POST',
                        success:function(data){
                            console.log(data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Role removed successfully!',
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