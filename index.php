<?php
    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $msg = " ";
    $counter = 0;
    
    session_Start();

    // authentication and authorization
    if (empty($_SESSION['STATUS']) || $_SESSION['STATUS'] == 'invalid' ) {
        $_SESSION['STATUS'] ='invalid';
    } else if ($_SESSION['STATUS'] == 'faculty') {
        echo '<script>window.location.href="faculty/facultyLanding.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    }

    $msg_registered = $_SESSION['REGISTER_MESSAGE'];
    unset($_SESSION['REGISTER_MESSAGE']);
    $ch = curl_init();
    $url = $api.'student/fetchAchievers.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $achiever_decoded = json_decode($resp);
    } curl_close($ch);


    $ch = curl_init();
    $url = $api.'student/fetchDefaults.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $default_decoded = json_decode($resp);
    } curl_close($ch);

    foreach ($default_decoded as $default_obj) {
        $achievers = $default_obj->finalizing;
    }


    if (isset($_POST['LOGIN'])) {
        $user_id = $_POST['user_id'];
        $user_password = $_POST['password'];

        $ch = curl_init();
        $url = $api."loginFaculty.php";
        
        $post_data = array (
            "faculty_id" => $user_id
        );

        $header = [
            'Content-type: Text/plain'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $decoded = json_decode($output);
        
        if ($output === false) {
            $_SESSION['STATUS'] = 'invalid';
        }

        else if ($decoded === 'Account does not exist') {
            $_SESSION['STATUS'] ='invalid';

                 //place student login here...
                 curl_close($ch);
                 $user_id = $_POST['user_id'];
                 $user_password = $_POST['password'];
     
                 $ch = curl_init();
                 $url = $api."loginStudent.php";
                 
                 $post_data = array ("student_id" => $user_id);
                 $header = ['Content-type: Text/plain'];
     
                 curl_setopt($ch, CURLOPT_URL, $url);
                 curl_setopt($ch, CURLOPT_POST, 1);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                 $output = curl_exec($ch);
                 $decoded = json_decode($output);
                 
                 if ($output === false) {
                     $_SESSION['STATUS'] = 'invalid';
     
                 }   else if ($decoded === 'Account does not exist') {
                         $_SESSION['STATUS'] ='invalid';
                         $msg = $decoded;
     
                 } else if (password_verify($user_password, $decoded[0]->password)) {
                     $_SESSION['STATUS'] = 'student';
                     
                     foreach ($decoded as $obj) {
                         $_SESSION['STUDENT_ID'] = $obj->student_id;
                         $_SESSION['FIRSTNAME_STUDENT'] = $obj->firstname;
                         $_SESSION['MIDDLENAME_STUDENT'] = $obj->middlename;
                         $_SESSION['LASTNAME_STUDENT'] = $obj->lastname;
                     }
     
                     if ($_SESSION['STATUS'] = 'student') {
                         echo '<script>window.location.href="student/student_landing.php"</script>';
                         curl_close($ch);
                     }
                 } else {
                    $msg = 'You have entered an incorrect userID or password';
                    curl_close($ch);
                }
        }

        else if (password_verify($user_password, $decoded[0]->password)) {
            $_SESSION['STATUS'] = 'faculty';

            foreach ($decoded as $obj) {
                $_SESSION['FACULTY_ID'] = $obj->faculty_id;
                $_SESSION['USERNAME'] = $obj->name;
                $_SESSION['ADVISER'] = $obj->adviser;
                $_SESSION['ADMIN'] = $obj->admin;
                $_SESSION['COMMITTEE_ROLE'] = $obj->committee_role;
            }

            if ($_SESSION['STATUS'] = 'faculty') {
                echo '<script>window.location.href="faculty/facultyLanding.php"</script>';
                curl_close($ch);
            }
        } else {
            $msg = 'You have entered an incorrect userID or password';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>ICS-DLOA | Login</title>
</head>
<body>

    <div class="index-container">

        <div class="login-form-container">

                <img class="icslogo-login-top" src="./assets/images/icslogo.png" alt="icslogo">
                <form action="index.php" method="POST" class="login-form">
                    <div class="green-bar"></div>

                    <div class="login-intro">
                        <h2> Sign in </h2>
                    </div>

                    <br class='br'/>
                    <br class='br'/>

                    <input class="login-text-field" type="text" name="user_id" id="user_id" placeholder="wmsu id">
                    <input class="login-text-field" type="password" name="password" id="password" placeholder="password">
                    <input class="login-sumbit" type="submit" name="LOGIN" id="LOGIN" value="Enter">
                </form>

                <div class="register-container">
                    <span>assigned by your adviser?</span>
                    <a href="./student/studentRegister.php" class="register-link"> Register</a>
                </div>  

                <div class="login-error-msg">
                    <span><?php echo $msg; ?></span>
                    <span style="color: green;"><?php echo $msg_registered; ?></span>
                </div>

        </div>

        <div class="right-container">

            <div class="logo-container">
                <img class="ics_home_logo" src="assets/images/icslogo.png" alt="ics_logo">
                <h3>Western Mindanao State University</h3>
                <h3>Institute of Computer Studies</h3>
                <h1>Director's List Online Application</h1>
            </div>

            <?php if ($achievers != 'no') { ?>
                <div class="table-container">
                    <h2 style="color: white;">Top 10 Achievers</h2>
                    <table class="table_achievers1">
                        <tr>
                            <th>Rank</th>
                            <th>Student ID</th>
                            <th>Course</th>
                            <th>Section</th>
                            <th>Year</th>
                            <th>GPA</th>
                        </tr>
        
                        <?php if ($achiever_decoded != "no students found") { ?>
                            <?php foreach ($achiever_decoded as $obj): ?>
                                <tr class="student-table">
                                    <td><?php echo $counter+=1; ?></td>
                                    <td><?php echo $obj->student_id; ?></td>
                                    <td><?php echo $obj->course; ?></td>
                                    <td><?php echo $obj->section; ?></td>
                                    <td><?php echo $obj->year; ?></td>
                                    <td><?php echo $obj->gpa; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php } ?>
                        
                    </table>
                    <a class="link" href="viewAchievers.php">View All Achievers</a>
                </div>
            <?php } ?>       

        </div>

    </div>
    
</body>
</html>