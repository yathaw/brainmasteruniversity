<?php 
	require 'header.php';
    include("confs/config.php");

    $date = new DateTime();
    $today = $date->format('Y-m-d');

    $select="SELECT * from categories WHERE ideaenddate > '$today'";
    $category_query=mysqli_query($conn,$select);
    $category_count=mysqli_num_rows($category_query);
    unset($_SESSION['oldvalue']);
    if(isset($_POST["btnsave"]))
    {
        unset($_SESSION['oldvalue']);

        $titleErr = $categoryErr = $descriptionErr = $agreeErr = NULL; 
        $title = $category = $description = $agree = NULL; 

        $_SESSION['oldvalue']=array(); 

        $title=$_POST['title'];
        $category=$_POST['category'];
        $description=$_POST['description'];

        $_SESSION['oldvalue']['title'] = $title;
        $_SESSION['oldvalue']['category'] = $category;
        $_SESSION['oldvalue']['description'] = $description;
        $_SESSION['oldvalue']['agree'] = isset($_POST['agree']);
        $_SESSION['oldvalue']['anonymous'] = isset($_POST['anonymous']);


        $valid = true;
        if (empty($title)) {
            $titleErr = "Title field is required";
            $valid = false;
        }

        if (empty($category)) {
            $categoryErr = "Category field is required";
            $valid = false;
        }

        if (empty($description)) {
            $descriptionErr = "Description field is required";
            $valid = false;
        } 

        if (!isset($_POST['agree'])){  
            $agreeErr = "Accept terms of services before submit."; 
            $valid = false;
        } 
        
        if($valid){

            if(isset($_FILES['file']['name']))
            {
            
                $uploadFileDir = "upload/file/";

                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];
                $fileSize = $_FILES['file']['size'];
                $fileType = $_FILES['file']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                $allowedfileExtensions = array('pdf');

                if (in_array($fileExtension, $allowedfileExtensions))
                {
                    $dest_path = $uploadFileDir . $newFileName; 
                    if(move_uploaded_file($fileTmpPath, $dest_path)) 
                    {
                        $file_path = $dest_path;     
                    }
                    else
                    {
                        $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                        $_SESSION['store_reject'] = $message;
                        
                    }  
                }
                else{
                    $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
                    $_SESSION['store_reject'] = $message;
                }
            }
            else
            {
                $file_path = NULL;
            }
                
            $userid = $_SESSION['sess_user']['id'];
            if (isset($_POST['anonymous'])) {
                $anonymousStatus = $_POST['anonymous']; 
            }else{
                $anonymousStatus = 'off';
            }

            $description = mysqli_escape_string($conn, $description); 
            $title = mysqli_escape_string($conn, $title); 
            
            $insert = "INSERT INTO ideas(title, body, file, category_id, user_id, status) VALUES 
            ('$title','$description','$file_path','$category','$userid','$anonymousStatus')";
            $ret    = mysqli_query($conn, $insert);

            $ideaid = $conn->insert_id;

            header("location:mail/newpost.php/?id=$ideaid");

            if ($ret)
            {
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
                            window.location='idea_category_list.php'
                        })
                    </script>
                ";  

            }
            else
            {
                $error = mysqli_error($conn);
                $_SESSION['store_reject'] = $error;

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
                    <?php if ($category_count>0): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="d-inline-block">Create New Idea</h5>
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

                            <form action='idea_add.php' method='POST' class="form-sample" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12 col-12 ">
                                        
                                        <div class="row mb-3">
                                            <div class="col-12">                                             
                                                <label class="form-label" for="inputTitle"> Title </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class='bx bx-purchase-tag' ></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="inputTitle" placeholder="Enter Title Here" name="title" value="<?php if(isset($_SESSION['oldvalue']['title']) && !empty($_SESSION['oldvalue']['title']) ){ echo $_SESSION['oldvalue']['title']; } ?>" />
                                                </div>

                                                <span class="text-danger">
                                                    <?php if(isset($titleErr)){ echo $titleErr; } ?> 
                                                </span> 
                                            </div>
                                        </div>


                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label for="inputCategory" class="form-label">Category</label>
                                                <select class="select2" name="category" id="inputCategory" >
                                                    <option></option>
                                                    <?php 
                                                        $categories=mysqli_fetch_all($category_query);
                                                        foreach($categories as $key => $category):
                                                    ?>
                                                    <option value="<?= $category[0]; ?>" 
                                                        <?php if(isset($_SESSION['oldvalue']['category']) && !empty($_SESSION['oldvalue']['category']) ){
                                                            if($_SESSION['oldvalue']['category'] == $category[0]){
                                                                echo "selected";
                                                            }
                                                        } 
                                                        ?>
                                                    > 
                                                        <?= $category[1]; ?> 
                                                    </option>
                                                    <?php endforeach ?>
                                                </select>
                                                <span class="text-danger"><?php if(isset($categoryErr)){ echo $categoryErr; } ?> </span>  

                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label for="inputTitle" class="form-label d-block">File</label>
                                                <input type="file" name="file" id="file-to-upload" class="form-control">
                                                <div class="form-text">Please Upload PDF File.</div>

                                                <div id="preview-pdf-main-container">
                                                    <div id="preview-pdf-loader">Loading document ...</div>
                                                    <div id="preview-pdf-contents">
                                                        <div id="preview-pdf-meta">
                                                            <div id="preview-pdf-buttons">
                                                                <button id="preview-pdf-prev" class="btn btn-sm btn-outline-dark">Previous</button>
                                                                <button id="preview-pdf-next" class="btn btn-sm btn-outline-dark">Next</button>
                                                            </div>
                                                            <div id="preview-page-count-container">Page <div id="preview-pdf-current-page"></div> of <div id="preview-pdf-total-pages"></div></div>
                                                        </div>
                                                        <canvas id="preview-pdf-canvas" width="400"></canvas>
                                                        <div id="preview-page-loader">Loading page ...</div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12 col-12 ">
                                        <label for="inputBody" class="form-label">Description</label>
                                        <textarea id="inputBody" name="description" rows="8"><?php if(isset($_SESSION['oldvalue']['description']) && !empty($_SESSION['oldvalue']['description']) ){ echo $_SESSION['oldvalue']['description']; } ?></textarea>
                                        <span class="text-danger">
                                            <?php if(isset($descriptionErr)){ echo $descriptionErr; } ?> 
                                        </span>  

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="col-12">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="inputAnonymous" name="anonymous" value="<?php if(isset($_SESSION['oldvalue']['anonymous']) && !empty($_SESSION['oldvalue']['anonymous']) ){ echo 'checked'; } ?>"/>
                                                <label class="form-check-label" for="inputAnonymous">Anonymous Posting</label>
                                            </div>
                                        </div>
                                        <div class="col-12 form-check">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="agree" value="<?php if(isset($_SESSION['oldvalue']['aggree']) && !empty($_SESSION['oldvalue']['aggree']) ){ echo 'checked'; } ?>" >
                                            <label class="form-check-label" for="exampleCheck1">
                                                I've read and acccept the
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Terms & Conditions * </a>
                                            </label>
                                            <span class="text-danger d-block"><?php if(isset($agreeErr)){ echo $agreeErr; } ?> </span> 
                                        </div> 

                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button class="btn btn-secondary me-md-2" type="reset"> 
                                                <i class="fa-solid fa-arrow-rotate-right"></i> Cancel 
                                            </button>
                                            <button class="btn btn-primary" type="submit" name='btnsave'> 
                                                <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes 
                                            </button>
                                        </div>
                                    </div>
                                    
                                 </div>

                                
                            </form>
                        </div>
                    </div>

                    <?php else: ?>

                        <div class="card p-5">
                            <div class="card-body text-center">
                                <h2 class="mb-2 mx-2 "> You can't post at the moment :( </h2>

                                <p class="mb-4 mx-2"> The post cannot access to upload because the expiration date have passed. </p>
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
<script src="assets/vendor/pdfViewer/previewer.js"></script>

<script>
    $('#inputBody').summernote({
        height: 350,
    });

    $("#file-to-upload").on('change', function() {
        preview_showPDF(URL.createObjectURL($("#file-to-upload").get(0).files[0]));
    });

</script>