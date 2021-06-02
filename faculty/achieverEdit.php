<?php
    session_start();
    $api = "http://icsdloa.online/cybersolution_backend/control/";

    if (isset($_POST['edit'])) {
        
        $student_id_change = $_SESSION['ACHIEVER_STUDENT_ID'];
        $student_id = $_POST['student_id'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $course = $_POST['course'];
        $year = $_POST['year'];
        $section = $_POST['section'];

        $ch = curl_init();
        $url = $api.'admin/editAchiever.php';
        $post_data = array (
            "student_id_holder" => $student_id_change,
            "student_id" => $student_id,
            "firstname" => $firstname,
            "middlename" => $middlename,
            "lastname" => $lastname,
            "course" => $course,
            "year" => $year,
            "section" => $section
        );
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
        
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
                    <h2>ICS-DLOA Edit Achiever</h2>
                    <p>
                        This Edit form is for the DLOA Achievers of the Western Mindanao State University 
                        Institute of Computer Studies department.
                    </p>
                    <a class="cancel-btn" href="achievers.php">Cancel Edit</a>
                </div>

                <form action="achieverEdit.php" method="POST" class="adviser-register-form">

                    <div class="top-reg-adviser">
                        <input type="text" name="student_id" id="student_id" placeholder="student id">

                    </div>

                    <div class="name-reg-adviser">
                        <input type="text" name="firstname" id="firstname" placeholder="Firstname">
                        <input type="text" name="middlename" id="middlename" placeholder="Middlename">
                        <input type="text" name="lastname" id="lastname" placeholder="Lastname">
                    </div>

                    <select name="course" id="course" class="select-course">
                        <option value=" " disabled selected>Select course</option>
                        <option value="BSCS">BSCS</option>
                        <option value="BSIT">BSIT</option>
                    </select>

                    <div class="bottom-reg-adviser">
                        <select name="year" id="year">
                            <option value=" " disabled selected>Year select</option>
                            <option value="1">1th</option>
                            <option value="2">2nd</option>
                            <option value="3">3rd</option>
                            <option value="4">4th</option>
                        </select>

                        <select name="section" id="section">
                            <option value=" " disabled selected>Select section</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                        
                    </div>

                    <div class="register-btn-container">
                        <input class='register-btn' type="submit" name="edit" id="edit" value="Save">
                    </div>

                    </div>
    
                </form>
            </div>

        </div>
    </div>

</body>
</html>