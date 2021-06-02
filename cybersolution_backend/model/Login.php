<?php
    class Login {
        private $conn;
        private $facultyMembers= "faculty_members";
        private $students = "student";

        public $faculty_id;
        public $name;
        public $email;
        public $password;
        public $admin;
        public $adviser;
        public $ics_director;
        public $institute_secretary;
        public $student_affair_coordinator;
        public $computer_science_department_head;
        public $information_technology_department_head;
        public $gender_coordinator;
        public $contact_number;

        public $student_id;
        public $firstname;
        public $middlename;
        public $lastname;
        public $college;
        public $course;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function loginFaculty () {
            try {
                $query = 'SELECT 
                    faculty_id,
                    name,
                    email,
                    password,
                    adviser,
                    admin,
                    committee_role
                FROM ' .$this->facultyMembers. '
                WHERE faculty_id = :faculty_id';

                $stmt = $this->conn->prepare($query);
                $this->faculty_id = htmlspecialchars(strip_tags($this->faculty_id));

                $stmt->bindParam(':faculty_id', $this->faculty_id);
                $stmt->execute();
                return $stmt;
            }
            catch (PDOException $err) {
                return false;
            }
        }

        public function loginStudent () {
            try {
                $query = 'SELECT 
                    student_id,
                    password,
                    firstname,
                    middlename,
                    lastname,
                    email,
                    contact_number,
                    college,
                    course
                FROM ' .$this->students. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);
                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id', $this->student_id);
                $stmt->execute();
                return $stmt;
            }
            catch (PDOException $err) {
                echo $err;
                return false;
            }
        }
    }
?>