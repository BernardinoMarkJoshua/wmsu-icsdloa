<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../model/Admin.php';

    $database = new Database();
    $db = $database->connect();

    $admin = new Admin($db);
    $result = $admin->sortAdviser();
    $rowcount = $result->rowCount();

    if( $rowcount > 0) {

        $admin_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $admin_item = array(

                'faculty_id' => $faculty_id,
                'email' => $email,
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
        echo json_encode(
            "no faculty members found"
        );
    }
?>