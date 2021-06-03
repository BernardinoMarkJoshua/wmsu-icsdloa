<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../model/Adviser.php';

    $database = new Database();
    $db = $database->connect();

    $adviser = new Adviser($db);
    $result = $adviser->chairperson();
    $rowcount = $result->rowCount();

    if( $rowcount > 0) {
        $adviser_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $adviser_item = array(

                'name' => $name
            );
            array_push($adviser_arr, $adviser_item);
        }
        echo json_encode($adviser_arr);
    } 
    else {
        echo json_encode(
            "no students found"
        );
    }
?>