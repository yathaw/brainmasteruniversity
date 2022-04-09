<?php 
	require 'header.php';
    include("confs/config.php");

    $date = new DateTime();
    $today = $date->format('Y-m-d');

    $sess_userid = $_SESSION['sess_user']['id'];


    $id=$_GET['id'];
    $result = mysqli_query($conn,"SELECT ideas.*, categories.id as cid, categories.name as cname, categories.color as ccolor, categories.commentenddate as commentenddate, users.name as uname, users.profile as uprofile, positions.name as pname from ideas 
            LEFT JOIN categories ON ideas.category_id = categories.id
            LEFT JOIN users ON ideas.user_id = users.id
            LEFT JOIN position_user ON users.id = position_user.user_id
            LEFT JOIN positions ON position_user.position_id = positions.id
            WHERE ideas.id = $id");
    $row = mysqli_fetch_array($result);

    $like_select="SELECT * FROM reacts WHERE react = 1 AND idea_id = '$id'";
    $like_query=mysqli_query($conn,$like_select);
    $idea_like_total=mysqli_num_rows($like_query);
    $likes=mysqli_fetch_all($like_query);



    $dislike_select="SELECT * FROM reacts WHERE react = 0 AND idea_id = '$id'";
    $dislike_query=mysqli_query($conn,$dislike_select);
    $idea_dislike_total=mysqli_num_rows($dislike_query);

    $dislikes=mysqli_fetch_all($dislike_query);

    $like_users = array();
    foreach($likes as $like){
        array_push($like_users, $like[3]);
    }

    $dislike_users = array();
    foreach($dislikes as $dislike){
        array_push($dislike_users, $dislike[3]);
    }

    $comment_select="SELECT comments.*, users.id as uid, users.name as uname, users.profile as uprofile, positions.name as pname 
                    FROM comments 
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN position_user ON users.id = position_user.user_id
                    LEFT JOIN positions ON position_user.position_id = positions.id
                    WHERE comments.idea_id = '$id'
                    ORDER BY comments.created_at DESC
                    ";
    $comment_query=mysqli_query($conn,$comment_select);
    $comment_count=mysqli_num_rows($comment_query);
    $idea_comments=mysqli_fetch_all($comment_query);

    $category = $row['cname'];
    $title = $row['title'];
    $body = $row['body'];
    $color = $row['ccolor'];
    $file = $row['file'];
    $status = $row['status'];
    $created_at = $row['created_at'];
    $user = $row['uname'];
    $profile = $row['uprofile'];
    $position = $row['pname'];
    $commentenddate = $row['commentenddate'];

    $date = new DateTime();
    $today = $date->format('Y-m-d');


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
                    <div class="row gy-3">
                        <div class="<?php if(!empty($file)){ ?> col-xl-7 col-lg-7 <?php } ?> col-md-12 col-sm-12 col-12">
                            <div class="card overflow-hidden pb-4 mb-4" style="height: 660px">
                                <div class="card-header">
                                    <span class='badge' style="background-color:<?= $color; ?>"> <?= $category; ?> </span>

                                </div>
                                <div class="d-flex justify-content-between flex-wrap gap-2 px-4 mb-3">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="avatar me-3">
                                            <?php if($status == "off"):?>
                                                <img src="<?= $profile; ?>" alt="Avatar" class="rounded-circle" />
                                            <?php else: ?>
                                                <span class="avatar-initial rounded-circle bg-label-danger"> AN </span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <?php if($status == "off"):?>
                                                <p class="text-secondary mb-0"> <?= $user; ?> </p>
                                                <span class="text-muted"> <?= $position; ?> </span>
                                            <?php else: ?>
                                                Anonymous User
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <p> <?= time_since($created_at); ?> </p>
                                    </div>
                                </div>
                                <h5 class="px-4 text-dark"> <?= $title; ?> </h5>

                                <div class="card-body" id="vertical-example">
                                    <p class="card-text"> <?= $body; ?>  </p>
                                </div>

                                <div class="card-footer row align-items-center" >
                                    <?php
                                        if(in_array($sess_userid, $like_users)) { 
                                            $likeClassBtn = 'active redoBtn';
                                            $likeAttr = 'data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="Tap to undo."' ;
                                            $dislikeDisable = 'disabled';
                                        }else{ 
                                            $likeClassBtn =  'reactBtn';  
                                        }

                                        if(in_array($sess_userid, $dislike_users)) { 
                                            $dislikeClassBtn = 'active redoBtn';
                                            $dislikeAttr = 'data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="Tap to undo."' ;
                                            $likeDisable = 'disabled';
                                        }else{ 
                                            $dislikeClassBtn =  'reactBtn';  
                                        }
                                    ?>
                                    <div class="d-flex justify-content-between flex-wrap gap-2 px-4 mb-3">

                                        <div class="">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-success <?= $likeClassBtn; ?>" data-react="1" 
                                                    <?php if(isset($likeAttr)){ echo $likeAttr; } ?> 
                                                    <?php if(isset($likeDisable)){ echo $likeDisable; } ?>

                                                >
                                                    <i class="bx bx-like"></i> 
                                                </button>

                                                <button type="button" class="btn btn-outline-danger <?= $dislikeClassBtn; ?>" data-react="0" 
                                                    <?php if(isset($dislikeAttr)){ echo $dislikeAttr; } ?> 
                                                    <?php if(isset($dislikeDisable)){ echo $dislikeDisable; } ?>
                                                >
                                                    <i class="bx bx-dislike"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="article-votes " >
                                            <div class="btn-group pe-none ">
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="bx bxs-like"></i> Like
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm"> 
                                                    <span class="likeCount"> <?= $idea_like_total; ?> </span>
                                                </button>
                                            </div>

                                            <div class="btn-group pe-none" role="group" aria-label="Basic outlined example">
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    <i class="bx bxs-dislike"></i> Dislike
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" disabled> 
                                                    <span class="dislikeCount"> <?= $idea_dislike_total; ?> </span>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    
                                </div>


                            </div>
                        </div>

                        <div class="<?php if(empty($file)){ ?> d-none <?php } ?> col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 ">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div id="pdf-main-container">
                                        <div id="pdf-contents">
                                            <div id="pdf-meta">
                                                <div id="pdf-buttons">
                                                    <button id="pdf-prev" class="btn btn-sm btn-outline-dark">Previous</button>
                                                    <button id="pdf-next" class="btn btn-sm btn-outline-dark">Next</button>
                                                </div>
                                                <div id="page-count-container" class="">Page <div id="pdf-current-page"></div> of <div id="pdf-total-pages"></div></div>
                                            </div>
                                            <canvas id="pdf-canvas"></canvas>
                                            <div id="page-loader">Loading page ...</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    
                    <div class="row gy-3">
                        <div class="col-12">
                            <div class="accordion" id="accordionExample">
                                <div class="card accordion-item mb-4">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">  <span class="badge bg-label-primary me-2"> <?= $comment_count ?> </span> Comentarios </button>
                                    </h2>
                                    <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body"> 
                                            <?php 
                                                foreach($idea_comments as $idea_comment):

                                                $comment_id = $idea_comment[0];
                                                $comment_body = $idea_comment[1];

                                                $comment_status = $idea_comment[2];
                                                $comment_created_at = time_since($idea_comment[5]);

                                                $comment_userid = $idea_comment[7];
                                                $comment_user = $idea_comment[8];
                                                $comment_userprofile = $idea_comment[9];
                                                $comment_userposiiton = $idea_comment[10];

                                                $session_user = $_SESSION['sess_user']['id'];


                                            ?>

                                            <div class="col-12">
                                                <div class="d-flex justify-content-between flex-wrap gap-2 px-4 mb-3">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div class="avatar me-3">
                                                            <?php if($comment_status == "off"):?>
                                                                <img src="<?= $comment_userprofile; ?>" alt="Avatar" class="rounded-circle" />
                                                            <?php else: ?>
                                                                <span class="avatar-initial rounded-circle bg-label-danger"> AN </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <?php if($comment_status == "off"):?>
                                                                <p class="text-secondary mb-0"> <?= $comment_user; ?> </p>
                                                                <span class="text-muted"> <?= $comment_userposiiton; ?> </span>
                                                            <?php else: ?>
                                                                Anonymous User
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p> <?= time_since($comment_created_at); ?> 
                                                        </p>
                                                        <?php if($comment_userid == $session_user): ?>
                                                        <div>
                                                            
                                                            <button class="btn btn-outline-warning btn-sm <?php if($today <= $commentenddate){ echo 'commenteditBtn'; } ?>" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"title="Edit Comment" data-id="<?= $comment_id; ?>" data-status="<?= $comment_status; ?>" data-body="<?= $comment_body; ?>" <?php if($today > $commentenddate){ echo 'disabled'; } ?> >
                                                                <i class='bx bx-edit-alt' ></i>
                                                            </button>
                                                            <button class="btn btn-outline-danger btn-sm <?php if($today <= $commentenddate){ echo 'commentremoveBtn'; } ?>  " data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"title="Remove Comment" data-id="<?= $comment_id; ?>" <?php if($today > $commentenddate){ echo 'disabled'; } ?> > 
                                                                <i class='bx bx-trash' ></i> 
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                                <span class="text-muted"> <?= $comment_body; ?> </span>
                                                <hr>
                                            </div>

                                            
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <?php if($today <= $commentenddate): ?>
                    <div class="row gy-3">
                        <div class="col-12">
                            <div class="card mb-4 bg-lighter">
                                <div class="card-body">
                                    <label for="inputComment" class="form-label">Comment Box</label>
                                    <textarea class="form-control" id="inputComment" rows="3"></textarea>
                                    <input type="hidden" name="id" id="inputCommentid">
                                    <div class="row mt-3">
                                        <div class="col-8">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="inputAnonymous" name="status" />
                                                <label class="form-check-label" for="inputAnonymous">Anonymous Posting</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button class="btn btn-primary commentBtn" type="button"> Add Comment </button>
                                            </div>

                                        </div>
                                    </div>
                                        
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php else: ?>

                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="card p-5">
                                    <div class="card-body text-center">
                                        <h2 class="mb-2 mx-2 "> You can't comment at the moment :( </h2>

                                        <p class="mb-4 mx-2"> The comment cannot access to upload because the expiration date have passed. </p>
                                        <div class="row justify-content-center">
                                            <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 col-12 text-center">
                                                <img src="assets/img/empty.png" class="img-fluid">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endif ?>
                    
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
    var url = "<?= $file; ?>";
    console.log(url);
</script>

<script src="assets/vendor/pdfViewer/viewer.js"></script>

<script type="text/javascript">
    
    $('.commentBtn').on('click', function(e) {
        var commentid = $('#inputCommentid').val();
        var comment = $('#inputComment').val();
        var status = $('#inputAnonymous').is(':checked');
        var ideaid = "<?= $id; ?>";

        console.log(status);

        if(status){
            var status = 'on';
        }else{
            var status = 'off';
        }

        console.log(status);
        
        $.ajax({
            url:'comment_add.php',
            data:{commentid:commentid, comment:comment, status:status, ideaid:ideaid},
            type:'POST',
            success:function(data){
                console.log(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Your submission has been saved!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(function(){
                    window.location.reload();

                })

            },
            error:function(data){
                //Error Message == 'Title', 'Message body', Last one leave as it is
                // swal("Oops...", "Something went wrong :(", "error");
            }
        });

    });

    $('.reactBtn').on('click',function(e){
        var react = $(this).data("react");
        var status = 'react';

        var ideaid = "<?= $id; ?>";
                    
        reactStore(react, status, ideaid)        
    });

    $('.redoBtn').on('click',function(e){
        var react = $(this).data("react");
        var status = 'redo';

        var ideaid = "<?= $id; ?>";
                    
        reactStore(react, status, ideaid)        
    });

    function reactStore(react, status, ideaid){

        if(status == 'react'){

            var reactDiv = `<div class='center'>
                            <i class="thumb bx bxs-like"></i>
                            <div class="circle-wrap">
                                <div class="circle-lg"></div>
                            </div>
                            <div class="dots-wrap">
                                <div class="dot dot--t"></div>
                                <div class="dot dot--tr"></div>
                                <div class="dot dot--br"></div>
                                <div class="dot dot--b"></div>
                                <div class="dot dot--bl"></div>
                                <div class="dot dot--tl"></div>
                                </div> 
                        </div>
                        <div>
                            <h3 class="text-center"> You Like This Idea </h3>
                        </div>`;

            $.ajax({
                url:'react_add.php',
                data:{react:react, status:status, ideaid:ideaid},
                type:'POST',
                success:function(data){
                    
                    console.log(data);
                    Swal.fire({
                        html: reactDiv,
                        customClass: 'swal-height',
                        imageHeight: 1500,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        allowOutsideClick: false
                    }).then(function(){
                        window.location.reload();

                    })

                },
                error:function(data){
                    //Error Message == 'Title', 'Message body', Last one leave as it is
                    swal("Oops...", "Something went wrong :(", "error");
                }
            });
        }else{
            Swal.fire({
                title: 'Undo your reaction to this idea?',
                text: 'Only he can see how you reacted, but you can also remove your reactions.',
                icon: "question",
                showCancelButton:true,
                confirmButtonText: 'Yes, Undo it!',
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
                            url:'react_add.php',
                            data:{react:react, status:status, ideaid:ideaid},
                            type:'POST',
                            success:function(data){
                                
                                console.log(data);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Removed!',
                                    text: 'Undo a reaction successfully!',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    allowOutsideClick: false
                                }).then(function(){
                                    window.location.reload();

                                })

                            },
                            error:function(data){
                                //Error Message == 'Title', 'Message body', Last one leave as it is
                                swal("Oops...", "Something went wrong :(", "error");
                            }
                        });
                        
                    }
                    else
                    {
                        Swal.fire({
                            icon: "info",
                            title: "Changes are not saved",
                            showConfirmButton: false,
                            timer : 1500
                        });
                        
                    }
                })
        }
        

        

        
    }

    $('.commentremoveBtn').on('click', function(e) {
        var commentid = $(this).data("id");

        Swal.fire({
            title: 'You are about to delete a comment',
            html: 'This will delete your comment from this idea. <br> Are you Sure?',
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
                        url:'comment_delete.php',
                        data:{commentid:commentid},
                        type:'POST',
                        success:function(data){
                            console.log(data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Comment removed successfully!',
                                showConfirmButton: false,
                                timer: 2000
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
                        timer : 1500
                    });
                    
                }
            })
    });

    $('.commenteditBtn').on('click', function(e) {

        var id = $(this).data("id");
        var status = $(this).data("status");
        var body = $(this).data("body");

        if(status =="off"){
            $('#inputAnonymous').prop('checked', false);
        }

        $('#inputComment').val(body);
        $('#inputCommentid').val(id);
        window.scrollTo({
            top: $('#inputComment').offset().top,
            left: 0,
            behavior: 'smooth'
        })

        $('#inputComment').focus();

    });


</script>
