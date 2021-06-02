<?php
    use PHPMailer\PHPMailer\PHPMailer;
    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $msg = " ";
    $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $string2 = "1234567890";
    
    
    if (isset($_POST['register'])) {
        $faculty_id = $_POST['faculty_id'];
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $password1 = substr(str_shuffle($string), 0,4);
        $password2 = substr(str_shuffle($string2), 0,1);
        $password = $password1.''.$password2;

        $adviser = $_POST['adviser'];
        $admin = $_POST['admin'];
        $committee_role = $_POST['committee_role'];
        $contact_number  = $_POST['contact'];

        if ($adviser == 'no') {
            $adviser_email = ' ';
        } else if ($adviser == 'yes') {
            $adviser_email = 'Adviser';
        } if ($admin == 'no') {
            $admin_email = ' ';
        } else if ($admin == 'yes') {
            $admin_email = 'Admin';
        } if (isset($committee_role)) {
            $committee_role_email = $committee_role;
        }

        $ch = curl_init();
        $url = $api.'admin/checkCommitteeRole.php';
    
        $post_data = array ("committee_role" => $committee_role);
    
        $header = ['Content-Type: Text/plain'];
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $committee_role_resp = json_decode($output);
        curl_close($ch);  

        if ($committee_role == 'none') {

            $hash = password_hash($password, PASSWORD_BCRYPT);
            if ($middlename != NULL) {
                $full_name = $firstname.' '.$middlename.' '.$lastname;
            }else {
                $full_name = $firstname.' '.$lastname;
            }
            $ch = curl_init();
            $url = $api."admin/registerFaculty.php";

            $post_data = array(
                "faculty_id" => $faculty_id,
                "email" => $email,
                "name" => $full_name,
                "password" => $hash,
                "adviser" => $adviser,
                "admin" => $admin,
                "committee_role" => $committee_role,
                "contact_number" => $contact_number
            );

            $header = [
                'Content-Type: Text/plain'
            ];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);
            $committee_role_decoded = json_decode($output);

            if ($committee_role_decoded === 'user id is already in use') {
                $msg = "The ID you entered is already in use please check your input and try again";
            } else {
                curl_close($ch);
                //insert Mailing here

                $name = 'ICS-DLOA';
                $email = $email;
                $subject = 'Account Registered';
                $body = '
                    <span>Your DLOA account has been created</span> 
                    <br/>
                    <br/>
                    <span><strong>Informations</strong></span>
                    <br/>
                    <span>Name: </span>'.$firstname.'  '.$lastname.'
                    <br/>
                    <span>Role/s: </span>'.$admin_email.', '.$adviser_email.'
                    <br/>
                    <span><strong>User ID: </strong>'.$faculty_id.'</span> <br/>
                    <span><strong>Password: </strong>'.$password.'</span>
                ';
            
                require_once "PHPMailer/PHPMailer.php";
                require_once "PHPMailer/SMTP.php";
                require_once "PHPMailer/Exception.php";
            
                $mail = new PHPMailer();
            
                //SMTP Settings
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "icsdloa@gmail.com";
                $mail->Password = 'icsuser112233';
                $mail->Port = 465; //587
                $mail->SMTPSecure = "ssl"; //tls
            
                //Email Settings
                $mail->isHTML(true);
                $mail->setFrom($email, $name);
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->Body = $body;
            
                if ($mail->send()) {
                    $status = "success";
                    $response = "Email is sent!";
                    echo '<script>window.location.href="faculty.php"</script>';
                } else {
                    $status = "failed";
                    $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
                }
            }
            

        } else if ($committee_role_resp == 'Role Accepted') {
    
                //ADD ADVISER TO FACULTY MEMBER
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $ch = curl_init();
                $url = $api."admin/registerFaculty.php";
                if ($middlename != NULL) {
                    $full_name = $firstname.' '.$middlename.' '.$lastname;
                }else {
                    $full_name = $firstname.' '.$lastname;
                }
    
                $post_data = array(
                    "faculty_id" => $faculty_id,
                    "email" => $email,
                    "name" => $full_name,
                    "password" => $hash,
                    "adviser" => $adviser,
                    "admin" => $admin,
                    "committee_role" => $committee_role,
                    "contact_number" => $contact_number
                );
    
                $header = [
                    'Content-Type: Text/plain'
                ];
    
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $output = curl_exec($ch);
                $committee_role_decoded = json_decode($output);
                var_dump($committee_role_decoded);
    
                if ($committee_role_decoded === 'user id is already in use') {
                    $msg = "The ID you entered is already in use please check your input and try again";
                } else {
                    curl_close($ch);

                        $name = 'ICS-DLOA';
                        $email = $email;
                        $subject = 'Account Registered';
                        $body = '
                        <span>Your DLOA account has been created</span> 
                        <br/>
                        <br/>
                        <span><strong>Informations</strong></span>
                        <br/>
                        <span>Name: </span>'.$firstname.'  '.$lastname.'
                        <br/>
                        <span>Role/s: </span>'.$admin_email.'  '.$adviser_email.'  '.$committee_role_email.'
                        <br/>
                        <span><strong>User ID: </strong>'.$faculty_id.'</span> <br/>
                        <span><strong>Password: </strong>'.$password.'</span>
                    ';

                    require_once "PHPMailer/PHPMailer.php";
                    require_once "PHPMailer/SMTP.php";
                    require_once "PHPMailer/Exception.php";
                
                    $mail = new PHPMailer();
                
                    //SMTP Settings
                    $mail->isSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->SMTPAuth = true;
                    $mail->Username = "icsdloa@gmail.com";
                    $mail->Password = 'icsuser112233';
                    $mail->Port = 465; //587
                    $mail->SMTPSecure = "ssl"; //tls
                
                    //Email Settings
                    $mail->isHTML(true);
                    $mail->setFrom($email, $name);
                    $mail->addAddress($email);
                    $mail->Subject = $subject;
                    $mail->Body = $body;
                
                    if ($mail->send()) {
                        $status = "success";
                        $response = "Email is sent!";
                        echo '<script>window.location.href="faculty.php"</script>';
                    } else {
                        $status = "failed";
                        $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
                    }
                }
            
        } else {
            $msg = "Role is already taken";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Register</title>
</head>
<body>  
    
    <div class="faculty-register-main-container">
        <div class="faculty-register-container">
            <div class="adviser-register">

                <div class="information">
                    <h2>ICS-DLOA Register Faculty</h2>
                    <p>
                        This registration form is for the Advisers and Admins of the Western Mindanao State University 
                        Institute of Computer Studies department.
                    </p>
                    <a class="cancel-btn" href="faculty.php">Back</a>
                </div>

                <form action="registerMembers.php" method="POST" class="adviser-register-form">

                    <div class="top-reg-adviser">
                        <input type="text" name="faculty_id" id="faculty_id" placeholder="Faculty id" required>
                        <input type="text" name="email" id="email" placeholder="Email" required>
                    </div>

                    <div class="name-reg-adviser">
                        <input type="text" name="firstname" id="firstname" placeholder="Firstname" required>
                        <input type="text" name="middlename" id="middlename" placeholder="Middlename (OPTIONAL)" >
                        <input type="text" name="lastname" id="lastname" placeholder="Lastname" required>
                    </div>
                    
                    <div class="bottom-reg-adviser">
                        <select name="adviser" id="adviser" required>
                            <option value="" disabled selected>Adviser Role</option>
                            <option value="yes">yes</option>
                            <option value="no">no</option>
                        </select>

                        <select name="admin" id="admin" required>
                            <option value="" disabled selected>Admin Role</option>
                            <option value="yes">yes</option>
                            <option value="no">no</option>
                        </select>

                        <select name="committee_role" id="committee_role" required>
                            <option value="" disabled selected>Committee role Select</option>
                            <option value="ICS Director">ICS Director</option>
                            <option value="Chairperson">Chairperson</option>
                            <option value="Gender Guidance">Gender and Guidance Counseling</option>
                            <option value="Institute Secretary">Institute Secretary</option>
                            <option value="Student Affair Coordinator">Student Affair Coordinator</option>
                            <option value="IT Head">IT Head</option>
                            <option value="CS Head">CS Head</option>
                            <option value="none">none</option>
                        </select>
                    </div>

                    <div class="long-input">
                        <input type="text" name="contact" id="contact" placeholder="Contact number" required>
                    </div>
                    

                    <div class="register-btn-container">
                        <input class='register-btn' type="submit" name="register" id="register" value="Register">
                    </div>
    
                    <span><?php echo $msg; ?></span>
                </form>
            </div>

        </div>
    </div>

</body>
</html>