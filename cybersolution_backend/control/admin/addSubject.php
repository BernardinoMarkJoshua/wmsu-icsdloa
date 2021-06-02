<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-with');

    include_once '../../config/Database.php';
    include_once '../../model/Admin.php';

    $database = new Database();
    $db = $database->connect();

    $admin = new Admin($db);

    $data = json_decode(file_get_contents("php://Input"));

    $admin->subject_code = $data->subject_code;
    $admin->subject_name = $data->subject_name;
    $admin->subject_year = $data->subject_year;
    $admin->subject_semester = $data->subject_semester;
    $admin->curriculum_name = $data->curriculum_name;
    $admin->subject_units = $data->subject_units;

    if ($admin->addSubject()) {
        echo json_encode (
            'successfully registered'
        );
    }

?>