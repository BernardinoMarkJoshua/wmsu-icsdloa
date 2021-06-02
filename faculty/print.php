<?php
    session_start();
    $api = "http://icsdloa.online/cybersolution_backend/control/";

    $date_issued = " ";
    $student_semester = 0;
    $student_name = " ";
    $student_id = $_SESSION['verify_student_id'];
    $student_section = " ";
    $student_year = 0;
    $student_course = " ";
    $adviser_name = $_SESSION['USERNAME'];
    $school_year = " ";
    $subject_units = 0;

    $ics_director=" ";
    $ggc_coordinator = " ";
    $institute_secretary = " ";
    $student_affair_coordinator = " ";
    $information_technology_department_head = " ";
    $computer_science_department_head = " ";
    $chairperson = " ";
    

    #fetch grades start

    $ch = curl_init();
    $url = $api.'adviser/fetchGrade.php';
    $post_data = array ("student_id" => $student_id);
    $header =  ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $decoded_grades = json_decode($output);

    foreach ($decoded_grades as $obj_grades) {
        $subject_units = $obj_grades->subject_unit + $subject_units;
    }


    curl_close($ch);

    #fetch grades end

    #fetch form start

    $ch = curl_init();
    $url = $api.'adviser/viewOneForm.php';
    $post_data = array ("student_id" => $student_id);
    $header =  ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $decoded_appform = json_decode($output);

    foreach ($decoded_appform as $obj) {
        $student_name = $obj->lastname .", ". $obj->firstname .", ". $obj->middlename;
        $student_course = $obj->course;
        $student_section = $obj->section;
        $student_year = $obj->year;
        $school_year = $obj->school_year;
    
        if ($obj->semester == 1) {
            $student_semester = $obj->semester ."st";
        } else {
            $student_semester = $obj->semester ."nd";
        }
        $date_issued = $obj->date;
        $gpa = $obj->gpa;
    }

    curl_close($ch);

    #fetch form end


    #fetch ics director start

    $ch = curl_init();
    $url = $api.'adviser/icsDirector.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    $ics_director_decoded = json_decode($resp);
    curl_close($ch);

    foreach ($ics_director_decoded as $obj1) {
        $ics_director = $obj1->name;
    }

    #fetch ics director end

    #fetch gender coordinator start

    $ch = curl_init();
    $url = $api.'adviser/genderGuidance.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    $ggc_coordinator_decoded = json_decode($resp);
    curl_close($ch);

    foreach ($ggc_coordinator_decoded as $obj1) {
        $ggc_coordinator = $obj1->name;
    }

    #fetch gender coordinator end


    #fetch institute secretary start

    $ch = curl_init();
    $url = $api.'adviser/instituteSecretary.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    $institute_secretary_decoded = json_decode($resp);
    curl_close($ch);

    foreach ($institute_secretary_decoded as $obj1) {
        $institute_secretary = $obj1->name;
    }

    #fetch institute secretary end

    #fetch student affair start

    $ch = curl_init();
    $url = $api.'adviser/studentAffair.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    $student_affair_coordinator_decoded = json_decode($resp);
    curl_close($ch);

    foreach ($student_affair_coordinator_decoded as $obj1) {
        $student_affair_coordinator = $obj1->name;
    }

    #fetch student affair end

    #fetch it head start

    $ch = curl_init();
    $url = $api.'adviser/itHead.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    $information_technology_department_head_decoded = json_decode($resp);
    curl_close($ch);

    foreach ($information_technology_department_head_decoded as $obj1) {
        $information_technology_department_head = $obj1->name;
    }

    #fetch it head end

    #fetch cs Head start

    $ch = curl_init();
    $url = $api.'adviser/csHead.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    $computer_science_department_head_decoded = json_decode($resp);
    curl_close($ch);

    foreach ($computer_science_department_head_decoded as $obj1) {
        $computer_science_department_head = $obj1->name;
    }

    #fetch cs Head end


    #fetch chairperson start

    $ch = curl_init();
    $url = $api.'adviser/chairperson.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    $chairperson_decoded = json_decode($resp);
    curl_close($ch);

    foreach ($chairperson_decoded as $obj1) {
        $chairperson = $obj1->name;
    }

    #fetch chairperson end


    $html = "
        <div class='container'> 
            <div class='top1'>
                <img src='pdfassets/wmsulogo.png' alt='wmsulogo'>
            </div>

            <div class='top2'>
                <span>Republic of the philippines</span><br/>
                <span>Western Mindanao State University</span><br/>
                <span class='institute'>INSTITUTE OF COMPUTER STUDIES</span><br/>
                <span>Zamboanga City</span>
            </div>

            <div class='top1'>
                <img src='pdfassets/icslogo.png' alt='icslogo'>
            </div>
        </div>

        <div class ='container2'>
            <br/>
            <br/>
            <br/>
            <span>FOR: </span> 
            <span class='institute'>{$ics_director}</span> <br/>
            <span class='director'>&emsp;&emsp;&emsp;Director, ICS</span>
        </div>

        <div class ='container2'>
            <span class='director'>&emsp;&emsp;&emsp;I have the honor to apply for the inclusion in the Director's List for the {$student_semester} semester, 
            school year {$school_year}, Based on my academic ratings for the given period, to wit:</span>

                <div class='table-container'>
                    <table>
                        <th>
                            <tr>
                                <td>Subject name</td>
                                <td>units</td>
                                <td>Grade</td>
                            </tr>
                        </th>";


                foreach ($decoded_grades as $obj) {
                    $html .= "
                        <tb>
                            <tr>
                                <td>{$obj->subject_name}</td>
                                <td>{$obj->subject_code}</td>
                                <td>{$obj->grade}</td>
                            </tr>
                        </tb>
                    ";
                }
                

                $html .="<tb>
                            <tr>
                                <td style='font-size: 10px; font-weight: bold;'>Total units and GPA:</td>
                                <td>{$subject_units}</td>
                                <td>{$gpa}</td>
                            </tr>
                        </tb>
                    </table>
                <div>

                <div class='container3'>

                    <div class='container4'>
                        <br/>
                        <br/>
                        <span><u>{$adviser_name}</u></span> 
                        <br/>
                        <span><strong>Adviser</strong></span>
                    </div>

                    <div class='container5'>
                        <span><strong>Student name:</strong> {$student_name}</span><br/>
                        <span><strong>Student id:</strong> {$student_id}</span><br/>
                        <span><strong>Section:</strong> {$student_section}</span><br/>
                        <span><strong>Year:</strong> {$student_year}</span><br/>
                        <span><strong>Course:</strong> {$student_course}</span><br/>
                    </div>
                </div>

                <hr>

                <br/>
                    <span><strong>{$ics_director}</strong></span> <br/>
                    <span>Director, ICS</span> <br>
                <br/>

                <div>
                    <span>Sir<span><br/>
                    
                    <span>
                        &emsp;&emsp;&emsp;Upon verification by the committee, Mr./Mrs. <u>{$student_name}</u> has been found to possess the
                        qualifications, and none of the disqualifications, for the inclusion in the Institute's Dirctor's List for the perdiod indicated.
                    </span>
                    <br/>
                    <br/>
                    <span>
                        &emsp;&emsp;&emsp;Therefore, we hereby recommend for approval of his/her application as a Director's Lister.
                    </span>
                </div>

                <br/>
                <br/>

                <div class='container-committee'>
                    <div class='committee-left'>
                        <span><strong>{$ggc_coordinator}</strong></span><br/>
                        <span>Member / Gender and Guidance & Counseling Coordinator</span><br/>
                        <br/>
                        <span><strong>{$student_affair_coordinator}</strong></span><br/>
                        <span>Member / Student Affair Coordinator</span><br/>
                        <br/>
                        <span><strong>{$computer_science_department_head}</strong></span><br/>
                        <span>Computer Science Department Head</span><br/>
                    </div>

                    <div class='committee-right'>
                        <span><strong>{$institute_secretary}</strong></span><br/>
                        <span>Member / Institute Secretary</span><br/>
                        <br/>
                        <span><strong>{$information_technology_department_head}</strong></span><br/>
                        <span>Member / Information Technology Department Head</span><br/>
                        <br/>
                        <span><strong>{$chairperson}</strong></span><br/>
                        <span>Chairperson</span><br/>
                    </div>
                <div>

                <hr>

                <div class='footer'>
                    <div>
                        <span><strong>Date: </strong><u>{$date_issued}<u></span>
                    </div>
                    <br/>
                    <span>
                        &emsp;&emsp;&emsp;Upon the recommendation of the Committee, Mr./Ms.<u>{$student_name}</u> is hereby admitted for the inclusion in the Director's List
                        for the academic period herein stated.  
                    </span> <br/>
                    <br/>
                    <div class='footer-admin'>
                        <span><strong>{$ics_director}</strong></span><br/>
                        <span>Director</span>
                    </div>
                </div>
        </div>
    ";

include('mpdf/vendor/autoload.php');
$mpdf = new \Mpdf\Mpdf();
$stylesheet = file_get_contents('pdfassets/style.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html,2);
$mpdf->output();

?>