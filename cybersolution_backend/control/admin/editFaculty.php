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

    $admin->faculty_id_change = $data->faculty_id_change;
    $admin->faculty_id = $data->faculty_id;
    $admin->email = $data->email;
    $admin->name = $data->name;
    $admin->password = $data->password;
    $admin->adviser = $data->adviser;
    $admin->admin = $data->admin;
    $admin->committee_role = $data->committee_role;
    $admin->contact_number = $data->contact_number;

    if ($admin->editFaculty()) {
        echo json_encode (
            'successfully edited'
        );
    }
?>