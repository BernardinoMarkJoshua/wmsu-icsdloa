<?php
    session_start();
    
    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADVISER_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    } 

    if (isset($_POST['logout'])) {
        session_destroy();
        echo '<script>window.location.href="../index.php"</script>';
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
    <title>ICS-DLOA | Adviser</title>
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
    </div>
    
    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>