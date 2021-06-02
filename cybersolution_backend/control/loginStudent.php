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

    $faculty->student_id = $data->student_id;

    if ($faculty->loginStudent()) {
        
        $result = $faculty->loginStudent();

        $rowcount = $result->rowCount();

        $faculty_arr = array();

        if ($rowcount > 0) {

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $faculty_item = array(
                    'student_id' => $student_id,
                    'password' => $password,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'email' => $email,
                    'contact_number' => $contact_number,
                    'college' => $college,
                    'course' => $course
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