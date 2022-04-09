<?php 
	require 'header.php';
	include("confs/config.php");

if(isset($_POST["btnlogin"]))
{
	unset($_SESSION['oldvalue']);
	unset($_SESSION['login_reject']);
	
	$passwordError = null;
    $emailError = null;

	$_SESSION['oldvalue']=array(); 

	// $_SESSION['login_reject']=array();  

    $email=$_POST['email'];
    $password=$_POST['password'];

	$_SESSION['oldvalue']['email'] = $email;

    $valid = true;
    if (empty($email)) {
        $emailError = 'Please enter your email address in format: yourname@example.com';
        $valid = false;
    }

    if (empty($password)) {

    	$passwordError = 'Please enter your password.';
    	$valid = false;
	}

    if($valid){

	    $login_query=mysqli_query($conn,"SELECT users.*, positions.name as pname, positions.id as pid, position_user.type as type FROM users 
            INNER JOIN position_user ON users.id = position_user.user_id
            INNER JOIN positions ON position_user.position_id = positions.id
	    	WHERE users.email='$email' AND users.password='$password' 

             ");  
	    $login_numrows=mysqli_num_rows($login_query); 
	     
	    if($login_numrows!=0)  
	    
	    {

    		$result = mysqli_fetch_array($login_query);
	  
	    	if($email == $result['email'] && $password == $result['password'])  
	    	{
	    		if($result['status'] =='Active'){  
		    		$_SESSION['sess_user']=$result;

		    		echo "<script>
		    				Swal.fire({
							  	icon: 'success',
							  	title: 'Login Successful',
							  	text: 'You are now ready to use Brain Master University Portal',
							  	showConfirmButton: false,
							  	timer: 2000,
	            				allowOutsideClick: false
							  	
							}).then(function(){
								window.location='dashboard.php'
							})
		    			</script>
		    		";  
	    		}else{
	    			$_SESSION['login_reject'] = "Your sign-in was successful, but you don't have permissison to access this resource.";
	    		}
	    	}
	    	else{
	    		if($email != $result['email']){
	    		$emailError = "Sorry, we can't find an account with this email address. Please try signing in again later.";

		    	}else{
		    		$passwordError = "The password you entered is incorrect. Please try again.";

		    	}
	    	}
	    }
	    else{ 

	    	$_SESSION['login_reject'] = "The email and password you entered did not match our records. Please double-check and try again.";
	    } 
    }

}

?>
	<link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

	<div class="container-xxl">
	    <div class="authentication-wrapper authentication-basic container-p-y">
	        <div class="authentication-inner">
	            <!-- Register -->
	            <div class="card">
	                <div class="card-body">
	                    <!-- Logo -->
	                    <div class="app-brand justify-content-center">
	                        <a href="index.html" class="app-brand-link gap-2 justify-content-center">
	                            <img src="assets/img/logo.png" class="img-fluid w-50">
	                        </a>
	                    </div>
	                    <!-- /Logo -->
	                    <h4 class="mb-2">Welcome to BMU! 👋</h4>
	                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

	                    <?php if(isset($_SESSION['login_reject']) && !empty($_SESSION['login_reject']) ): ?>

					        <div class="alert alert-danger alert-dismissible fade show" role="alert">
					            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
					                <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
					                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
					                </symbol>
					            </svg>
					            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
					            
					            <?= $_SESSION['login_reject']; ?>
					            
					            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					        </div>

				        <?php endif ?>

	                    <form id="formAuthentication" class="mb-3 " action="index.php" method="POST">
	                        <div class="mb-3">
	                            <label for="email" class="form-label">Email or Username</label>
	                            <input type="text" class="form-control 
	                            <?php 
	                            	if(isset($emailError) || isset($_SESSION['login_reject']) && !empty($_SESSION['login_reject']) ) { echo 'is-invalid'; }
	                            ?>

	                            <?php 
	                            	if(isset($valid) && empty($emailError) && isset($_SESSION['login_reject']) && empty($_SESSION['login_reject']) ){ echo 'is-valid'; }
	                            ?>

	                            " id="email" name="email" placeholder="Enter your email" autofocus value="<?php if(isset($_SESSION['oldvalue']['email']) && !empty($_SESSION['oldvalue']['email']) ){ echo $_SESSION['oldvalue']['email']; } ?>" />
	                            
	                            <div class="invalid-feedback">
		                            <?php if (isset($emailError)) { echo $emailError; } ?>
		                        </div>
	                        </div>
	                        <div class="mb-3 form-password-toggle">
	                            <div class="d-flex justify-content-between">
	                                <label class="form-label" for="password">Password</label>
	                                
	                            </div>
	                            <div class="input-group input-group-merge">
	                                <input type="password" id="password" class="form-control
	                                <?php 
		                            	if(isset($passwordError) || isset($_SESSION['login_reject']) && !empty($_SESSION['login_reject']) ) { echo 'is-invalid'; }

		                            ?>

	                                " name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
	                                <span class="input-group-text cursor-pointer">
	                                    <i class="bx bx-hide"></i>
	                                </span>

	                                <div class="invalid-feedback">
			                            <?php if (isset($passwordError)) { echo $passwordError; } ?>
			                        </div>
	                            </div>

	                            
	                        </div>
	                        <div class="mb-3">
	                            <button class="btn btn-primary d-grid w-100" type="submit" name="btnlogin">Sign in</button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	            <!-- /Register -->
	        </div>
	    </div>
	</div>

<?php 
	require 'footer.php';
?>