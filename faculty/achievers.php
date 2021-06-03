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
    $counter = 0;

    $ch = curl_init();
    $url = $api.'admin/viewAchievers.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($resp);
    } curl_close($ch);

    if (isset($_POST['delete'])) {
        $student_id = $_POST['student_id'];

        $ch = curl_init();

        $url = $api.'adviser/fetchOneStudent.php';
        $post_data = array ("student_id" => $student_id);
        $header = ['Content-Type: Text/plain'];
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output1 = curl_exec($ch);
        $student_decoded = json_decode($output1);
    
        foreach ($student_decoded as $obj1) {
            $student_email = $obj1->email;
            $student_name = $obj1->firstname;
        }
    
        if ($output1 === false) {
            echo "cURL Error: " . curl_error($ch);
        }
        curl_close($ch);
        $_SESSION['ACHIEVER_STUDENT_ID_REMOVE'] = $_POST['student_id'];
        $_SESSION['ACHIEVER_EMAIL_REMOVE'] = $student_email;
        $_SESSION['ACHIEVER_REMOVE_STATUS'] = 'yes';
        echo '<script>window.location.href="reason_remove_achievers.php"</script>';

    } if (isset($_POST['logout'])) {
        session_destroy();
        echo "<script>window.location.href='../index.php'</script>";

    } if (isset($_POST['search_btn'])) {

        $search = '%'.$_POST['search'].'%';
        $ch = curl_init();
        $url = $api.'admin/searchAchievers.php';
    
        $post_data = array ("search" => $search);
        $header = ['Content-type: Text/plain'];
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    
        $output = curl_exec($ch);
        $decoded = json_decode($output);
        curl_close($ch);

    } if (isset($_POST['refresh_btn'])) {

        $ch = curl_init();
        $url = $api.'admin/viewAchievers.php';
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $resp = curl_exec($ch);
    
        if ($e = curl_error($ch)) {
            echo $e;
        } else {
            $decoded = json_decode($resp);
        } curl_close($ch);
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
    <title>ICS-DLOA | Achievers</title>
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
                
                <form action="achievers.php" method="POST">

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
            <h1>Current Achievers</h1>

                <div class="search_bar">
                    <form action="achievers.php" method="POST">  
                        <input class="primary-txt" type="text" name="search" placeholder="Search Achiever" required>
                        <input class="save-btn" type="submit" name="search_btn" value="Search">
                    </form>

                    <form action="achievers.php" method="POST">
                        <input class="search-btn" type="submit" name="refresh_btn" value="Refresh">
                    </form>
                </div>
            

                <table  class="table_achievers">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Student id</th>
                        <th>Section</th>
                        <th>Year Level</th>
                        <th>Gpa</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Actions</th>
                    </tr>

                    <?php if ($decoded != NULL) { ?>
                        <?php if ($decoded != 'no achievers found') { ?>
                            <?php foreach ($decoded as $obj): ?>
                                <tr>
                                    <?php $counter+=1;?>
                                    <td><?php echo $counter;?></td>
                                    <td><?php echo $obj->lastname.', '.$obj->firstname.' '.$obj->middlename; ?></td>
                                    <td><?php echo $obj->student_id; ?></td>
                                    <td><?php echo $obj->section; ?></td>
                                    <td><?php echo $obj->year; ?></td>
                                    <td><?php echo $obj->gpa; ?></td>
                                    <td><?php echo $obj->course; ?></td>
                                    <td><?php echo $obj->semester; ?></td>
                                    <td>
                                        <form action="achievers.php" method="POST">
                                            <input type="hidden" name="student_id" id="student_id" value=<?php echo $obj->student_id; ?>>
                                            <input class="decline-btn" type="submit" name="delete" id="delete" value="Remove">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php } ?>
                    <?php } ?>

                </table>
 
        </div>
    </div>
</div>

<script src="../assets/modal.js" type="text/javascript"></script>
 
</body>
</html>
