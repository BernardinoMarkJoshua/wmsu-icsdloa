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

    $admin->school_year = $data->school_year;
    $admin->semester = $data->semester;

    if ($admin->viewArchive()) {
        
        $result = $admin->viewArchive();

        $rowcount = $result->rowCount();

        $admin_arr = array();

        if ($rowcount > 0) {

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $admin_item = array(
                    'student_id' => $student_id,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'section' => $section,
                    'course' => $course,
                    'year' => $year,
                    'semester' => $semester,
                    'date' => $date,
                    'gpa' => $gpa,
                    'school_year' => $school_year
                );


                array_push($admin_arr, $admin_item);
            }
        echo json_encode($admin_arr);
        }
        
        else {
            echo json_encode('No archive exist');
        }

    } else {
        echo 'System error';
    }
?>