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

    $admin->faculty_id = $data->faculty_id;
    $admin->course = $data->course;
    $admin->year = $data->year;
    $admin->section = $data->section;
    $admin->semester = $data->semester;

    if ($admin->adviserInfo()) {
        echo json_encode (
            'adviser info added'
        );
    }

?>