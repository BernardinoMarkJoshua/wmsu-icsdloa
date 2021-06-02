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

    $admin->student_id_holder = $data->student_id_holder;
    $admin->student_id = $data->student_id;
    $admin->firstname = $data->firstname;
    $admin->middlename = $data->middlename;
    $admin->lastname = $data->lastname;
    $admin->course = $data->course;
    $admin->section = $data->section;
    $admin->year = $data->year;

    if ($admin->editAchiever()) {
        echo json_encode (
            'you have edited the users'
        );
    }
?>