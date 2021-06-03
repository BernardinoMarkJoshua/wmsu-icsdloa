<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADVISER_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    } 

    $api = "http://icsdloa.online/cybersolution_backend/control/";
    
    $ch = curl_init();
    $url = $api.'adviser/fetchStudents.php';
    $counter = 0;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($resp);
    } curl_close($ch);

    if (isset($_POST['search_btn'])) {

        $search = '%'.$_POST['search'].'%';
        $ch = curl_init();
        $url = $api.'adviser/search.php';
    
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

    } else if (isset($_POST['refresh_btn'])) {
        $ch = curl_init();
        $url = $api.'adviser/fetchStudents.php';
    
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
    <meta name="viewport" content="width=device-width, initial-scale=0.1">
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Student</title>
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
                <a href="gate.php">Student Registration</a>
                <a href="student.php">List of Students</a>
                <a href="verify.php">Verify DLOA Appform</a>
                <a href="facultyLanding.php">Back to Role Select</a>
                
                <form action="adviserMain.php" method="POST">

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
                <h1>List of Students</h1>

                <div class="search_bar">
                    <form action="student.php" method="POST">  
                        <input class="primary-txt" type="text" name="search" placeholder="Search Student" required>
                        <input class="save-btn" type="submit" name="search_btn" value="Search">
                    </form>

                    <form action="student.php" method="POST">
                        <input class="search-btn" type="submit" name="refresh_btn" value="Refresh">
                    </form>
                </div>

                <table class="table_achievers">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Student id</th>
                        <th>College</th>
                        <th>Course</th>
                    </tr>
    
                    <?php if ($decoded != "no students found") { ?>
                        <?php if ($decoded != NULL) { ?>
                            <?php foreach ($decoded as $obj): ?>
                                <tr class="student-table">
                                    <?php $counter+=1;?>
                                    <td><?php echo $counter; ?></td>
                                    <td><?php echo $obj->lastname.', '.$obj->firstname.' '.$obj->middlename; ?></td>
                                    <td><?php echo $obj->student_id; ?></td>
                                    <td><?php echo $obj->college; ?></td>
                                    <td><?php echo $obj->course; ?></td>
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