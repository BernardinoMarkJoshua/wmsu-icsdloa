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
$student->password = $data->password;
$student->firstname = $data->firstname;
$student->middlename = $data->middlename;
$student->lastname = $data->lastname;
$student->email = $data->email;
$student->contact_number = $data->contact_number;
$student->college = $data->college;
$student->course = $data->course;
$student->adviser = $data->adviser;

if ($student->registerStudent()) {
    echo json_encode (
        'successfully registered'
    );
} else {
    echo json_encode (
        'student already exist'
    );
}

?>