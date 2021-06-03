<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-with');

    include_once '../../config/Database.php';
    include_once '../../model/Student.php';

    $database = new Database();
    $db = $database->connect();

    $student = new Student($db);

    $data = json_decode(file_get_contents("php://Input"));

    $student->year = $data->year;
    $student->curriculum_name = $data->curriculum_name;

    $result = $student->fetchSubjects();
    $rowcount = $result->rowCount();

    if( $rowcount > 0) {

        $student_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $student_item = array(
                
                'subject_code' => $subject_code,
                'subject_name' => $subject_name,
                'subject_units' => $subject_units,
                
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