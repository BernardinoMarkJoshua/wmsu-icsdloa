<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADMIN_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    } else if ($_SESSION['STUDENT_EDIT_STATUS'] != 'yes') {
        echo '<script>window.location.href="viewStudents.php"</script>';
    }

    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $msg = " ";

    $ch = curl_init();
    $url = $api.'admin/fetchCurriculum.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $curriculum_decoded = json_decode($resp);
    } curl_close($ch);


    $ch = curl_init();
    $url = $api.'admin/viewOneStudent.php';
    $student_id_default = $_SESSION['VIEW_STUDENT_STUDENT_ID'];
    $post_data = array ("student_id" => $student_id_default);

    $header = ['Content-Type: Text/plain'];
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $student_decoded = json_decode($output);

    foreach ($student_decoded as $student_obj) {
        $student_id_default = $student_obj->student_id;
        $firstname_default = $student_obj->firstname;
        $middlename_default = $student_obj->middlename;
        $lastname_default = $student_obj->lastname;
        $email_default = $student_obj->email;
        $contact_number_default = $student_obj->contact_number;
        $college_default = $student_obj->college;
        $course_default = $student_obj->course;
        $curriculum_default = $student_obj->curriculum;
    }


    curl_close($ch);

    if (isset($_POST['save'])) {
        
        $student_id_holder = $_SESSION['VIEW_STUDENT_STUDENT_ID'];
        $student_id = $_POST['student_id'];
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $contact_number = $_POST['contact'];
        $college = $_POST['college'];
        $course = $_POST['course'];
        $course_curriculum = $_POST['curriculum_course'];

        if ($password_confirm != $password) {

            $msg = 'password does not match';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ch = curl_init();
            $url = $api.'admin/editStudents.php';
            $post_data = array (
                "student_id_holder" => $student_id_holder,
                "student_id" => $student_id,
                "email" => $email,
                "firstname" => $firstname,
                "middlename" => $middlename,
                "lastname" => $lastname,
                "password" => $hash,
                "contact_number" => $contact_number,
                "college" => $college,
                "course" => $course,
                "curriculum_name" => $course_curriculum
            );
            $header = ['Content-Type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);

            if ($output === "false") {
                $msg = "account already exist or something went wrong please try again";
            } else {
                $_SESSION['VIEW_STUDENT_STUDENT_ID'] = $student_id;
                $_SESSION['STUDENT_EDIT_STATUS'] = 'no';
                $msg = "Student details changed";
                echo '<script>window.location.href="viewStudents.php"</script>';
                curl_close($ch);
            }
        }
    } else if (isset($_POST['modal'])) {
        echo" <script>window.location.href='#modal'</script>";
        
    } else if (isset($_POST['cancel_edit'])) {
        $_SESSION['STUDENT_EDIT_STATUS'] = 'yes';
        echo "<script>window.location.href='#'</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/modal2.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Edit</title>
</head>
<body>  
    
    <div class="faculty-register-main-container">
        <div class="faculty-register-container">
            <div class="adviser-register">

                <div class="information">
                    <h2>ICS-DLOA Edit Student</h2>
                    <p>
                        This Edit form is for the Students of the Western Mindanao State University 
                        Institute of Computer Studies department.
                    </p>
                    <a class="cancel-btn" href="viewStudents.php">Back</a>
                </div>

                <form action="studentEdit.php" method="POST" class="adviser-register-form">

                    <div class="top-reg-adviser">
                        <input type="text" name="student_id" id="student_id" placeholder="Student id" value="<?php echo $student_id_default?>" required>
                        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $email_default?>" required>
                    </div>

                    <div class="name-reg-adviser">
                        <input type="text" name="firstname" id="firstname" placeholder="Firstname" value="<?php echo $firstname_default?>" required>
                        <input type="text" name="middlename" id="middlename" placeholder="Middlename" value="<?php echo $middlename_default?>" >
                        <input type="text" name="lastname" id="lastname" placeholder="Lastname" value="<?php echo $lastname_default?>" required>
                    </div>

                    <div class="password-reg-adviser">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <input type="password" name="password_confirm" name="password_confirm" placeholder="Password confirm" required>
                    </div>
                    
                    <div class="long-input">
                        <input type="text" name="contact" id="contact" placeholder="Contact number" value="<?php echo $contact_number_default?>" required>
                    </div>

                    <select name="college" id="college" class="select-course" required>
                        <option value="<?php echo $college_default?>">Select college</option>
                        <option value="ICS">ICS</option>
                    </select>

                    <select name="course" id="course" class="select-course" required>
                        <option value="<?php echo $course_default?>">Select course</option>
                        <option value="BSCS">BSCS</option>
                        <option value="BSIT">BSIT</option>
                    </select>
                    
                    <select name="curriculum_course" id="curriculum_course" class="select-course" required>
                        <option value="<?php echo $curriculum_default?>">Select curriculum</option>
                        <?php foreach ($curriculum_decoded as $curriculum_obj) :?>
                            <option value="<?php echo $curriculum_obj->curriculum_name;?>"><?php echo $curriculum_obj->curriculum_name;?></option>
                        <?php endforeach; ?>
                    </select>

                    <a class='primary-link-btn' href="#modal">Save</a>

                    <div class="modal" id="modal">
                        <div class="modal__content">
                        <a href="#" class="modal__close">&times;</a>
                        <h2 class="modal__heading">Confirm</h2>
                            <span>Continue to save changes?</span>
                            <br/>    
                            <br/> 
                            <br/> 
                            <div style="display: flex; flex-direction: row;">
                                    <input class='primary-btn' type="submit" name="save" value="Save">
                                    <input class='blue-btn' type="submit" name="cancel_edit" value="Cancel">
                            </div>
                            
                        </div>
                    </div>

                    <?php echo $msg?>
                </form>
            </div>
        </div>
    </div>

</body>
</html>