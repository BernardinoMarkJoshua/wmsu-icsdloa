<?php
    class Adviser {
        private $conn;
        private $studentGate = 'gate';
        private $students = 'student';
        private $waitingForm = 'waiting_form';
        private $forms = 'approved_forms'; 
        private $grades = 'grades';
        private $adviserInfo = 'adviser_info';
        private $faculty_members = 'faculty_members';

        public $student_id;
        public $student_id_form;
        public $password;
        public $firstname;
        public $middlename;
        public $email;
        public $contact_number;
        public $course;
        public $adviser_name;
        public $curriculum;

        public $year;
        public $semester;
        public $section;
        public $date;
        public $gpa;

        public $subject_code;
        public $subject_name;
        public $grade;
        public $search;
        
        public $ics_director_approval;
        public $gcc_coordinator_approval;
        public $institute_secretary_approval;
        public $student_affair_coordinator_approval;
        public $information_technology_department_head_approval;
        public $computer_science_department_head_approval;

        public function __construct($db) {
            $this->conn = $db;
        }

        //View students from gate
        public function viewGate() {
            $query = 'SELECT
                student_id,
                firstname,
                middlename,
                lastname,
                contact_number,
                email,
                course
            FROM
            ' .$this->studentGate . '
            WHERE adviser = :adviser_name';

            $stmt = $this->conn->prepare($query);
            $this->adviser_name = htmlspecialchars(strip_tags($this->adviser_name));
            $stmt->bindParam(':adviser_name' ,$this->adviser_name);

            $stmt->execute();
            return $stmt;
        }

        //Accept students from gate to student database
        public function acceptGate() {
            try {
                $query = 'INSERT INTO ' . $this->students . '(student_id, password, firstname, middlename, lastname, email, contact_number, college,course)
                SELECT
                    student_id,
                    password,
                    firstname,
                    middlename,
                    lastname,
                    email,
                    contact_number,
                    college,
                    course
                FROM
                ' .$this->studentGate. '
                WHERE student_id = :student_id;
                DELETE FROM ' .$this->studentGate. '
                WHERE student_id = :student_id;
                UPDATE '  . $this->students . '
                    SET
                        curriculum = :curriculum
                WHERE student_id = :student_id';

                

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $this->curriculum = htmlspecialchars(strip_tags($this->curriculum));

                $stmt->bindParam(':curriculum' ,$this->curriculum);
                $stmt->bindParam(':student_id' ,$this->student_id);

                $stmt->execute();

                return true;
            }
            catch (PDOException $err) {
                return false;
            }
        }

        //Decline students from gate
        public function declineGate() {
            try {
                $query = 'DELETE FROM '.$this->studentGate. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                return false;
            }    
        }

        //View students from student database
        public function viewStudents() {
            $query = 'SELECT
                student_id,
                firstname,
                middlename,
                lastname,
                contact_number,
                email,
                college,
                course
            FROM 
            ' . $this->students . '
            ORDER BY lastname';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        //View waiting application form
        public function viewWaitingForm() {
            try {
                $query = 'SELECT 
                    student_id,
                    firstname,
                    middlename,
                    lastname,
                    section,
                    course,
                    year,
                    semester,
                    date,
                    gpa 
                FROM ' .$this->waitingForm. '
                WHERE adviser = :adviser_name';

                $stmt = $this->conn->prepare($query);

                $this->adviser_name = htmlspecialchars(strip_tags($this->adviser_name));
                
                $stmt->bindParam(':adviser_name', $this->adviser_name);

                $stmt->execute();
                return $stmt;
            }

            catch (PDOException $err){
                return false;
            }
        }

        //Accept application form from waitingforms database
        public function acceptForm() {
            try {
                $query = 'INSERT INTO ' . $this->forms . ' (student_id, firstname, middlename, lastname, section, course, year, school_year, semester, date, gpa, adviser)
                SELECT
                    student_id,
                    firstname,
                    middlename,
                    lastname,
                    section,
                    course,
                    year,
                    school_year,
                    semester,
                    date,
                    gpa,
                    adviser
                FROM ' .$this->waitingForm. '
                WHERE student_id = :student_id; 
                DELETE FROM ' . $this->waitingForm . '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id',$this->student_id);

                $stmt->execute();
                return $stmt;
            } 
            catch (PDOException $err) {
                return false;
            }
        }

        public function acceptFormDefaults() {
            try {
                $query = 'UPDATE ' .$this->forms. '
                    SET
                        ics_director_approval = "no", 
                        chairperson_approval = "no", 
                        gender_coordinator_approval = "no", 
                        institute_secretary_approval = "no",
                        student_affair_coordinator_approval = "no", 
                        information_technology_department_head_approval = "no", 
                        computer_science_department_head_approval = "no"
                    WHERE student_id = :student_id';

                    $stmt = $this->conn->prepare($query);
                        
                    $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                    $stmt->bindParam(':student_id',$this->student_id);
                    
                    $stmt->execute();
                    return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        //Decline application form from waitingforms database
        public function declineForm() {
            try {
                $query = 'DELETE FROM '.$this->waitingForm. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;
            }

            catch (PDOException $err) {
                return false;
            }
        }

        //View a specific application form from the waitingforms database
        public function viewOneForm() {
            try {
                $query = 'SELECT 
                    student_id,
                    firstname,
                    middlename,
                    lastname,
                    section,
                    course,
                    year,
                    school_year,
                    semester,
                    date,
                    gpa 
                FROM ' .$this->waitingForm. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
 
                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;
            }

            catch (PDOException $err) {
                return false;
            }
        }

        //View Grades from grades database
        public function viewGrades() {
            try {
                $query = 'SELECT
                    subject_code,
                    subject_name,
                    subject_unit,
                    grade
                FROM ' .$this->grades. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        //Declining a student will fire this function and delete the student's grade
        public function declineStudentGrade() {
            try {
                $query = 'DELETE FROM '.$this->grades. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;

            } catch (PDOException $err) {
                return false;
            }
        }

        public function viewApprovedForms() {
            try {
                $query = 'SELECT * FROM ' .$this->forms. '
                    WHERE adviser = :adviser_name';

                $stmt = $this->conn->prepare($query);
                $this->adviser_name = htmlspecialchars(strip_tags($this->adviser_name));
                $stmt->bindParam(':adviser_name', $this->adviser_name);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                return false;
            }
        }

        public function cancelForm() {
            try {
                $query = 'INSERT INTO ' . $this->waitingForm . ' (student_id, firstname, middlename, lastname, section, course, year, school_year, semester, date, gpa, adviser)
                SELECT
                    student_id,
                    firstname,
                    middlename,
                    lastname,
                    section,
                    course,
                    year,
                    school_year,
                    semester,
                    date,
                    gpa,
                    adviser
                FROM ' .$this->forms. '
                WHERE student_id = :student_id; 
                DELETE FROM ' . $this->forms . '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id',$this->student_id);

                $stmt->execute();
                return $stmt;
            } 
            catch (PDOException $err) {
                return false;
            }
        }

        public function fetchOneGate() {
            try {
                $query = 'SELECT *
                FROM ' .$this->studentGate. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }


        public function fetchOneStudent() {
            try {
                $query = 'SELECT *
                FROM ' .$this->students. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        public function icsDirector() {
            try {
                $query = 'SELECT *
                FROM ' .$this->faculty_members. '
                WHERE committee_role = "ICS Director"';

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        public function genderGuidance() {
            try {
                $query = 'SELECT *
                FROM ' .$this->faculty_members. '
                WHERE committee_role = "Gender Guidance"';

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        public function instituteSecretary() {
            try {
                $query = 'SELECT *
                FROM ' .$this->faculty_members. '
                WHERE committee_role = "Institute Secretary"';

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        public function studentAffair() {
            try {
                $query = 'SELECT *
                FROM ' .$this->faculty_members. '
                WHERE committee_role = "Student Affair Coordinator"';

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        public function itHead() {
            try {
                $query = 'SELECT *
                FROM ' .$this->faculty_members. '
                WHERE committee_role = "IT Head"';

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        public function csHead() {
            try {
                $query = 'SELECT *
                FROM ' .$this->faculty_members. '
                WHERE committee_role = "CS Head"';

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        public function chairperson() {
            try {
                $query = 'SELECT *
                FROM ' .$this->faculty_members. '
                WHERE committee_role = "Chairperson"';

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }

        public function search() {
            try {
                $query = 'SELECT *
                FROM ' .$this->students. '
                WHERE firstname LIKE :search
                OR middlename LIKE :search
                OR lastname LIKE :search
                OR student_id LIKE :search';

                $stmt = $this->conn->prepare($query);

                $this->search = htmlspecialchars(strip_tags($this->search));

                $stmt->bindParam(':search',$this->search);

                $stmt->execute();
                return $stmt;  
            }
            
            catch (PDOException $err) {
                return false;
            }
        }
    }
?>