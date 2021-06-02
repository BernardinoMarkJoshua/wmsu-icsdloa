<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    }

    $display_ics_director = 'display :none;';
    $display_chairperson = 'display :none;';
    $display_gender_coordinator = 'display :none;';
    $display_institute_secretary = 'display :none;';
    $display_student_affair_coordinator = 'display :none;';
    $display_information_technology_department_head = 'display :none;';
    $display_computer_science_department_head = 'display :none;';

    $faculty_id = $_SESSION['FACULTY_ID'];
    $username = $_SESSION['USERNAME'];
    $adviser = $_SESSION['ADVISER'];
    $admin = $_SESSION['ADMIN'];
    $committee_role = $_SESSION['COMMITTEE_ROLE'];
    
    if ($admin == 'yes') {
        $display_admin = 'display : block; display: flex; align-items: center;';
        $_SESSION['ADMIN_STATUS'] = 'yes';

    } else if ($admin == 'no') {
        $display_admin = 'display : none;';
        $_SESSION['ADMIN_STATUS'] = 'no';

    } if ($adviser == 'yes') {
        $display_adviser = 'display : block; display: flex; align-items: center;';
        $_SESSION['ADVISER_STATUS'] = 'yes';

    } else if ($adviser == 'no') {
        $display_adviser = 'display : none;';
        $_SESSION['ADVISER_STATUS'] = 'no';

    } if ($committee_role == 'ICS Director') {
        $display_ics_director = 'display : block; display: flex; align-items: center;';
        $_SESSION['ICS_DIRECTOR_STATUS'] = 'yes';

    } else if ($committee_role == 'Chairperson') {
        $display_chairperson = 'display : block; display: flex; align-items: center;';
        $_SESSION['CHAIRPERSON_STATUS'] = 'yes';

    } else if ($committee_role == 'Gender Guidance') {
        $display_gender_coordinator = 'display : block; display: flex; align-items: center;';
        $_SESSION['GENDER_STATUS'] = 'yes';

    } else if ($committee_role == 'Institute Secretary') {
        $display_institute_secretary = 'display : block; display: flex; align-items: center;';
        $_SESSION['INSTITUTE_SEC_STATUS'] = 'yes';

    } else if ($committee_role == 'Student Affair Coordinator') {
        $display_student_affair_coordinator = 'display : block; display: flex; align-items: center;';
        $_SESSION['STUDENT_COORD_STATUS'] = 'yes';

    } else if ($committee_role == 'IT Head') {
        $display_information_technology_department_head = 'display : block; display: flex; align-items: center;';
        $_SESSION['IT_HEAD_STATUS'] = 'yes';

    } else if ($committee_role == 'CS Head') {
        $display_computer_science_department_head = 'display : block; display: flex; align-items: center;';
        $_SESSION['CS_HEAD_STATUS'] = 'yes';

    } else if ($committee_role == 'none') {
        $display_ics_director = 'display :none;';
        $display_chairperson = 'display :none;';
        $display_gender_coordinator = 'display :none;';
        $display_institute_secretary = 'display :none;';
        $display_student_affair_coordinator = 'display :none;';
        $display_information_technology_department_head = 'display :none;';
        $display_computer_science_department_head = 'display :none;';
        $_SESSION['ICS_DIRECTOR_STATUS'] = 'no';
        $_SESSION['CHAIRPERSON_STATUS'] = 'no';
        $_SESSION['GENDER_STATUS'] = 'no';
        $_SESSION['INSTITUTE_SEC_STATUS'] = 'no';
        $_SESSION['STUDENT_COORD_STATUS'] = 'no';
        $_SESSION['IT_HEAD_STATUS'] = 'no';
        $_SESSION['CS_HEAD_STATUS'] = 'no';
    } 

    if (isset($_POST['logout'])) {
        session_destroy();
        echo "<script>window.location.href='../index.php'</script>";
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.1">
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Faculty</title>
</head>
<body>

    <div class="faculty_container">

        <div class="side_panel">

            <div class="ics_dloa">
                <img class="ics_logo" src="../assets/images/icslogo.png" alt="ics_logo">
                <div>
                    <span><strong>Western Mindanao State University</strong></span>
                    <h4>ICS-DLOA</h4>
                    <span>Faculty Member</span>
                </div>
            </div>

            <div class="name_container">
                <span>Welcome</span>
                <p><?php echo $username ?></p>
            </div>

            <div class="navigation">
                <span>Role Select</span>

                <a href="adminLanding.php" style = "<?php echo $display_admin; ?>">Admin</a>
                <a href="approveIcsDirector.php" style = "<?php echo $display_ics_director; ?>">ICS Director</a>
                <a href="approveChairperson.php" style = "<?php echo $display_chairperson; ?>">Chairperson</a>
                <a href="approveGenderCoordinator.php" style = "<?php echo $display_gender_coordinator; ?>">Gender and Guidance Counseling</a>
                <a href="approveSecretary.php" style = "<?php echo $display_institute_secretary; ?>">Institute Secretary</a>
                <a href="approveStudentAffair.php" style = "<?php echo $display_student_affair_coordinator; ?>">Student Affair Coordinator</a>
                <a href="approveInformationTechnology.php" style = "<?php echo $display_information_technology_department_head; ?>">Information Technology Department Head</a>
                <a href="approveComputerScience.php" style = "<?php echo $display_computer_science_department_head; ?>">Computer Science Department Head</a>
                <a href="adviserMain.php" style = "<?php echo $display_adviser; ?>">Adviser</a>

                <form action="facultyLanding.php" method="POST">

                    <a onclick="showModal()">Logout</a>
                    <div id="alertBox">
                        <div id="box">
                            <div class="heading">
                                Confirm
                            </div>
                            <div class="content">
                                <p>Are you sure you wish to logout?</p>
                                <div id="button_container">
                                    <input type="submit" name="logout" id="logout" value="Yes" onclick="hideAlert()">
                                    <button id="cancel" onclick="hideAlert()">cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>