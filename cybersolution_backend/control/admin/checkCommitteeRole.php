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

    $admin->committee_role = $data->committee_role;

    if ($admin->checkCommitteeRole()) {
        
        $result = $admin->checkCommitteeRole();
        $rowcount = $result->rowCount();
        $admin_arr = array();

        if ($rowcount > 0) {

            echo json_encode('Role already occupied');
        }
        
        else {

            echo json_encode('Role Accepted');
        }

    } else {
        echo 'error';
    }
?>