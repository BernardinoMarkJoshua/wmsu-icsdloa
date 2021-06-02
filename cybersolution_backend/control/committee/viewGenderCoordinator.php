<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../model/Admin.php';

    $database = new Database();
    $db = $database->connect();

    $admin = new Admin($db);
    $result = $admin->viewGenderCoordinator();
    $rowcount = $result->rowCount();

    if( $rowcount > 0) {

        $admin_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $admin_item = array(
                
                'student_id' => $student_id,
                'firstname' => $firstname,
                'middlename' => $middlename,
                'lastname' => $lastname,
                'course' => $course,
                'section' => $section,
                'year' => $year,
                'date' => $date,
                'gpa' => $gpa
            );
            array_push($admin_arr, $admin_item);
        }
        echo json_encode($admin_arr);
    } 
    else {
        echo json_encode(
            "no students found"
        );
    }
?>