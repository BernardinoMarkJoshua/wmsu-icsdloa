<?php
    session_start();
    $style1 = 'visibility: visible;';
    $style2 = 'visibility: hidden;';
    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $msg1 = " ";


    $ch = curl_init();
    $url = $api.'student/fetchAdvisers.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $adviser_decoded = json_decode($resp);
    } curl_close($ch);


    if (isset($_POST['verify'])) {
        $student_id = $_POST['student_id'];
        $_SESSION['REGISTER_STUDENT_ID'] = $_POST['student_id'];

        $ch = curl_init();

        $url = $api."student/verifyMisto.php";
        $post_data = array (
            "student_id" => $student_id
        );

        $header = ['Content-type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $verifyMisto_decoded = json_decode($output);
        
        if ( $verifyMisto_decoded != 'No student exist' ) {

            $url = $api."student/verifyStudent.php";
            $post_data = array (
                "student_id" => $student_id
            );

            $header = ['Content-type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);
            $verifyStudent_decoded = json_decode($output);
            
            $ch = curl_init();
            $url = $api."student/checkGate.php";
            $post_data = array ("student_id" => $student_id);
            $header = ['Content-type: Text/plain'];
        
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);
            $checkGate = json_decode($output);
            
            if ($checkGate != 'student does not exist') {
                $msg1 = 'Student ID is already waiting for approval';
            } else {
                if ($verifyStudent_decoded == 'No student exist') {
                    $style2 = 'visibility: visible;';
                    $style1 = 'visibility: hidden;';
                    $msg1 = " ";
    
                    foreach ( $verifyMisto_decoded as $verifyMisto_obj ) {
                        $_SESSION['REGISTER_FIRSTNAME'] = $verifyMisto_obj->firstname;
                        $_SESSION['REGISTER_MIDDLENAME'] = $verifyMisto_obj->middlename;
                        $_SESSION['REGISTER_LASTNAME'] = $verifyMisto_obj->lastname;
                    }
                } else {
                    $msg1 = 'Student ID is already registered';
                }
            }
        } else {
            $msg1 = 'No student record found';
            $style2 = 'visibility: hidden;';
        }

        curl_close($ch);

    } else if (isset($_POST['register'])) {
         $student_id_register = $_SESSION['REGISTER_STUDENT_ID'];
         $firstname_register = $_SESSION['REGISTER_FIRSTNAME'];
         $middlename_register = $_SESSION['REGISTER_MIDDLENAME'];
         $lastname_register = $_SESSION['REGISTER_LASTNAME'];
         $email = $_POST['email'];
         $contact_number = $_POST['contact'];
         $contact = $_POST['contact'];
         $course = $_POST['course'];
         $adviser = $_POST['adviser'];
         $password = $_POST['password'];
         $password_confirm = $_POST['password_confirm'];
         $college = "ICS";

        
        if ($password != $password_confirm) {
            $msg1 = "The passwords you entered dont match";
            $style2 = 'visibility: visible;';
            $style1 = 'visibility: hidden;';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ch = curl_init();
            $url = $api."student/registerStudent.php";

            $post_data = array(
                "student_id" => $student_id_register,
                "password" => $hash,
                "firstname" => $firstname_register,
                "middlename" =>$middlename_register,
                "lastname" => $lastname_register,
                "email" => $email,
                "contact_number" => $contact_number,
                "college" => $college,
                "course" => $course,
                "adviser" => $adviser   
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
            $register_decoded = json_decode($output);

            if ($register_decoded == 'student already exist') {
                $msg1 = 'This user id is already in use or waiting for approval';
                $style2 = 'visibility: visible;';
                $style1 = 'visibility: hidden;';
            } else {
                curl_close($ch);
                unset($_SESSION['REGISTER_STUDENT_ID']);
                unset($_SESSION['REGISTER_FIRSTNAME']);
                unset($_SESSION['REGISTER_MIDDLENAME']);
                unset($_SESSION['REGISTER_LASTNAME']);
                $_SESSION['REGISTER_MESSAGE'] = "Your student ID has been registered please wait for an email approval.";
                echo '<script>window.location.href="../index.php"</script>';
            }
        }
        
    } else if (isset($_POST['back'])) {
        echo '<script>window.location.href="../index.php"</script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICS-DLOA | Register</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="register_main_container">

    
        <form class="verify_misto" action="studentRegister.php" method="POST" style="<?php echo $style1?>">
            <h1>Register </h1>
            <label for="student_id">Student ID</label>
            <input type="text" name="student_id" id="student_id" placeholder="Student ID" required>
            
            <input class="verify_btn"  type="submit" name="verify" value="Verify">
        </form>
        
        <form action="studentRegister.php" method="POST" style="margin-top: 10px; margin-bottom: 20px;">
            <input class="blue-btn" type="submit" name="back" value="Back">
        </form>

        <?php if (isset($msg1)) { ?>

            <span><?php echo $msg1?></span>

        <?php } ?>

        <div class="register_container" style="<?php echo $style2 ?>">

            <h1>Register</h1>

            <form class="register_form" action="studentRegister.php" method="POST">
                <input type="hidden" name="student_id" value="<?php echo $student_id ?>">
                <input type="hidden" name="firstname" value="<?php echo $firstname ?>">
                <input type="hidden" name="middlename" value="<?php echo $middlename ?>">
                <input type="hidden" name="lastname" value="<?php echo $lastname ?>">
    
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required>

                <label for="contact">Contact Number</label>
                <input type="number" name="contact" id="contact" placeholder="Contact Number" required>

                <label for="course">Course</label>
                <select name="course" required>
                    <option value="" disabled selected>Course Select</option>
                    <option value="BSCS">BSCS</option>
                    <option value="BSIT">BSIT</option>
                </select>
    
                <label for="adviser">Adviser</label>
                <select name="adviser" required>
                    <option value="" disabled selected>Adviser Select</option>
                    <?php foreach ( $adviser_decoded as $adviser_obj ) :?>
                        <option value="<?php echo $adviser_obj->name?>"><?php echo $adviser_obj->name?></option>
                    <?php endforeach; ?>
                </select>
    
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" required>
    
                <label for="password_confirm">Password Confirm</label>
                <input type="password" name="password_confirm" placeholder="Password Confirm" required>
    
                <input class="register-btn" type="submit" name="register" value="Register">
            </form>
        </div>

    </div>
    
</body>
</html>