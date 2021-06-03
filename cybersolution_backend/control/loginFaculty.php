<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-with');

    include_once '../config/Database.php';
    include_once '../model/Login.php';

    $database = new Database();
    $db = $database->connect();

    $faculty = new Login($db);

    $data = json_decode(file_get_contents("php://Input"));

    $faculty->faculty_id = $data->faculty_id;

    if ($faculty->loginFaculty()) {
        
        $result = $faculty->loginFaculty();

        $rowcount = $result->rowCount();

        $faculty_arr = array();

        if ($rowcount > 0) {

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $faculty_item = array(
                    'faculty_id' => $faculty_id,
                    'email' => $email,
                    'name' => $name,
                    'password' => $password,
                    'adviser' => $adviser,
                    'admin' => $admin,
                    'committee_role' => $committee_role
                );


                array_push($faculty_arr, $faculty_item);
            }
        echo json_encode($faculty_arr);
        }
        
        else {
            echo json_encode('Account does not exist');
        }

    } else {
        echo 'System error';
    }
?>