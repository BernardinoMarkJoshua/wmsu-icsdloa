<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['SUBJECT_ADD_STATUS'] != 'yes') {
        echo '<script>window.location.href="subject.php"</script>';
    } 
    $_SESSION['SUBJECT_ADD_STATUS'] = 'no';
    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $msg = " ";

    if (isset($_POST['add'])) {
        $curriculum_name = $_SESSION['SUBJECT_ADD_course_name'];
        $subject_year = $_SESSION['SUBJECT_ADD_subject_year'];
        $subject_semester = $_SESSION['SUBJECT_ADD_subject_semester']; 
        $subject_code = $_POST['subject_code'];
        $subject_name = $_POST['subject_name'];
        $subject_units = $_POST['subject_units'];
        $ch = curl_init();
        $url = $api.'admin/addSubject.php';
        $post_data = array (
            "subject_code" => $subject_code,
            "subject_name" => $subject_name,
            "subject_year" => $subject_year,
            "subject_semester" => $subject_semester,
            "curriculum_name" => $curriculum_name,
            "subject_units" => $subject_units
        );
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
        echo '<script>window.location.href="subject.php"</script>';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Edit</title>
</head>
<body>
    
<div class="faculty-register-main-container">
        <div class="faculty-register-container">
            <div class="adviser-register">

                <div class="information">
                    <h2>ICS-DLOA Add Subject</h2>
                    <p>
                        This form is for adding Subjects for courses of the Western Mindanao State University 
                        Institute of Computer Studies department.
                    </p>
                    <a class="cancel-btn" href="subject.php">Cancel</a>
                </div>

                <form action="subjectAdd.php" method="POST" class="adviser-register-form">

                    <div class="name-reg-adviser">
                        <input type="text" name="subject_code" id="subject_code" placeholder="Subject Code">
                        <input type="text" name="subject_name" id="subject_name" placeholder="Subject Name">
                        <input type="text" name="subject_units" id="subject_units" placeholder="Subject Units">
                    </div>

                    <div class="register-btn-container">
                        <input class='register-btn' type="submit" name="add" id="add" value="Add">
                    </div>

                    </div>
    
                </form>
            </div>

        </div>
    </div>

</body>
</html>