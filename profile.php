<?php 
	require 'header.php';
    include("confs/config.php");

    $sess_userid = $_SESSION['sess_user']['id'];

    $result = mysqli_query($conn,"SELECT * FROM `users` WHERE `id` = $sess_userid");
    $row = mysqli_fetch_array($result);

    $profile = $row['profile'];
    $name = $row['name'];
    $email = $row['email'];
    $oldpassword = $row['password'];
    $gender = $row['gender'];
    $dob = $row['dob'];
    $phone = $row['phone'];
    $address = $row['address'];
    $jod = $row['joindate'];

    if(isset($_POST["btnsave"]))
    {
        $statusErr = null; $imgErr = null; $genderErr = null; 
        $nameErr = null;
        $emailErr = null; $passwordErr = null; $phoneErr = null; $dobErr = null; $jodErr = null; $addressErr = null;

        $valid = true;

        $gender=$_POST['gender'];
        $name=$_POST['name'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];
        $dob=$_POST['dob'];
        $jod=$_POST['jod'];
        $address=$_POST['address'];

        if (empty($name)) {
            $nameErr = 'The name field is required.';
            $valid = false;
        }


        if (empty($email)) {
            $emailErr = 'Please enter email address in format: staffname@example.com';
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

        if($valid){
            
            if (isset($_POST['password'])) {
                $password = $_POST['password'];
            }else{
                $password = $oldpassword;
            }

            $user_sql = "UPDATE users SET name='$name', gender='$gender', email='$email', password='$password', phone='$phone', dob='$dob', joindate='$jod', address='$address' WHERE id='$sess_userid' ";
            mysqli_query($conn, $user_sql);

            $_SESSION['store_success'] = "Your profile was updated!";
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Store Successful',
                        text: 'You saved the category.',
                        showConfirmButton: false,
                        timer: 2000,
                        allowOutsideClick: false

                    }).then(function(){
                        window.location='profile.php'
                    })
                </script>
            "; 
            

        }
    }

?>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php require 'app_sidebar.php' ?>

            <div class="layout-page">
                <?php require 'app_header.php' ?>

                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>

                        <?php if(isset($_SESSION['store_success']) && !empty($_SESSION['store_success']) ): ?>

                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </symbol>
                                </svg>
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                
                                <?= $_SESSION['store_success']; ?>
                                
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                        <?php 
                            endif;
                            unset($_SESSION['store_success']);

                        ?>
                        <!-- Account -->
                        <form id="formAccountSettings" method="POST" action="profile.php">
                            <input type="hidden" name="oldpassword" value="<?= $oldpassword; ?>">
                            <div class="card-body">
                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                    <img src="<?= $profile; ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input type="file" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" name="img" />
                                        </label>
                                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4" onClick="window.location.reload();">
                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>
                                        <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0" />
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row mb-3 align-items-center">

                                            <div class="col-xl-8 col-lg-8 col-md-6 col-12 form-group ">
                                                <label class="form-label" for="inputName">Full Name</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="inputName" placeholder="John Doe" name="name" autofocus value="<?= $name; ?>" />
                                                </div>

                                                <p class="text-danger">
                                                    <?php if (isset($nameErr)) { echo $nameErr; } ?>
                                                </p>
                                            </div>

                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 form-group">
                                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" name="gender" id="btnradio1" value="Male" <?php if($gender == 'Male'){ echo 'checked'; } ?>  />
                                                    <label class="btn btn-outline-dark" for="btnradio1"> Male </label>
                                                    <input type="radio" class="btn-check" name="gender" id="btnradio2" value="Female" <?php if($gender == 'Female'){ echo 'checked'; } ?> />
                                                    <label class="btn btn-outline-dark" for="btnradio2">Female</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group">
                                                <label class="form-label" for="inputEmail">Email</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-envelope"></i>
                                                    </span>
                                                    <input type="text" id="inputEmail" class="form-control" placeholder="john.doe@example.com" name="email" value="<?= $email; ?>"/>
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
                                                    <input type="text" id="inputPhone" class="form-control" placeholder="658 799 8941" name="phone" value="<?= $phone; ?>"/>
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
                                                    <input type="text" id="inputDob" class="form-control " placeholder="YYYY-MM-DD" name="dob" value="<?= $dob; ?>"/>
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
                                                    <input type="text" id="inputJod" class="form-control" placeholder="YYYY-MM-DD" name="jod" value="<?= $jod; ?> "/>
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
                                                <textarea id="inputAddress" class="form-control" placeholder="Hi, Do you have a moment to talk Joe?" name="address"><?= $address; ?></textarea>
                                            </div>
                                            <p class="text-danger">
                                                <?php if (isset($addressErr)) { echo $addressErr; } ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-2" name="btnsave">Save changes</button>
                                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                </div>
                            </div>
                        </form>

                        <!-- /Account -->
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

    if(document.querySelector('div.button-wrapper input')){
        document.querySelector('div.button-wrapper input').addEventListener('change', e => {
            document.querySelector('div.card-body img').src = URL.createObjectURL(e.target.files[0]);

        });
    }

</script>