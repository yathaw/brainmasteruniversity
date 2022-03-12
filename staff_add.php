<?php 
	require 'header.php';
    include("confs/config.php");

    $position_sql="SELECT * from positions";
    $position_query=mysqli_query($conn,$position_sql);
    $positions=mysqli_fetch_all($position_query);

    if(isset($_POST["btnsave"]))
    {
        unset($_SESSION['oldvalue']);
        unset($_SESSION['store_reject']);

        $statusErr = null; $imgErr = null; $genderErr = null; 
        $nameErr = null; $positionErr = null;
        $emailErr = null; $passwordErr = null; $phoneErr = null; $dobErr = null; $jodErr = null; $addressErr = null;

        $_SESSION['oldvalue']=array(); 

        $valid = true;

        $status=$_POST['status'];
        $gender=$_POST['gender'];

        $name=$_POST['name'];
        $positionid=$_POST['positionid'];
        $email=$_POST['email'];
        $password=$_POST['password'];
        $phone=$_POST['phone'];
        $dob=$_POST['dob'];
        $jod=$_POST['jod'];
        $address=$_POST['address'];


        $_SESSION['oldvalue']['status'] = $status;
        $_SESSION['oldvalue']['positionid'] = $positionid;
        $_SESSION['oldvalue']['gender'] = $gender;
        $_SESSION['oldvalue']['name'] = $name;
        $_SESSION['oldvalue']['email'] = $email;
        $_SESSION['oldvalue']['phone'] = $phone;
        $_SESSION['oldvalue']['dob'] = $dob;
        $_SESSION['oldvalue']['jod'] = $jod;
        $_SESSION['oldvalue']['address'] = $address;

        if (empty($name)) {
            $nameErr = 'The name field is required.';
            $valid = false;
        }

        if (empty($positionid)) {
            $positionErr = 'Please select at least one option';
            $valid = false;
        }

        if (empty($email)) {
            $emailErr = 'Please enter email address in format: staffname@example.com';
            $valid = false;
        }

        if (empty($password)) {
            $passwordErr = 'Please enter password.';
            $valid = false;
        }

        if (empty($phone)) {
            $phoneErr = 'The phone number field is required.';
            $valid = false;
        }

        if (empty($dob)) {
            $dobErr = 'The date of birth field is required.';
            $valid = false;
        }

        if (empty($jod)) {
            $jodErr = 'The start date of job field is required.';
            $valid = false;
        }

        if (empty($address)) {
            $addressErr = 'The address field is required.';
            $valid = false;
        }

        if (empty($_FILES['img']['name'])) {
            $imgErr = 'The file you upload seems to be empty. Please check whether you really want to upload this file.';
            $valid = false;
        }

        if($_POST["status"] == "on"){
            $status = "Active";
        }else{
            $status = "Inactive";
        }
        
        if($valid){
            

            $user_query=mysqli_query($conn,"SELECT * FROM users WHERE name LIKE '%$name%' OR email LIKE '%$email%' ");  
            $user_numrows=mysqli_num_rows($user_query); 
            if($user_numrows <= 0){

                if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK)
                {
                
                    $uploadFileDir = "upload/img/";

                    $fileTmpPath = $_FILES['img']['tmp_name'];
                    $fileName = $_FILES['img']['name'];
                    $fileSize = $_FILES['img']['size'];
                    $fileType = $_FILES['img']['type'];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));

                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                    $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');

                    if (in_array($fileExtension, $allowedfileExtensions))
                    {
                        $dest_path = $uploadFileDir . $newFileName; 
                        if(move_uploaded_file($fileTmpPath, $dest_path)) 
                        {
                            $users_sql = "INSERT INTO users(name, profile, email, password, gender, phone, address, status, joindate, dob) VALUES ('$name','$dest_path','$email','$password','$gender','$phone','$address','$status','$jod', '$dob')";
                            mysqli_query($conn, $users_sql);

                            $userid = $conn->insert_id;

                            $positionuser_sql = "INSERT INTO position_user(user_id, position_id) VALUES ('$userid','$positionid')";
                            mysqli_query($conn, $positionuser_sql);

                            unset($_SESSION['oldvalue']);
                            unset($_SESSION['store_reject']);

                            echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Store Successful',
                                        text: 'You saved one staff.',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        allowOutsideClick: false

                                    }).then(function(){
                                        window.location='staff_list.php'
                                    })
                                </script>
                            ";
                             
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
                    $message = 'There is some error in the file upload. Please check the following error.<br>';
                    $message .= 'Error:' . $_FILES['uploadedFile']['error'];

                    $_SESSION['store_reject'] = $message;

                }
                

                
            } 
            else{ 
                $row = mysqli_fetch_array($category_query);

                if($email == $row['email']){
                    $emailErr = "The staff email already exists!";
                }

                if ($name == $row['name']) {
                    $nameErr = "The staff name already exists!";
                }
                $_SESSION['store_reject'] = "Sorry! Duplicate Record. All must be unique. Please try another one!";
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
                            <h5 class="d-inline-block">Create New Staff</h5>
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

                            <form action="staff_add.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <label class="custom-control ios-switch">
                                                    <span class="ios-switch-control-description">Off</span>
                                                    <input type="checkbox" class="ios-switch-control-input" checked name="status">
                                                    <span class="ios-switch-control-indicator"></span>
                                                    <span class="ios-switch-control-description">On</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-8 uploader">
                                                <input type="file" name="img">
                                                <img src="" alt="">

                                                <p class="text-danger">
                                                    <?php if (isset($imgErr)) { echo $imgErr; } ?>
                                                </p>
                                            </div>

                                        </div>
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-8 mx-auto align-item-center">
                                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" name="gender" id="btnradio1" value="Male" <?php if(isset($_SESSION['oldvalue']['gender']) && !empty($_SESSION['oldvalue']['gender'] =="Male") ){ echo "checked"; } ?> <?php if (empty($_SESSION['oldvalue'])) { echo "checked"; } ?> />
                                                    <label class="btn btn-outline-dark" for="btnradio1"> Male </label>
                                                    <input type="radio" class="btn-check" name="gender" id="btnradio2" value="Female" <?php if(isset($_SESSION['oldvalue']['gender']) && !empty($_SESSION['oldvalue']['gender'] =="Female") ){ echo "checked"; } ?>/>
                                                    <label class="btn btn-outline-dark" for="btnradio2">Female</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                                        <div class="row mb-3">

                                            <div class="col-xl-8 col-lg-8 col-md-6 col-12 form-group">
                                                <label class="form-label" for="inputName">Full Name</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="inputName" placeholder="John Doe" name="name" autofocus value="<?php if(isset($_SESSION['oldvalue']['name']) && !empty($_SESSION['oldvalue']['name']) ){ echo $_SESSION['oldvalue']['name']; } ?>" />
                                                </div>

                                                <p class="text-danger">
                                                    <?php if (isset($nameErr)) { echo $nameErr; } ?>
                                                </p>
                                            </div>

                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 form-group">
                                                <label for="inputosition" class="mb-2">Position</label>
                                                <select class="select2 " name="positionid">
                                                    <option></option>
                                                    <?php 
                                                        foreach($positions as $key => $position):
                                                    ?>
                                                    <option value="<?= $position[0]; ?>" 
                                                        <?php if(isset($_SESSION['oldvalue'])) { 
                                                            if($_SESSION['oldvalue']['positionid'] == $position[0]) {
                                                                echo "selected"; 
                                                            } 
                                                        } 
                                                        ?> 
                                                    > 
                                                        <?= $position[1]; ?> 
                                                    </option>
                                                    <?php endforeach ?>
                                                </select>

                                                <p class="text-danger">
                                                    <?php if (isset($positionErr)) { echo $positionErr; } ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group">
                                                <label class="form-label" for="inputEmail">Email</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-envelope"></i>
                                                    </span>
                                                    <input type="text" id="inputEmail" class="form-control" placeholder="john.doe@example.com" name="email" value="<?php if(isset($_SESSION['oldvalue']['email']) && !empty($_SESSION['oldvalue']['email']) ){ echo $_SESSION['oldvalue']['email']; } ?>"/>
                                                </div>

                                                <p class="text-danger">
                                                    <?php if (isset($emailErr)) { echo $emailErr; } ?>
                                                </p>
                                            </div>

                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group form-password-toggle">
                                                <label class="form-label" for="inputPassword">Password</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class='bx bx-key' ></i>
                                                    </span>
                                                    <input type="password" id="inputPassword" class="form-control " name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" name="password"/>
                                                    <span class="input-group-text cursor-pointer">
                                                        <i class="bx bx-hide"></i>
                                                    </span>
                                                </div>

                                                <p class="text-danger">
                                                    <?php if (isset($passwordErr)) { echo $passwordErr; } ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 form-group">
                                                <label class="form-label" for="inputPhone">Phone No</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-phone"></i>
                                                    </span>
                                                    <input type="text" id="inputPhone" class="form-control" placeholder="658 799 8941" name="phone" value="<?php if(isset($_SESSION['oldvalue']['phone']) && !empty($_SESSION['oldvalue']['phone']) ){ echo $_SESSION['oldvalue']['phone']; } ?>"/>
                                                </div>
                                                <p class="text-danger">
                                                    <?php if (isset($phoneErr)) { echo $phoneErr; } ?>
                                                </p>
                                            </div>

                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 form-group">
                                                <label class="form-label" for="inputDob">Date Of Birth</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class='bx bx-cake' ></i>
                                                    </span>
                                                    <input type="text" id="inputDob" class="form-control " placeholder="YYYY-MM-DD" name="dob" value="<?php if(isset($_SESSION['oldvalue']['dob']) && !empty($_SESSION['oldvalue']['dob']) ){ echo $_SESSION['oldvalue']['dob']; } ?>"/>
                                                </div>
                                                <p class="text-danger">
                                                    <?php if (isset($dobErr)) { echo $dobErr; } ?>
                                                </p>
                                            </div>

                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 form-group">
                                                <label class="form-label" for="inputJod">Joined Date</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class='bx bx-calendar-star' ></i>
                                                    </span>
                                                    <input type="text" id="inputJod" class="form-control" placeholder="YYYY-MM-DD" name="jod" value="<?php if(isset($_SESSION['oldvalue']['jod']) && !empty($_SESSION['oldvalue']['jod']) ){ echo $_SESSION['oldvalue']['jod']; } ?>"/>
                                                </div>

                                                <p class="text-danger">
                                                    <?php if (isset($jodErr)) { echo $jodErr; } ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="inputAddress">Address</label>
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text">
                                                    <i class='bx bx-map' ></i>
                                                </span>
                                                <textarea id="inputAddress" class="form-control" placeholder="Hi, Do you have a moment to talk Joe?" name="address"><?php if(isset($_SESSION['oldvalue']['address']) && !empty($_SESSION['oldvalue']['address']) ){ echo $_SESSION['oldvalue']['address']; } ?></textarea>
                                            </div>
                                            <p class="text-danger">
                                                <?php if (isset($addressErr)) { echo $addressErr; } ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-secondary me-md-2" type="reset">
                                        <i class='bx bx-refresh'></i> Reset </button>
                                    <button class="btn btn-primary" type="submit" name='btnsave'>
                                        <i class='bx bx-save'></i>Save Changes </button>
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

    $("#inputDob").datepicker({
        endDate:now,
        format:'yyyy-MM-dd',
        pickedClass: 'picked',
        highlightedClass: 'highlighted',
        mutedClass: 'muted',
        autoHide:true,

    });


    $("#inputJod").datepicker({
        endDate:now,
        format:'yyyy-MM-dd',
        pickedClass: 'picked',
        highlightedClass: 'highlighted',
        mutedClass: 'muted',
        autoHide:true,

    });

    if(document.querySelector('div.uploader input')){
        document.querySelector('div.uploader input').addEventListener('change', e => {
            document.querySelector('div.uploader img').src = URL.createObjectURL(e.target.files[0]);

        });
    }

</script>