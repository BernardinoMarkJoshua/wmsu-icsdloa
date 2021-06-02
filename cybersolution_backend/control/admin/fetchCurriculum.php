<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../model/Admin.php';

    $database = new Database();
    $db = $database->connect();

    $admin = new Admin($db);
    $result = $admin->fetchCurriculum();
    $rowcount = $result->rowCount();

    if( $rowcount > 0) {
        $admin_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $admin_item = array(

                'curriculum_name' => $curriculum_name

            );
            array_push($admin_arr, $admin_item);
        }
        echo json_encode($admin_arr);
    } 
    else {
        echo json_encode(
            "no Admins found"
        );
    }
?>