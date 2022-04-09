<?php

include("../confs/config.php");

$id=$_GET['id'];

$result = mysqli_query($conn,"SELECT comments.*, 
                            users.name as uname, users.profile as uprofile, positions.name as pname, 
                            ideas.title as title, ideas.id as iid 
                            FROM comments 
                            LEFT JOIN users ON comments.user_id = users.id
                            LEFT JOIN position_user ON users.id = position_user.user_id
                            LEFT JOIN positions ON position_user.position_id = positions.id
                            LEFT JOIN ideas ON comments.idea_id = ideas.id
                            WHERE comments.id = $id");
$row = mysqli_fetch_array($result);

$mailresult = mysqli_query($conn, "SELECT users.name as uname, users.email as uemail
FROM comments
LEFT JOIN ideas ON comments.idea_id = ideas.id
LEFT JOIN users ON ideas.user_id = users.id
WHERE comments.id = $id");
$mailrow = mysqli_fetch_array($mailresult);

$id = $row['iid'];
$title = $row['title'];
$comment = $row['body'];
$status = $row['status'];

$sendmail = $mailrow['uemail'];
$sendname = $mailrow['uname'];


$user = $row['uname'];
$profile = '../'.$row['uprofile'];
$position = $row['pname'];

if (strlen($title) > 120){
    $idea_title = substr($title, 0, 120).'...';
}
else{
    $idea_title = $title;
}




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../vendor/autoload.php";

require_once "../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require_once "../vendor/phpmailer/phpmailer/src/SMTP.php";
require_once "../vendor/phpmailer/phpmailer/src/Exception.php";


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'nvmind123456@gmail.com';                     //SMTP username
    $mail->Password   = 'nvm123!@#';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('info@brainmasteruniversity.com', 'Brain Master University');
    $mail->addAddress($sendmail, $sendname);     //Add a recipient

    $mail->AddEmbeddedImage('../assets/img/bmulogo.png', 'logo');


    if($status == 'off'){
        $mail->AddEmbeddedImage($profile, 'profile');

        $somebody1 = '<img src="cid:profile" alt="" style="width: 100px; max-width: 600px; height: auto; margin: auto; display: block;">
                        <h3 class="name">'.$user.'</h3>
                        <span class="position">'.$position.'</span>';
        $somebody2 = $user;
    }else{
        $mail->AddEmbeddedImage('../assets/img/mysterious.png', 'mysterious');

        $somebody1 = '<img src="cid:mysterious" alt="" style="width: 100px; max-width: 600px; height: auto; margin: auto; display: block;">
                     <h3 class="name"> Anonymous User </h3>';
        $somebody2 = 'Anonymous User';

    }
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = "Look, someone comment, your post…";

    $body = '<html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <meta property="og:title" content="BMU Email Template">
                <title>BMU Email Template</title>
                <style type="text/css">
                    @import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700);
                    a:hover {
                        color: #009DE0 !important;
                    }

                    a.section-btn:hover {
                        color: #F1F3F5 !important;
                        background-color: #007BDC !important;
                    }

                    @media only screen and (min-width: 601px),
                    screen and (min-device-width: 601px) {
                        body[yahoo] .content {
                            width: 100% !important;
                            max-width: 600px !important;
                        }
                    }

                    @media only screen and (max-width: 614px),
                    screen and (max-device-width: 614px) {
                        body[yahoo] .narrow {
                            padding: 30px 16px 30px 16px !important;
                        }
                    }
                    .round {
                        border: 1px solid #51ADE5;
                        border-radius: 50%;
                        padding: 7px;
                    }
                    .text-author {
                        bordeR: 1px solid rgba(0, 0, 0, .05);
                        max-width: 50%;
                        margin: 0 auto;
                        padding: 2em;
                    }

                    .text-author img {
                        border-radius: 50%;
                        padding-bottom: 20px;
                    }

                    .text-author h3 {
                        margin-bottom: 0;
                    }
                    .avatar-initial {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        text-transform: uppercase;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 700;
                    }
                    .rounded-circle {
                        border-radius: 50% !important;
                    }
                    .bg-label-danger {
                        background-color: #ffe0db !important;
                        color: #ff3e1d !important;
                    }
                </style>
            </head>
            <body yahoo leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="-webkit-font-smoothing: antialiased;margin: 0;padding: 0;background-color: #EDF2F5;width: 100% !important;">
                <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="margin: 0;padding: 0;background-color: #EDF2F5;height: 100% !important;width: 100% !important;">
                    <tr>
                        <td style="border-collapse: collapse; vertical-align: top;">
                            
                            <table class="content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; border-collapse: collapse; vertical-align: top; margin: 0 auto;">
                                <tr>
                                    <td class="narrow" bgcolor="#EDF2F5" style="padding: 40px 16px 30px 16px; border-collapse: collapse; vertical-align: top;">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; vertical-align: top;">
                                            <tr>
                                                <td align="left" style="font-family: Source Sans Pro, Helvetica Neue,Helvetica,Arial,sans-serif; font-size: 13px;  border-collapse: collapse; vertical-align: top;">
                                                    <a href="http://localhost/bmu/" style="font-size:20px;color:#3B464B;text-decoration: none!important;-webkit-transition: color .2s ease; transition: color .2s ease;">
                                                    <img src="cid:logo" alt="Instituto Superior Técnico" border="0" width="170" height="40" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; display: inline-block;" />
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-collapse: collapse; vertical-align: top; padding: 0 16px;">
                                        <table bgcolor="#FFFFFF" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; vertical-align: top; border-radius:3px;">
                                            <tr>
                                                <td style="padding: 16px"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <div class="text-author">'.$somebody1.'</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#FFFFFF" width="100%" height="30" style="line-height: 34px; border-collapse: collapse; vertical-align: top; padding: 0">
                                                    <h1 style="color:#282C35; margin: 16px 40px 0; padding: 0; font-family: Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif; font-size: 27px; font-weight: 600;"> '.$somebody2.' comment your post ideas. </h1>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#FFFFFF" width="100%" height="30" style="line-height: 25px; border-collapse: collapse; vertical-align: top; padding: 0 40px">
                                                    <p style="color:#45555F; margin:24px 0; padding: 0; font-family: Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif; font-size: 15px; font-weight: 400;">'.$idea_title.'</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#FFFFFF" width="100%" height="30" style="line-height: 25px; border-collapse: collapse; vertical-align: top; padding: 0 40px">
                                                    <a href="idea.php?id='.$id.'" target="_blank" class="section-btn" style="background-color:#009CE3;font-family: Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif; font-size:14px;font-weight:600;text-align:center;color:#FFFFFF;margin: 16px 0;display: inline-block;padding:8px 16px;border-radius:2px;text-decoration: none!important;-webkit-transition: all .2s ease; transition: all .2s ease;">Read The Comment </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#FFFFFF" width="100%" height="30" style="line-height: 25px; border-collapse: collapse; vertical-align: top; padding: 0 40px;">
                                                    <hr style="background: transparent; border: 0; border-top: 1px solid #DDD; margin: 24px 0 0 0" />
                                                    <p style="font-family: Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif; font-size: 12px; color: #45555F; margin: 24px 0">Best Regards, </p>

                                                    <p style="font-family: Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif; font-size: 12px; color: #45555F; margin: 24px 0"> Brain Master University </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr height="50">
                                    <td colspan="3" style="color: #464646; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 16px; vertical-align: top;"></td>
                                </tr>
                            </table>
                                       
                        </td>
                    </tr>
                </table>
            </body>
            </html>';

    $mail->Body    = $body;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}





