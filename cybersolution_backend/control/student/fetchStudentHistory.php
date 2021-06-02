<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-with');

    include_once '../../config/Database.php';
    include_once '../../model/Student.php';

    $database = new Database();
    $db = $database->connect();

    $student = new Student($db);

    $data = json_decode(file_get_contents("php://Input"));

    $student->student_id = $data->student_id;

    if ($student->viewArchive()) {
        
        $result = $student->viewArchive();

        $rowcount = $result->rowCount();

        $student_arr = array();

        if ($rowcount > 0) {

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $student_item = array(
                    
                    'section' => $section,
                    'course' => $course,
                    'year' => $year,
                    'semester' => $semester,
                    'date' => $date,
                    'school_year' => $school_year,
                    'gpa' => $gpa
                );


                array_push($student_arr, $student_item);
            }
        echo json_encode($student_arr);
        }
        
        else {
            echo json_encode('No archive exist');
        }

    } else {
        echo 'System error';
    }
?>