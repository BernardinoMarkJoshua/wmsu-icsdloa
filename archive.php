<?php
    $api = "http://icsdloa.online/cybersolution_backend/control/";

    $ch = curl_init();
    $url = $api.'admin/fetchDefaults.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded1 = json_decode($resp);
    } curl_close($ch);

    foreach ($decoded1 as $obj) {
        $school_year = $obj->school_year;
        $archive_date = $obj->archive_date; 
        $archive_date2 = $obj->archive_date2; 
        $semester = $obj->semester;
    }
  
    date_default_timezone_set('Asia/Manila');
    
    if (date('n-j') == $archive_date) {
        if ($semester == 1) {
            $semester_edit = 2;

            $ch = curl_init();
            $url = $api.'admin/archive.php';
        
            curl_setopt($ch, CURLOPT_URL, $url);
        
            $resp = curl_exec($ch);
        
            if ($e = curl_error($ch)) {
                echo $e;
            } else {
                $archive = json_decode($resp);
            } curl_close($ch);

            $ch = curl_init();

            $url = $api.'admin/editSemester.php';
            $post_data = array ("semester" => $semester_edit);
            $header = ['Content-Type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);

            curl_close($ch);
            
        } else if ($semester == 2) {
            $semester_edit = 1;

            $ch = curl_init();
            $url = $api.'admin/archive.php';
        
            curl_setopt($ch, CURLOPT_URL, $url);
        
            $resp = curl_exec($ch);
        
            if ($e = curl_error($ch)) {
                echo $e;
            } else {
                $archive = json_decode($resp);
            } curl_close($ch);


            $ch = curl_init();

            $url = $api.'admin/editSemester.php';
            $post_data = array ("semester" => $semester_edit);
            $header = ['Content-Type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);

            curl_close($ch);
            
            $school_year_calc = date('Y');
            $school_year_calc1 = $school_year_calc + 1;
            $current_school_year = $school_year_calc.'-'.$school_year_calc1;
            
            $ch = curl_init();

            $url = $api.'admin/editSchool_year.php';
            $post_data = array ("school_year" => $current_school_year);
            $header = ['Content-Type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);

            curl_close($ch);
        }
    }
    
    
    if (date('n-j') == $archive_date2) {
        if ($semester == 1) {
            $semester_edit = 2;

            $ch = curl_init();
            $url = $api.'admin/archive.php';
        
            curl_setopt($ch, CURLOPT_URL, $url);
        
            $resp = curl_exec($ch);
        
            if ($e = curl_error($ch)) {
                echo $e;
            } else {
                $archive = json_decode($resp);
            } curl_close($ch);

            $ch = curl_init();

            $url = $api.'admin/editSemester.php';
            $post_data = array ("semester" => $semester_edit);
            $header = ['Content-Type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);

            curl_close($ch);
            
        } else if ($semester == 2) {
            $semester_edit = 1;

            $ch = curl_init();
            $url = $api.'admin/archive.php';
        
            curl_setopt($ch, CURLOPT_URL, $url);
        
            $resp = curl_exec($ch);
        
            if ($e = curl_error($ch)) {
                echo $e;
            } else {
                $archive = json_decode($resp);
            } curl_close($ch);


            $ch = curl_init();

            $url = $api.'admin/editSemester.php';
            $post_data = array ("semester" => $semester_edit);
            $header = ['Content-Type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);

            curl_close($ch);
            
            $school_year_calc = date('Y');
            $school_year_calc1 = $school_year_calc + 1;
            $current_school_year = $school_year_calc.'-'.$school_year_calc1;
            
            $ch = curl_init();

            $url = $api.'admin/editSchool_year.php';
            $post_data = array ("school_year" => $current_school_year);
            $header = ['Content-Type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);

            curl_close($ch);
        }
    }

?>
 