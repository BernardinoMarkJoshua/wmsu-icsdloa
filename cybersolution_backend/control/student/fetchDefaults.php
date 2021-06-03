<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../model/Student.php';

    $database = new Database();
    $db = $database->connect();

    $student = new Student($db);
    $result = $student->fetchDefaults();
    $rowcount = $result->rowCount();

    if( $rowcount > 0) {
        $student_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $student_item = array(

                'school_year' => $school_year,
                'semester' => $semester,
                'finalizing' => $finalizing,
                'application' => $application

            );
            array_push($student_arr, $student_item);
        }
        echo json_encode($student_arr);
    } 
    else {
        echo json_encode(
            "no students found"
        );
    }
?>