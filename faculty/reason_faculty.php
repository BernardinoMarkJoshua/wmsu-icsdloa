<?php 
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['FACULTY_REMOVE_STATUS'] != 'yes') {
        echo '<script>window.location.href="faculty.php"</script>';
    }


    $email = $_SESSION['FACULTY_EMAIL'];
    $_SESSION['FACULTY_REMOVE_STATUS'] = 'no';
    $student_id = $_SESSION['FACULTY_ID_FACULTY'];
    $subject= 'Removed from faculty';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ICS-DLOA | Reason</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
    <div class="container" style="margin-top:100px;">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3" align="center">
                <h1>Reason for removal</h1>
                <input type="hidden" id="name" value="ICS-DLOA" class="form-control">
                <input type="hidden" id="email" value="<?php echo $email?>" class="form-control">
                <input type="hidden" id="subject" value="<?php echo $subject?>" class="form-control">
                <input type="hidden" id="student_id" value="<?php echo $student_id?>" class="form-control">
                <textarea class="form-control" id="body" placeholder="Email Body"></textarea>
                
                <input type="button" class="btn btn-secondary" value="Cancel" onclick="history.back()">
                
                <input type="button" data-toggle="modal" data-target="#myModal" id="send-btn" class="btn btn-primary" value="Send and Remove">
                
            </div>
              
        </div>
    </div>

    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Are you sure?</h1>
                </div>

                <div class="modal-body">
                    This faculty member will be removed
                </div>

                <div class="modal-footer">
                    <input type="button" value="Cancel" data-dismiss="modal" class="btn btn-secondary">
                    <input type="button" onclick="sendEmail()" value="Confirm" data-dismiss="modal" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    
        function sendEmail() {
            var name = $("#name");
            var email = $("#email");
            var subject = $("#subject");
            var student_id = $("#student_id");
            var body = $("#body");
            

            if (isNotEmpty(name) && isNotEmpty(email) && isNotEmpty(subject) && isNotEmpty(student_id) && isNotEmpty(body)) {
                $.ajax({
                   url: 'sendEmail_Admin_Faculty.php',
                   method: 'POST',
                   dataType: 'json',
                   data: {
                       name: name.val(),
                       email: email.val(),
                       subject: subject.val(),
                       student_id: student_id.val(),
                       body: body.val()
                   }, success: function (response) {
                        if (response.status == "success")
                            window.location.href = "faculty.php";
                        else {
                            alert('Please Try Again!');
                            console.log(response);
                        }
                   }
                });
            }
        }

        function isNotEmpty(caller) {
            if (caller.val() == "") {
                caller.css('border', '1px solid red');
                return false;
            } else
                caller.css('border', '');

            return true;
        }
    </script>
</body>
</html>