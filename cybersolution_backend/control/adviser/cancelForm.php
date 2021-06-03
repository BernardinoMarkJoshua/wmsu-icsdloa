<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-with');

    include_once '../../config/Database.php';
    include_once '../../model/Adviser.php';

    $database = new Database();
    $db = $database->connect();

    $adviser = new Adviser($db);

    $data = json_decode(file_get_contents("php://Input"));

    $adviser->student_id = $data->student_id;

    if ($adviser->cancelForm()) {
        echo json_encode (
            'you have canceled the approval'
        );
    
    }
?>