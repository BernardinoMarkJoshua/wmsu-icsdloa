<?php 
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADMIN_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    }

    $api = "http://icsdloa.online/cybersolution_backend/control/";

    $ch = curl_init();
    $url = $api.'admin/fetchDefaults.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $defaults_decoded = json_decode($resp);
    } curl_close($ch);

    foreach ($defaults_decoded as $def_obj) {
        $sy = $def_obj->school_year;
        $ad = $def_obj->archive_date;
        $ad2 = $def_obj->archive_date2;
        $sem = $def_obj->semester;
        $fin = $def_obj->finalizing;
        $app = $def_obj->application;
    }



    if (isset($_POST['school_year_edit'])) {
        $school_year = $_POST['school_year'];

        $ch = curl_init();

        $url = $api.'admin/editSchool_year.php';
        $post_data = array ("school_year" => $school_year);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
         echo '<script>window.location.href="edit_misc.php"</script>';
    } 

    else if (isset($_POST['semester_edit'])) {
        $semester = $_POST['semester'];

        $ch = curl_init();

        $url = $api.'admin/editSemester.php';
        $post_data = array ("semester" => $semester);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
        echo '<script>window.location.href="edit_misc.php"</script>';
    }

    else if (isset($_POST['date_edit'])) {
        $archive_date = $_POST['month_archive'].'-'.$_POST['day_archive'];
        $ch = curl_init();

        $url = $api.'admin/editArchive.php';
        $post_data = array ("archive_date" => $archive_date);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
        echo '<script>window.location.href="edit_misc.php"</script>';

    }  else if (isset($_POST['date_edit2'])) {
        $archive_date = $_POST['month_archive'].'-'.$_POST['day_archive'];
        $ch = curl_init();

        $url = $api.'admin/editArchive2.php';
        $post_data = array ("archive_date" => $archive_date);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
        echo '<script>window.location.href="edit_misc.php"</script>';

    } else if (isset($_POST['finalizing_btn'])) {
        $finalizing = $_POST['finalizing'];
        $ch = curl_init();

        $url = $api.'admin/editFinalizing.php';
        $post_data = array ("finalizing" => $finalizing);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
        echo '<script>window.location.href="edit_misc.php"</script>';

    } else if (isset($_POST['apply_btn'])) {
        $application = $_POST['apply'];
        $ch = curl_init();

        $url = $api.'admin/editApplication.php';
        $post_data = array ("application" => $application);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
        echo '<script>window.location.href="edit_misc.php"</script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/modal2.css">
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Edit Archive</title>
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
            <p><?php echo $_SESSION['USERNAME'];?></p>
        </div>

        <div class="navigation">
            <span>Please select an option:</span>
            <a href="achievers.php">View Achievers</a>
            <a href="faculty.php">Set Faculty</a>
            <a href="viewStudents.php">Set Students</a>
            <a href="subject.php">Set Subjects</a>
            <a href="archive_landing.php">View Archive</a>
            <a href="edit_misc.php">Date Settings</a>
            <a href="facultyLanding.php">Back to Role Select</a>
            <a href="adminLanding.php">Statistics</a>
            
            <form action="archive_landing.php" method="POST">

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

    <div class="main_screen">

        <div class="table-container">
            <h1>Settings</h1>
            <div class="edit_misc_container">
                <span><strong>SET SCHOOL YEAR </strong> </span>

            <form action="edit_misc.php" method="POST" class="edit_archive_form">
                    <select class="drop-down-search" name="school_year" id="school_year" required>
                        <option value="" disbled selected >Select school year</option>
                        <option value="2020-2021">2020-2021</option>
                        <option value="2021-2022">2021-2022</option>
                        <option value="2022-2023">2022-2023</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2026-2027">2026-2027</option>
                        <option value="2027-2028">2027-2028</option>
                        <option value="2028-2029">2028-2029</option>
                        <option value="2029-2030">2029-2030</option>
                        <option value="2030-2031">2030-2031</option>
                        <option value="2031-2032">2031-2032</option>
                        <option value="2032-2033">2032-2033</option>
                        <option value="2033-2034">2033-2034</option>
                        <option value="2034-2035">2034-2035</option>
                        <option value="2035-2036">2035-2036</option>
                        <option value="2036-2037">2036-2037</option>
                        <option value="2037-2038">2037-2038</option>
                        <option value="2038-2039">2038-2039</option>
                        <option value="2039-2040">2039-2040</option>
                    </select>

                <input class="blue-btn" type="submit" name="school_year_edit" id="school_year_edit" value="Save">
            </form>




            <span><strong>SET SEMESTER</strong> </span>
                <form action="edit_misc.php" method="POST" class="edit_archive_form">
                    <select class="drop-down-search" name="semester" id="semester" required>
                        <option value="" disbled selected >Select Semester</option>
                        <option value="1">1st</option>
                        <option value="2">2nd</option>
                    </select>
                    <input class="blue-btn" type="submit" name="semester_edit" id="semester_edit" value="Save">
                </form>




            <span><strong>SET 1st SEMESTER ARCHIVE DATE</strong> </span>
                <form action="edit_misc.php" method="POST" class="edit_archive_form">

                    <select class="drop-down-search" name="month_archive" id="month_archive" required>
                        <option value="" disbled selected >Select Archive Month</option>
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">Mar</option>
                        <option value="4">Apr</option>
                        <option value="5">May</option>
                        <option value="6">Jun</option>
                        <option value="7">Jul</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>

                    <select class="drop-down-search" name="day_archive" id="day_archive" required>
                        <option value="" disbled selected >Select Archive Day</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>


                    <input  class="blue-btn" type="submit" name="date_edit" id="date_edit" value="Save">
                </form>




                <span><strong>SET 2nd SEMESTER ARCHIVE DATE</strong> </span>
                <form action="edit_misc.php" method="POST" class="edit_archive_form">

                    <select class="drop-down-search" name="month_archive" id="month_archive" required>
                        <option value="" disbled selected >Select Archive Month</option>
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">Mar</option>
                        <option value="4">Apr</option>
                        <option value="5">May</option>
                        <option value="6">Jun</option>
                        <option value="7">Jul</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>

                    <select class="drop-down-search" name="day_archive" id="day_archive" required>
                        <option value="" disbled selected >Select Archive Day</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>


                    <input  class="blue-btn" type="submit" name="date_edit2" id="date_edit2" value="Save">
                </form>

                <span><strong>ALLOW VIEWING OF ACHIEVERS </strong> </span>
                <form action="edit_misc.php" method="POST">
                    <select class="drop-down-search" name="finalizing" required>
                        <option value="" disabled selected>Select Permission</option>
                        <option value="yes">Yes</option>
                        <option value="no">no</option>
                    </select>

                    <input  class="blue-btn" type="submit" name="finalizing_btn" id="finalizing_btn" value="Save">
                </form>

                <span><strong>ALLOW APPLICATION</strong> </span>
                <form action="edit_misc.php" method="POST">
                    <select class="drop-down-search" name="apply" required>
                        <option value="" disabled selected>Select Permission</option>
                        <option value="yes">Yes</option>
                        <option value="no">no</option>
                    </select>

                    <input  class="blue-btn" type="submit" name="apply_btn" id="apply_btn" value="Save">
                </form>

                <div class="semester_info">
                    <p><strong>Cuurent School Year: </strong><?php echo $sy?></p>
                    <p><strong>First Archive Date (M-D): </strong><?php echo $ad?></p>
                    <p><strong>Second Archive Date (M-D): </strong><?php echo $ad2?></p>
                    <p><strong>Semester: </strong><?php echo $sem?></p>
                    <p><strong>Allow Viewing of Achievers: </strong><?php echo $fin?></p>
                    <p><strong>Allow Application: </strong><?php echo $app?></p>
                </div>

            </div>
                    
        </div>
            
    </div>
</div>

<script src="../assets/modal.js" type="text/javascript"></script>

</body>
</html>
