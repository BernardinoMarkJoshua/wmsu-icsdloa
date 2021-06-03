<?php
    class Student {
        private $conn;
        private $gate = "gate";
        private $current_achievers = "current_achievers";
        private $faculty_members = "faculty_members";
        private $grades = "grades";
        private $subjects = "subjects";
        private $waiting_form = "waiting_form";
        private $approved_forms = "approved_forms";
        private $student = 'student';
        private $subject_curriculum = 'subject_curriculum';
        private $defaults = 'defaults';
        private $archiveAchievers = 'archive_achievers';
        private $archive_grades = 'archive_grades'; 
        private $misto = "misto";

        public $student_id;
        public $password;
        public $firstname;
        public $middlename;
        public $lastname;
        public $email;
        public $contact_number;
        public $college;
        public $course;
        public $adviser;
        public $name;
        public $section;

        public $year;
        public $semester;

        public $subject_code;
        public $course_name;
        public $subject_units;
        public $subject_name;
        public $name_curriculum;
        public $curriculum_name;
        public $grade;
        public $gpa;
        public $school_year;
        public $curriculum;

        public function __construct ($db) {
            $this->conn = $db;
        }

        public function registerStudent() {
            try {
                $query = 'INSERT INTO ' .$this->gate. '
                    SET
                        student_id = :student_id,
                        password = :password,
                        firstname = :firstname,
                        middlename = :middlename,
                        lastname = :lastname,
                        email = :email,
                        contact_number = :contact_number,
                        college = :college,
                        course = :course,
                        adviser = :adviser';
                    
                    $stmt = $this->conn->prepare($query);

                    $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                    $this->password = htmlspecialchars(strip_tags($this->password));
                    $this->firstname = htmlspecialchars(strip_tags($this->firstname));
                    $this->middlename = htmlspecialchars(strip_tags($this->middlename));
                    $this->lastname = htmlspecialchars(strip_tags($this->lastname));
                    $this->email = htmlspecialchars(strip_tags($this->email));
                    $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));
                    $this->college = htmlspecialchars(strip_tags($this->college));
                    $this->course = htmlspecialchars(strip_tags($this->course));
                    $this->adviser = htmlspecialchars(strip_tags($this->adviser));


                    $stmt->bindParam(':student_id', $this->student_id);
                    $stmt->bindParam(':password', $this->password);
                    $stmt->bindParam(':firstname', $this->firstname);
                    $stmt->bindParam(':middlename', $this->middlename);
                    $stmt->bindParam(':lastname', $this->lastname);
                    $stmt->bindParam(':email', $this->email);
                    $stmt->bindParam(':contact_number', $this->contact_number);
                    $stmt->bindParam(':college', $this->college);
                    $stmt->bindParam(':course', $this->course);
                    $stmt->bindParam(':adviser', $this->adviser);


                    $stmt->execute();
                    return $stmt;
                    

            } catch (PDOException $err) {
                return false;
            }
        }

        public function fetchAdvisers() {
            try {
                $query = 'SELECT
                    name
                FROM ' .$this->faculty_members. '
                WHERE adviser = "yes"';
    
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;
            } 

            catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function fetchAchievers() {
            $query = 'SELECT * FROM ' .$this->current_achievers. '
            ORDER BY gpa ASC
            LIMIT 10';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;            
        }

        public function fetchAllAchievers() {
            $query = 'SELECT * FROM ' .$this->current_achievers. '
            ORDER BY gpa ASC';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;            
        }

        public function sendAppform() {
            try {
                $query = 'INSERT INTO  ' .$this->waiting_form. ' (student_id, firstname, middlename, lastname, section, course, year, school_year, semester, date, gpa, adviser)
                    SELECT 
                        s.student_id,
                        s.firstname,
                        s.middlename,
                        s.lastname,
                        :section,
                        :course,
                        :year,
                        d.school_year,
                        d.semester,
                        CURDATE(),
                        :gpa,
                        :adviser
                    FROM student s , defaults d
                    WHERE s.student_id = :student_id';

                $stmt = $this->conn->prepare($query);
                
                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $this->section = htmlspecialchars(strip_tags($this->section));
                $this->course = htmlspecialchars(strip_tags($this->course));
                $this->year = htmlspecialchars(strip_tags($this->year));
                $this->gpa = htmlspecialchars(strip_tags($this->gpa));
                $this->adviser = htmlspecialchars(strip_tags($this->adviser));

                $stmt->bindParam(':student_id', $this->student_id);
                $stmt->bindParam(':section', $this->section);
                $stmt->bindParam(':course', $this->course);
                $stmt->bindParam(':year', $this->year);
                $stmt->bindParam(':gpa', $this->gpa);
                $stmt->bindParam(':adviser', $this->adviser);
                
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function sendGrades() {
            try {
                $query = 'INSERT INTO ' .$this->grades. ' ( student_id, subject_code, subject_unit, subject_name, grade, school_year)
                    SELECT 
                        s.student_id,
                        sub.subject_code,
                        sub.subject_units,
                        sub.subject_name,
                        :grade,
                        d.school_year
                    FROM student s, subjects sub, defaults d
                    WHERE s.student_id = :student_id
                    AND sub.subject_code = :subject_code
                    AND sub.subject_curriculum = s.curriculum
                    AND sub.subject_year = :year';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $this->subject_code = htmlspecialchars(strip_tags($this->subject_code));
                $this->grade = htmlspecialchars(strip_tags($this->grade));
                $this->year = htmlspecialchars(strip_tags($this->year));

                $stmt->bindParam(':student_id', $this->student_id);
                $stmt->bindParam(':subject_code', $this->subject_code);
                $stmt->bindParam(':grade', $this->grade);
                $stmt->bindParam(':year', $this->year);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function fetchSubjects() {
            try {
                $query = 'SELECT 
                    subject_code,
                    subject_name,
                    subject_units
                FROM ' .$this->subjects. ', defaults d
                WHERE subject_year = :year
                AND subject_semester = d.semester
                AND subject_curriculum = :curriculum_name';

                $stmt = $this->conn->prepare($query);
                
                $this->year = htmlspecialchars(strip_tags($this->year));
                $this->curriculum_name = htmlspecialchars(strip_tags($this->curriculum_name));
                
                $stmt->bindParam(':year', $this->year);  
                $stmt->bindParam(':curriculum_name', $this->curriculum_name);  
                
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function changePassword() {
            try {
                $query = 'UPDATE  ' .$this->student. '
                    SET 
                        password = :password
                    WHERE student_id = :student_id';

                    $stmt = $this->conn->prepare($query);

                    $this->password = htmlspecialchars(strip_tags($this->password));
                    $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                
                    $stmt->bindParam(':password', $this->password);  
                    $stmt->bindParam(':student_id', $this->student_id);
                    
                    $stmt->execute();
                    return $stmt;

            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function checkWaitingForms() {
            try {
                $query = 'SELECT * FROM ' .$this->waiting_form. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function checkApprovedForms() {
            try {
                $query = 'SELECT * FROM ' .$this->approved_forms. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function checkCurrentAchievers() {
            try {
                $query = 'SELECT * FROM ' .$this->current_achievers. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function fetchCurriculum() {
            try {
                $query = 'SELECT * FROM ' .$this->subject_curriculum;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function fetchStudentInfo() {
            try {
                $query = 'SELECT * FROM ' .$this->student. '
                    WHERE student_id = :student_id';
                
                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function fetchDefaults() {
            try {
                $query = 'SELECT * FROM ' .$this->defaults;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function viewArchive() {
            try {
                $query = 'SELECT * FROM ' .$this->archiveAchievers. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $stmt->bindParam(':student_id', $this->student_id);
                
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function fetchStudentHistoryGrades() {
            try {
                $query = 'SELECT * FROM ' .$this->archive_grades. '
                WHERE student_id = :student_id
                AND year = :year
                AND semester = :semester
                AND school_year = :school_year';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $this->year = htmlspecialchars(strip_tags($this->year));
                $this->semester = htmlspecialchars(strip_tags($this->semester));
                $this->school_year = htmlspecialchars(strip_tags($this->school_year));

                $stmt->bindParam(':student_id', $this->student_id);
                $stmt->bindParam(':year', $this->year);
                $stmt->bindParam(':semester', $this->semester);
                $stmt->bindParam(':school_year', $this->school_year);

                
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function verifyMisto() {
            $query = 'SELECT * FROM
            ' .$this->misto . '
            WHERE student_id = :student_id';

            $stmt = $this->conn->prepare($query);
            $this->student_id = htmlspecialchars(strip_tags($this->student_id));
            $stmt->bindParam(':student_id' ,$this->student_id);

            $stmt->execute();
            return $stmt;
        }

        public function verifyStudent() {
            $query = 'SELECT * FROM
            ' .$this->student. '
            WHERE student_id = :student_id';

            $stmt = $this->conn->prepare($query);
            $this->student_id = htmlspecialchars(strip_tags($this->student_id));
            $stmt->bindParam(':student_id' ,$this->student_id);

            $stmt->execute();
            return $stmt;
        }

        public function getDirectorState() {
            $query = 'SELECT * FROM
            ' .$this->approved_forms . '
            WHERE student_id = :student_id
            AND chairperson_approval = "yes"';

            $stmt = $this->conn->prepare($query);
            $this->student_id = htmlspecialchars(strip_tags($this->student_id));
            $stmt->bindParam(':student_id' ,$this->student_id);

            $stmt->execute();
            return $stmt;
        }
        
        public function checkGate() {
            try {
                $query = 'SELECT * FROM ' .$this->gate. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                $stmt->bindParam(':student_id', $this->student_id);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }
    }

?>