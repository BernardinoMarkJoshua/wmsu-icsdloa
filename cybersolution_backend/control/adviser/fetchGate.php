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

    $adviser->adviser_name = $data->adviser_name;

    if ($adviser->viewGate()) {
        
        $result = $adviser->viewGate();
        $rowcount = $result->rowCount();
        $adviser_arr = array();

        if ($rowcount > 0) {

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $adviser_item = array(
                    'student_id' => $student_id,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'course' => $course
                );

                array_push($adviser_arr, $adviser_item);
            }
        echo json_encode($adviser_arr);
        }
        
        else {
            echo 'no students found';
        }

    } else {
        echo 'error';
    }
?>