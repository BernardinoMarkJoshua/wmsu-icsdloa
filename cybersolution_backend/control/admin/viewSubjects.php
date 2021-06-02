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

    $admin->course_name = $data->course_name;
    $admin->subject_year = $data->subject_year;
    $admin->subject_semester = $data->subject_semester;

    if ($admin->viewSubjects()) {
        
        $result = $admin->viewSubjects();

        $rowcount = $result->rowCount();

        $admin_arr = array();

        if ($rowcount > 0) {

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $admin_item = array(
                    'subject_code' => $subject_code,
                    'subject_name' => $subject_name,
                    'subject_units' => $subject_units,
                    'subject_curriculum' => $subject_curriculum,
                    'subject_year' => $subject_year,
                    'subject_semester' => $subject_semester
                );


                array_push($admin_arr, $admin_item);
            }
        echo json_encode($admin_arr);
        }
        
        else {
            echo json_encode('Subjects do not exist');
        }

    } else {
        echo 'System error';
    }
?>