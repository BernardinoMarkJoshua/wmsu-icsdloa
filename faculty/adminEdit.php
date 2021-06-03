<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADMIN_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    } else if ($_SESSION['ADMIN_EDIT_STATUS'] != 'yes') {
        echo '<script>window.location.href="faculty.php"</script>';
    }

    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $msg = "";


    $ch = curl_init();
    $url = $api.'admin/viewOneFaculty.php';
    $faculty_id_holder = $_SESSION['faculty_edit_student_id'];

    $post_data = array ("faculty_id" => $faculty_id_holder);

    $header = ['Content-Type: Text/plain'];
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $faculty_details = json_decode($output);

    curl_close($ch);


    foreach ($faculty_details as $faculty_obj) {
        $faculty_id_default = $faculty_obj->faculty_id;
        $email_default = $faculty_obj->email;
        $name_default = $faculty_obj->name;
        $password_default = $faculty_obj->password;
        $admin_default = $faculty_obj->admin;
        $adviser_default = $faculty_obj->adviser;
        $committee_role_default = $faculty_obj->committee_role;
        $contact_number_default = $faculty_obj->contact_number; 
    }

    $name_exploded = explode(" ", $name_default);
    
    if (array_key_exists("2", $name_exploded)) {
        $firstname = $name_exploded[0];
        $middlename = $name_exploded[1];
        $lastname = $name_exploded[2];
    } else {
        $firstname = $name_exploded[0];
        $middlename = " ";
        $lastname = $name_exploded[1];
    }

    if (isset($_POST['save'])) {

        $faculty_id_change = $_SESSION['faculty_edit_student_id'];
        $faculty_id = $_POST['faculty_id'];
        $email = $_POST['email'];
        $name = $_POST['firstname'].' '.$_POST['middlename'].' '.$_POST['lastname'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        $adviser = $_POST['adviser'];
        $admin = $_POST['admin'];
        $committee_role = $_POST['committee_role'];
        $contact_number = $_POST['contact'];

        $ch = curl_init();
        $url = $api.'admin/checkCommitteeRole.php';
    
        $post_data = array ("committee_role" => $committee_role);
    
        $header = ['Content-Type: Text/plain'];
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $committee_role_resp = json_decode($output);
        curl_close($ch);
        
        $ch = curl_init();
        $url = $api.'admin/checkMemberRole.php';
        $post_data = array (
            "faculty_id_change" => $faculty_id_change
        );
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $member_role_decoded = json_decode($output);
        
        foreach ($member_role_decoded as $obj_member_role) {
            $member_role = $obj_member_role->committee_role;
        }

        if ($committee_role == 'none') {
            if ($password_confirm != $password) {

                $msg = 'password does not match';
            } else {

                $hash = password_hash($password, PASSWORD_BCRYPT);
                $ch = curl_init();
                $url = $api.'admin/editFaculty.php';
                $post_data = array (
                    "faculty_id_change" => $faculty_id_change,
                    "faculty_id" => $faculty_id,
                    "email" => $email,
                    "name" => $name,
                    "password" => $hash,
                    "adviser" => $adviser,
                    "admin" => $admin,
                    "committee_role" => $committee_role,
                    "contact_number" => $contact_number
                );
                $header = ['Content-Type: Text/plain'];
    
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $output = curl_exec($ch);
                $committee_role_decoded = json_decode($output);
                    
                if ($committee_role_decoded === NULL) {
                    $msg = "The ID you entered is already in use please check your input and try again";
                }
                curl_close($ch);
                $_SESSION['ADMIN_EDIT_STATUS'] = 'no';
                echo '<script>window.location.href="faculty.php"</script>';
            }

        } else if ($committee_role_resp == 'Role Accepted') {

            if ($password_confirm != $password) {

                $msg = 'password does not match';
            } else {

                $hash = password_hash($password, PASSWORD_BCRYPT);
                $ch = curl_init();
                $url = $api.'admin/editFaculty.php';
                $post_data = array (
                    "faculty_id_change" => $faculty_id_change,
                    "faculty_id" => $faculty_id,
                    "email" => $email,
                    "name" => $name,
                    "password" => $hash,
                    "adviser" => $adviser,
                    "admin" => $admin,
                    "committee_role" => $committee_role,
                    "contact_number" => $contact_number
                );
                $header = ['Content-Type: Text/plain'];
    
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $output = curl_exec($ch);
                $committee_role_decoded = json_decode($output);
    
                if ($committee_role_decoded === NULL) {
                    $msg = "The ID you entered is already in use please check your input and try again";
                }
    
                curl_close($ch);
                echo '<script>window.location.href="faculty.php"</script>';
            }
            
        } else if ($member_role == $committee_role) {
            if ($password_confirm != $password) {

                $msg = 'password does not match';
            } else {

                $hash = password_hash($password, PASSWORD_BCRYPT);
                $ch = curl_init();
                $url = $api.'admin/editFaculty.php';
                $post_data = array (
                    "faculty_id_change" => $faculty_id_change,
                    "faculty_id" => $faculty_id,
                    "email" => $email,
                    "name" => $name,
                    "password" => $hash,
                    "adviser" => $adviser,
                    "admin" => $admin,
                    "committee_role" => $committee_role,
                    "contact_number" => $contact_number
                );
                $header = ['Content-Type: Text/plain'];
    
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $output = curl_exec($ch);
                $committee_role_decoded = json_decode($output);
    
                if ($committee_role_decoded === NULL) {
                    $msg = "The ID you entered is already in use please check your input and try again";
                }
    
                curl_close($ch);
                echo '<script>window.location.href="faculty.php"</script>';
            }
        } else {
            $msg = 'role chosen is already in use please enter a different role';
        } 

    } else if (isset($_POST['cancel_edit'])) {
        $_SESSION['ADMIN_EDIT_STATUS'] = 'yes';
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
    <title>ICS-DLOA | Register</title>
</head>
<body>
    
<div class="faculty-register-main-container">
        <div class="faculty-register-container">
            <div class="adviser-register">

                <div class="information">
                    <h2>ICS-DLOA Edit Faculty</h2>
                    <p>
                        This Edit form is for the Faculty of the Western Mindanao State University 
                        Institute of Computer Studies department.
                    </p>
                    <a class="cancel-btn" href="faculty.php">Cancel Edit</a>
                </div>

                <form action="adminEdit.php" method="POST" class="adviser-register-form">

                    <div class="top-reg-adviser">
                        <input type="text" name="faculty_id" id="faculty_id" placeholder="Faculty id" value="<?php echo $faculty_id_default;?>" required>
                        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $email_default?>" required>
                    </div>

                    <div class="name-reg-adviser">
                        <input type="text" name="firstname" id="firstname" placeholder="Firstname" value="<?php echo $firstname?>" required>
                        <input type="text" name="middlename" id="middlename" placeholder="Middlename (OPTIONAL)" value="<?php echo $middlename?>">
                        <input type="text" name="lastname" id="lastname" placeholder="Lastname" value="<?php echo $lastname?>" required>
                    </div>

                    <div class="password-reg-adviser">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <input type="password" name="password_confirm" name="password_confirm" placeholder="Password confirm" required>
                    </div>

                    <div class="bottom-reg-adviser">
                        <select name="adviser" id="adviser" required>
                            <option value="<?php echo $adviser_default?>">Adviser Role</option>
                            <option value="yes">yes</option>
                            <option value="no">no</option>
                        </select>

                        <select name="admin" id="admin" required>
                            <option value="<?php echo $admin_default?>">Admin Role</option>
                            <option value="yes">yes</option>
                            <option value="no">no</option>
                        </select>

                        <select name="committee_role" id="committee_role" required>
                            <option value="<?php echo $committee_role_default?>">Committee Role Select</option>
                            <option value="ICS Director">ICS Director</option>
                            <option value="Chairperson">Chairperson</option>
                            <option value="Gender Guidance">Gender and Guidance Counseling</option>
                            <option value="Institute Secretary">Institute Secretary</option>
                            <option value="Student Affair Coordinator">Student Affair Coordinator</option>
                            <option value="IT Head">IT Head</option>
                            <option value="CS Head">CS Head</option>
                            <option value="none">none</option>
                        </select>
                    </div>
                    
                    <div class="long-input">
                        <input type="text" name="contact" id="contact" placeholder="Contact number" value="<?php echo $contact_number_default?>" required>
                    </div>

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

                    <?php echo $msg;?>
                </form>
            </div>

        </div>
    </div>

</body>
</html>