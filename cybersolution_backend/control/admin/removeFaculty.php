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

    if ($admin->removeFaculty()) {
        echo json_encode (
            'you have removed the faculty member from the faculty\'s list'
        );
    } 
?>