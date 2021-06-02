<?php 

    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $ch = curl_init();
    $url = $api.'student/fetchAllAchievers.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($resp);
    } curl_close($ch);


    $ch = curl_init();
    $url = $api.'student/fetchDefaults.php';

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
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="assets/style.css">
    <title>ICS-DLOA | Achievers</title>
</head>
<body>

    <div class="achiever_display">

        <div class="table-container">
            <h1>Current Achievers</h1>
            <span>Congratulations! The Institute of Computer Science is very proud of you</span>
            <h5>S.Y <?php echo $school_year?></h5>
            <a href="index.php">Back</a>
            
            <table class="table_achievers">
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Course</th>
                    <th>Section</th>
                    <th>Year</th>
                    <th>GPA</th>
                </tr>

                <?php if ($decoded != 'no students found') { ?>
                    <?php $counter=0; foreach ($decoded as $obj): ?>
                        <tr>
                            <td><?php echo $counter+=1; ?></td>
                            <td><?php echo $obj->student_id ?></td>
                            <td><?php echo $obj->course; ?></td>
                            <td><?php echo $obj->section; ?></td>
                            <td><?php echo $obj->year; ?></td>
                            <td><?php echo $obj->gpa; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php } ?>

            </table>
        </div>

    </div>
    
</body>
</html>