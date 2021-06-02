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

    $admin->search = $data->search;

    if ($admin->searchFaculty()) {
        
        $result = $admin->searchFaculty();

        $rowcount = $result->rowCount();

        $admin_arr = array();

        if ($rowcount > 0) {

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $admin_item = array(
                    'email' => $email,
                    'faculty_id' => $faculty_id,
                    'name' => $name,
                    'adviser' => $adviser,
                    'admin' => $admin,
                    'committee_role' => $committee_role,
                    'contact_number' => $contact_number
                );


                array_push($admin_arr, $admin_item);
            }
        echo json_encode($admin_arr);
        }
        
        else {
            echo 'error';
        }

    } else {
        echo 'error';
    }
?>