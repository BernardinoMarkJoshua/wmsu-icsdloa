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
    $url = $api.'admin/viewFaculty.php';
    $counter = 0;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($resp);
    } curl_close($ch);

    if (isset($_POST['delete'])) {

        $faculty_id = $_POST['faculty_id'];
        $ch = curl_init();

        $url = $api.'admin/viewOneFaculty.php';
        $post_data = array ("faculty_id" => $faculty_id);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output_email = curl_exec($ch);
        $decoded_email = json_decode($output_email);

        foreach ($decoded_email as $obj_email) {
            $faculty_email = $obj_email->email;
        }

        curl_close($ch);
        $_SESSION['FACULTY_REMOVE_STATUS'] = 'yes';
        $_SESSION['FACULTY_EMAIL'] = $faculty_email;
        $_SESSION['FACULTY_ID_FACULTY'] = $faculty_id;
        echo '<script>window.location.href="reason_faculty.php"</script>';

    } else if (isset($_POST['view'])) {
        $_SESSION['ADMIN_EDIT_STATUS'] = 'yes';
        $_SESSION['faculty_edit_student_id'] = $_POST['faculty_id'];        
        echo '<script>window.location.href="adminEdit.php"</script>';
        
    } if (isset($_POST['search_btn'])) {

        $search = '%'.$_POST['search'].'%';
        $ch = curl_init();
        $url = $api.'admin/searchFaculty.php';
    
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
        $url = $api.'admin/viewFaculty.php';
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $resp = curl_exec($ch);
    
        if ($e = curl_error($ch)) {
            echo $e;
        } else {
            $decoded = json_decode($resp);
        } curl_close($ch);

    } if (isset($_POST['sort_btn'])) {
        $faculty_role = $_POST['faculty_role'];

        if ($faculty_role == 'admin') {
            $ch = curl_init();
            $url = $api.'admin/sortAdmin.php';
        
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            $resp = curl_exec($ch);
        
            if ($e = curl_error($ch)) {
                echo $e;
            } else {
                $decoded = json_decode($resp);
            } curl_close($ch);

        } else if ($faculty_role == 'adviser') {
            $ch = curl_init();
            $url = $api.'admin/sortAdviser.php';
        
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            $resp = curl_exec($ch);
        
            if ($e = curl_error($ch)) {
                echo $e;
            } else {
                $decoded = json_decode($resp);
            } curl_close($ch);

        } else if ($faculty_role == 'committee') {
            $ch = curl_init();
            $url = $api.'admin/sortCommittee.php';
        
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            $resp = curl_exec($ch);
        
            if ($e = curl_error($ch)) {
                echo $e;
            } else {
                $decoded = json_decode($resp);
            } curl_close($ch);
        }
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
            
            <form action="gate.php" method="POST">

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
            <h1>Faculty Members</h1>
                <div class="register-btn-container">
                    <a class="primary-btn" href="registerMembers.php">Add Faculty</a>
                </div>

                  
                <div class="search_bar">
                    <form action="faculty.php" method="POST">  
                        <input class="primary-txt" type="text" name="search" placeholder="Search faculty" required>
                        <input class="save-btn" type="submit" name="search_btn" value="Search">
                    </form>

                    <form action="faculty.php" method="POST">
                        <input class="search-btn" type="submit" name="refresh_btn" value="Refresh">
                    </form>
                </div>

                <div class="search_bar">
                    <form action="faculty.php" method="POST">
                        <select class="drop-down-search" name="faculty_role" required>
                            <option value="" disabled selected>Sort by role</option>
                            <option value="admin">Admin</option>
                            <option value="adviser">Adviser</option>
                            <option value="committee">Committee</option>
                        </select>

                        <input class="save-btn" type="submit" name="sort_btn" value="Sort">
                    </form>
                </div>


                <table  class="table_achievers">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Faculty id</th>
                        <th>Email</th>
                        <th>Contact number</th>
                        <th>Adviser</th>
                        <th>Admin</th>
                        <th>Committee role</th>
                        <th>Actions</th>
                    </tr>

                    <?php if ($decoded != 'no faculty members found') { ?>
                        <?php foreach ($decoded as $obj): ?>
                            <tr>
                                <?php $counter+=1;?>
                                <td><?php echo $counter; ?></td>
                                <td><?php echo $obj->name; ?></td>
                                <td><?php echo $obj->faculty_id; ?></td>
                                <td><?php echo $obj->email; ?></td>
                                <td><?php echo $obj->contact_number; ?></td>
                                <td><?php echo $obj->adviser; ?></td>
                                <td><?php echo $obj->admin; ?></td>
                                <td><?php echo $obj->committee_role; ?>
                                <td>
                                    <form action="faculty.php" method="POST">
                                        <input type="hidden" name="faculty_id" id="faculty_id" value=<?php echo $obj->faculty_id; ?>>
                                        <input type="submit" class="blue-btn" name="view" id="view" value="Edit">
                                        <input class="decline-btn" type="submit" name="delete" id="delete" value="Remove">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>

                </table>

                
        </div>
        
    </div>
</div>

<script src="../assets/modal.js" type="text/javascript"></script>

</body>
</html>
