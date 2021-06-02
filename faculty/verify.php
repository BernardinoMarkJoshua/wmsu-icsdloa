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
   $faculty_id = $_SESSION['FACULTY_ID'];
   $adviser_name = $_SESSION['USERNAME'];
   $counter = 0;
   $counter2 = 0;
   
   $ch = curl_init();
   $url = $api.'adviser/viewWaitingForms.php';

   $post_data = array ("adviser_name" => $adviser_name);

   $header = ['Content-Type: Text/plain'];

   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
   curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
   $output = curl_exec($ch);
   $decoded1 = json_decode($output);
   curl_close($ch);  

   $ch = curl_init();
   $url = $api.'adviser/viewApprovedForms.php';

   $post_data = array ("adviser_name" => $adviser_name);

   $header = ['Content-Type: Text/plain'];

   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
   curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
   $output = curl_exec($ch);
   $decoded2 = json_decode($output);

   curl_close($ch);  

   if (isset($_POST['view'])) {
       $_SESSION['verify_student_id'] = $_POST['student_id'];
       $_SESSION['VERIFY_APPLICATION_FORM_STATUS'] = 'yes';
       echo '<script>window.location.href="studentForm.php"</script>';

   } else if (isset($_POST['cancel'])) {
       $student_id = $_POST['student_id'];
       
       $ch = curl_init();
       $url = $api.'adviser/cancelForm.php';
       $post_data = array ("student_id" => $student_id);

       $header = ['Content-Type: Text/plain'];

       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
       curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
       $output = curl_exec($ch);
       echo '<script>window.location.href="verify.php"</script>';

   } else if (isset($_POST['modal'])) {
    $_SESSION['DECLINE_STUDENT_ID'] = $_POST['student_id'];
    echo" <script>window.location.href='#modal'</script>";
    
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.1">
    <link rel="stylesheet" href="../assets/modal2.css">
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Verify</title>
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
                <h1>To Verify</h1>
                <table class="table_achievers">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Student id</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>

                    <?php if (isset($decoded1)) {?>
                        <?php foreach ($decoded1 as $obj): ?>
                            <tr>
                                <?php $counter += 1;?>
                                <td><?php echo $counter;?></td>
                                <td><?php echo $obj->firstname.' '.$obj->middlename.' '.$obj->lastname; ?></td>
                                <td><?php echo $obj->student_id; ?></td>
                                <td><?php echo $obj->course; ?></td>
                                <td>
                                    <form action="verify.php" method="POST">
                                        <input type="hidden" name="student_id" value=<?php echo $obj->student_id; ?>>
                                        <input type="submit" name="view" value="View" class="primary-btn">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php }?>                    
                </table>


                <div class="table-container">
                <h1>Verified</h1>
                <table class="table_achievers">
                    <tr>
                    <th>#</th>
                        <th>Student Name</th>
                        <th>Student id</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>

                    <?php if (isset($decoded2)) {?>
                        <?php foreach ($decoded2 as $obj): ?>
                            <tr>
                                <?php $counter2 += 1;?>
                                <td><?php echo $counter2;?></td>
                                <td><?php echo $obj->firstname.' '.$obj->middlename.' '.$obj->lastname; ?></td>
                                <td><?php echo $obj->student_id; ?></td>
                                <td><?php echo $obj->course; ?></td>
                                <td>
                                    <form action="verify.php" method="POST">
                                        <input type="hidden" name="student_id" value=<?php echo $obj->student_id; ?>>
                                        <input type="submit" name="modal" value="Cancel" class="modal-open2">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php }?>                   
                </table>


                    <div class="modal" id="modal">
                        <div class="modal__content">
                        <a href="#" class="modal__close">&times;</a>
                        <h2 class="modal__heading">Confirm</h2>
                            <span>Continue to decline application form?</span>
                            <form action="verify.php" method="POST">
                                <input type="hidden" name="student_id" value="<?php echo $_SESSION['DECLINE_STUDENT_ID']?>">
                                <input type="submit" class="blue-btn" name="close" value="Cancel">
                                <input type="submit" class="primary-btn" name="cancel" value="Continue">
                            </form>
                        </div>
                    </div>
                            
             </div>
        </div>
    </div>
    
    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>