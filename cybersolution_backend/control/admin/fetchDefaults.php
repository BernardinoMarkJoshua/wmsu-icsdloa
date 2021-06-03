<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once '../../config/Database.php';
   include_once '../../model/Admin.php';

   $database = new Database();
   $db = $database->connect();

   $admin = new Admin($db);
   $result = $admin->fetchDefaults();
   $rowcount = $result->rowCount();

   if( $rowcount > 0) {

       $admin_arr = array();

       while($row = $result->fetch(PDO::FETCH_ASSOC)) {
           extract($row);
           $admin_item = array(

               'school_year' => $school_year,
               'archive_date' => $archive_date,
               'archive_date2' => $archive_date2,
               'semester' => $semester,
               'finalizing' => $finalizing,
               'application' => $application
           );
           array_push($admin_arr, $admin_item);
       }
       echo json_encode($admin_arr);
   } 
   else {
       echo json_encode(
           "no Defaults found"
       );
   }

?>