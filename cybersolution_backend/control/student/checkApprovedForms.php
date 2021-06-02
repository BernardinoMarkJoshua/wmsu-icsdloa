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

    $student->student_id = $data->student_id;

    $result = $student->checkApprovedForms();
    $rowcount = $result->rowCount();

    if( $rowcount > 0) {
        echo json_encode(
            "form already exists waiting for department head approval approval"
        );
    } 
    else {
        echo json_encode(
            "form does not exist"
        );
    }
?>