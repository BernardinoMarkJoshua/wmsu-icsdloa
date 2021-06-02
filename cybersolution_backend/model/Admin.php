<?php
    class Admin {
        private $conn;
        private $currentAchievers = "current_achievers";
        private $approvedForms = "approved_forms";
        private $grades = "grades";
        private $waitingForm = 'waiting_form';
        private $archiveAchievers = "archive_achievers";
        private $archiveGrades = "archive_grades";
        private $students = "student";
        private $faculty = "faculty_members";
        private $adviserInfo = "adviser_info";
        private $subjects = "subjects";
        private $approved_forms = "approved_forms";
        private $subject_curriculum = "subject_curriculum";
        private $defaults = "defaults";

        public $student_id;
        public $student_id_holder;
        public $faculty_id_change;
        public $firstname;
        public $middlename;
        public $lastname;
        public $course;
        public $section;
        public $year;
        public $semester;
        public $date;
        public $gpa;

        public $adviser;
        public $admin;
        public $ics_director;
        public $chairperson;
        public $gender_coordinator;
        public $institute_secretary;
        public $student_affair_coordinator;
        public $information_technology_department_head;
        public $computer_science_department_head;

        public $course_name;
        public $subject_year;
        public $subject_code;
        public $subject_semester;
        public $subject_name;
        public $school_year;
        public $subject_units;
        public $finalizing;
        public $application;

        public $subject_code_holder;
        public $subject_year_holder;
        public $subject_semester_holder;
        public $course_name_holder;
        public $archive_date;
        public $archive_date2;
        public $curriculum_name;
        public $committee_role;
        public $contact_number;
        public $search;
        public $course_name_curriculum;



        public function __construct($db) {
            $this->conn = $db;
        }

        //view approved application forms
        public function viewForms() {
            $query = 'SELECT 
                student_id,
                firstname,
                middlename,
                lastname,
                course,
                section,
                year,
                date,
                gpa
            FROM ' .$this->approvedForms. '
            ORDER BY gpa';
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        //reject application forms 
        public function rejectForms() {
            try {
                $query = 'DELETE FROM ' .$this->approvedForms. '
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

        //accept form to achievers
        public function acceptForm () {
            try {
                $query = 'INSERT INTO ' .$this->currentAchievers. ' (student_id, firstname, middlename, lastname, course, section, year, semester, date, gpa)
                SELECT
                    student_id,
                    firstname,
                    middlename,
                    lastname,
                    course,
                    section,
                    year,
                    semester,
                    date,
                    gpa
                FROM ' .$this->approvedForms. '
                WHERE student_id = :student_id;
                DELETE FROM ' .$this->approvedForms. '
                WHERE student_id = :student_id';

                $stmt = $this->conn->prepare($query);

                $this->student_id = htmlspecialchars(strip_tags($this->student_id));

                $stmt->bindParam(':student_id',$this->student_id);

                $stmt->execute();
                return $stmt;
            }

            catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        //view achievers 
        public function viewAchiever() {
            $query ='SELECT 
                student_id,
                firstname,
                middlename,
                lastname,
                course,
                section,
                year,
                semester,
                date,
                gpa
            FROM ' .$this->currentAchievers;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        //remove achievers
        public function removeAchiever() {
            try {
                $query ='DELETE FROM ' .$this->currentAchievers. '
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

        //change something on achiever table
        public function editAchiever() {
            try {
                $query = 'UPDATE ' .$this->currentAchievers. '
                    SET 
                        student_id = :student_id,
                        firstname = :firstname,
                        middlename = :middlename,
                        lastname = :lastname,
                        course = :course,
                        section = :section,
                        year = :year
                    WHERE student_id = :student_id_holder';

                    $stmt = $this->conn->prepare($query);

                    $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                    $this->student_id_holder = htmlspecialchars(strip_tags($this->student_id_holder));
                    $this->firstname = htmlspecialchars(strip_tags($this->firstname));
                    $this->middlename = htmlspecialchars(strip_tags($this->middlename));
                    $this->lastname = htmlspecialchars(strip_tags($this->lastname));
                    $this->course = htmlspecialchars(strip_tags($this->course));
                    $this->section = htmlspecialchars(strip_tags($this->section));
                    $this->year = htmlspecialchars(strip_tags($this->year));

                    $stmt->bindParam(':student_id', $this->student_id);
                    $stmt->bindParam(':student_id_holder', $this->student_id_holder);
                    $stmt->bindParam(':firstname', $this->firstname);
                    $stmt->bindParam(':middlename', $this->middlename);
                    $stmt->bindParam(':lastname', $this->lastname);
                    $stmt->bindParam(':course', $this->course);
                    $stmt->bindParam(':section', $this->section);
                    $stmt->bindParam(':year', $this->year);

                    $stmt->execute();
                    return $stmt;
            }
            catch (PDOException $err) {
                return false;
            }
        }

        //view archive
        public function viewArchive() {
            try {
                $query = 'SELECT * FROM ' .$this->archiveAchievers. '
                WHERE school_year = :school_year
                AND semester = :semester';

                $stmt = $this->conn->prepare($query);

                $this->school_year = htmlspecialchars(strip_tags($this->school_year));
                $this->semester = htmlspecialchars(strip_tags($this->semester));

                $stmt->bindParam(':school_year', $this->school_year);
                $stmt->bindParam(':semester', $this->semester);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        //view grade archive
        public function viewArchiveGrade() {
            $query = 'SELECT 
                student_id,
                subject_code,
                subject_units,
                subject_name,
                grade
            FROM ' .$this->archiveGrades. '
            WHERE student_id = :student_id
            AND year = :year';

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
            return $stmt;
        }

        //add to archive 
        public function archive() {
            try {
                $query = 'INSERT INTO ' .$this->archiveGrades. ' (student_id, subject_code, subject_units, subject_name, grade, school_year, semester, year)
                    SELECT 
                        g.student_id,
                        g.subject_code,
                        g.subject_unit,
                        g.subject_name,
                        g.grade,
                        g.school_year,
                        c. semester,
                        c.year
                    FROM ' .$this->grades. ' g, current_achievers c
                    WHERE c.student_id = g.student_id;
                    DELETE FROM ' .$this->grades. ';
                    INSERT INTO ' .$this->archiveAchievers. '(student_id, firstname, middlename, lastname, section, course, year, semester, date, school_year, gpa)
                    SELECT 
                        c.student_id,
                        c.firstname,
                        c.middlename,
                        c.lastname,
                        c.section,
                        c.course,
                        c.year,
                        c.semester,
                        c.date,
                        d.school_year,
                        c.gpa
                    FROM ' .$this->currentAchievers. ' c, defaults d; 
                    DELETE FROM ' .$this->currentAchievers. ';
                    DELETE FROM ' .$this->waitingForm. ';
                    DELETE FROM ' .$this->approvedForms;
                     

                $stmt = $this->conn->prepare($query);

                $stmt->execute();
                return $stmt;
            }
            catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        //view students
        public function viewStudents() {
            $query = 'SELECT
                student_id,
                firstname,
                middlename,
                lastname,
                contact_number,
                college,
                email,
                course
            FROM 
            ' . $this->students . '
            ORDER BY lastname';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        //delete student
        public function deleteStudent() {
            $query ='DELETE FROM ' .$this->students. '
            WHERE student_id = :student_id';
            
        
            $stmt = $this->conn->prepare($query);

            $this->student_id = htmlspecialchars(strip_tags($this->student_id));

            $stmt->bindParam(':student_id', $this->student_id);

            $stmt->execute();
            return $stmt;
        }

        //view faculty
        public function viewFaculty() {
            $query = 'SELECT 
                faculty_id,
                email,
                name,
                password,
                adviser,
                admin,
                committee_role,
                contact_number
            FROM ' .$this->faculty;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        //view single faculty
        public function viewSingleFaculty() {
            $query = 'SELECT 
                role
            FROM ' .$this->faculty. '
            WHERE faculty_id = :faculty_id';

            $stmt = $this->conn->prepare($query);

            $this->faculty_id = htmlspecialchars(strip_tags($this->faculty_id));
            $stmt->bindParam(':faculty_id', $this->faculty_id);

            $stmt->execute();
            return $stmt;
        }

        //edit faculty members
        public function editFaculty() {
            try {
                $query = 'UPDATE ' .$this->faculty. '
                    SET faculty_id = :faculty_id,
                        email = :email,
                        name = :name,
                        password = :password,
                        adviser = :adviser,
                        admin = :admin,
                        committee_role = :committee_role,
                        contact_number = :contact_number
                    WHERE faculty_id = :faculty_id_change';

                $stmt = $this->conn->prepare($query);

                $this->faculty_id_change = htmlspecialchars(strip_tags($this->faculty_id_change));
                $this->faculty_id = htmlspecialchars(strip_tags($this->faculty_id));
                $this->email = htmlspecialchars(strip_tags($this->email));
                $this->name = htmlspecialchars(strip_tags($this->name));
                $this->password = htmlspecialchars(strip_tags($this->password));
                $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));
                $this->adviser = htmlspecialchars(strip_tags($this->adviser));
                $this->admin = htmlspecialchars(strip_tags($this->admin));
                $this->committee_role = htmlspecialchars(strip_tags($this->committee_role));

                $stmt->bindParam(':faculty_id_change', $this->faculty_id_change);
                $stmt->bindParam(':faculty_id', $this->faculty_id);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':name', $this->name);
                $stmt->bindParam(':password', $this->password);
                $stmt->bindParam(':contact_number', $this->contact_number);
                $stmt->bindParam(':adviser', $this->adviser);
                $stmt->bindParam(':admin', $this->admin);
                $stmt->bindParam(':committee_role', $this->committee_role);

                $stmt->execute();
                return $stmt;
            }

            catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        //register faculty members
        public function registerFaculty() {
            try {
                $query = 'INSERT INTO ' .$this->faculty. '
                    SET 
                        faculty_id = :faculty_id,
                        email = :email,
                        name = :name,
                        password = :password,
                        adviser = :adviser,
                        admin = :admin,
                        committee_role = :committee_role,
                        contact_number = :contact_number';

                $stmt = $this->conn->prepare($query);

                $this->faculty_id = htmlspecialchars(strip_tags($this->faculty_id));
                $this->email = htmlspecialchars(strip_tags($this->email));
                $this->name = htmlspecialchars(strip_tags($this->name));
                $this->password = htmlspecialchars(strip_tags($this->password));

                $this->adviser = htmlspecialchars(strip_tags($this->adviser));
                $this->admin = htmlspecialchars(strip_tags($this->admin));
                $this->committee_role = htmlspecialchars(strip_tags($this->committee_role));
                $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));

                $stmt->bindParam(':faculty_id', $this->faculty_id);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':name', $this->name);
                $stmt->bindParam(':password', $this->password);
                
                $stmt->bindParam(':adviser', $this->adviser);
                $stmt->bindParam(':admin', $this->admin);
                $stmt->bindParam(':committee_role', $this->committee_role);
                $stmt->bindParam(':contact_number', $this->contact_number);

                $stmt->execute();
                return $stmt;
            }
            catch (PDOException $err) {
                return false;
            }
        }

        //remove faculty
        public function removeFaculty() {
            try {
                $query ='DELETE FROM ' .$this->faculty. '
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

        public function editStudent() {
            try {
                $query = 'UPDATE ' .$this->students. '
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
                        curriculum = :curriculum_name
                    WHERE student_id = :student_id_holder';

                    $stmt = $this->conn->prepare($query);

                    $this->student_id = htmlspecialchars(strip_tags($this->student_id));
                    $this->student_id_holder = htmlspecialchars(strip_tags($this->student_id_holder));
                    $this->password = htmlspecialchars(strip_tags($this->password));
                    $this->firstname = htmlspecialchars(strip_tags($this->firstname));
                    $this->middlename = htmlspecialchars(strip_tags($this->middlename));
                    $this->lastname = htmlspecialchars(strip_tags($this->lastname));
                    $this->email = htmlspecialchars(strip_tags($this->email));
                    $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));
                    $this->college = htmlspecialchars(strip_tags($this->college));
                    $this->course = htmlspecialchars(strip_tags($this->course));
                    $this->curriculum_name = htmlspecialchars(strip_tags($this->curriculum_name));
                    
                    $stmt->bindParam(':student_id', $this->student_id);
                    $stmt->bindParam(':student_id_holder', $this->student_id_holder);
                    $stmt->bindParam(':password', $this->password);
                    $stmt->bindParam(':firstname', $this->firstname);
                    $stmt->bindParam(':middlename', $this->middlename);
                    $stmt->bindParam(':lastname', $this->lastname);
                    $stmt->bindParam(':email', $this->email);
                    $stmt->bindParam(':contact_number', $this->contact_number);
                    $stmt->bindParam(':college', $this->college);
                    $stmt->bindParam(':course', $this->course);
                    $stmt->bindParam(':curriculum_name', $this->curriculum_name);

                    $stmt->execute();
                    return $stmt;
            }
            catch (PDOException $err) {
                return false;
            }
        }

        public function viewSubjects() {
            try {
                $query = 'SELECT * FROM ' . $this->subjects . '
                WHERE subject_curriculum = :course_name
                AND subject_year = :subject_year
                AND subject_semester = :subject_semester';

                $stmt = $this->conn->prepare($query);

                $this->course_name = htmlspecialchars(strip_tags($this->course_name));
                $this->subject_year = htmlspecialchars(strip_tags($this->subject_year));
                $this->subject_semester = htmlspecialchars(strip_tags($this->subject_semester));


                $stmt->bindParam(':course_name', $this->course_name);
                $stmt->bindParam(':subject_year', $this->subject_year);
                $stmt->bindParam(':subject_semester', $this->subject_semester);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function editSubject() {
            try {
                $query = 'UPDATE ' . $this->subjects . ' SET 
                    subject_code = :subject_code, 
                    subject_curriculum = :course_name,
                    subject_name = :subject_name,
                    subject_year = :subject_year,
                    subject_semester = :subject_semester,
                    subject_units = :subject_units
                    WHERE subject_code = :subject_code_holder
                    AND subject_curriculum = :course_name_holder
                    AND subject_year = :subject_year_holder
                    AND subject_semester = :subject_semester_holder';

                    $stmt = $this->conn->prepare($query);
                    
                    $this->subject_code_holder = htmlspecialchars(strip_tags($this->subject_code_holder));
                    $this->course_name_holder = htmlspecialchars(strip_tags($this->course_name_holder));
                    $this->subject_year_holder = htmlspecialchars(strip_tags($this->subject_year_holder));
                    $this->subject_semester_holder = htmlspecialchars(strip_tags($this->subject_semester_holder));
                    $this->subject_code = htmlspecialchars(strip_tags($this->subject_code));
                    $this->course_name = htmlspecialchars(strip_tags($this->course_name));
                    $this->subject_name = htmlspecialchars(strip_tags($this->subject_name));
                    $this->subject_year = htmlspecialchars(strip_tags($this->subject_year));
                    $this->subject_semester = htmlspecialchars(strip_tags($this->subject_semester));
                    $this->subject_units = htmlspecialchars(strip_tags($this->subject_units));

                    $stmt->bindParam(':subject_code_holder', $this->subject_code_holder);
                    $stmt->bindParam(':course_name_holder', $this->course_name_holder);
                    $stmt->bindParam(':subject_year_holder', $this->subject_year_holder);
                    $stmt->bindParam(':subject_semester_holder', $this->subject_semester_holder);
                    $stmt->bindParam(':subject_code', $this->subject_code);
                    $stmt->bindParam(':course_name', $this->course_name);
                    $stmt->bindParam(':subject_name', $this->subject_name);
                    $stmt->bindParam(':subject_year', $this->subject_year);
                    $stmt->bindParam(':subject_semester', $this->subject_semester);
                    $stmt->bindParam(':subject_units', $this->subject_units);

                    $stmt->execute();
                    return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function removeSubject () {
            try {
                $query ='DELETE FROM ' .$this->subjects. '
                WHERE subject_code = :subject_code
                AND  subject_curriculum = :course_name
                AND subject_year = :subject_year
                AND subject_semester = :subject_semester';

                $stmt = $this->conn->prepare($query);

                $this->subject_code = htmlspecialchars(strip_tags($this->subject_code));
                $this->course_name = htmlspecialchars(strip_tags($this->course_name));
                $this->subject_year = htmlspecialchars(strip_tags($this->subject_year));
                $this->subject_semester = htmlspecialchars(strip_tags($this->subject_semester));

                $stmt->bindParam(':subject_code', $this->subject_code);
                $stmt->bindParam(':course_name', $this->course_name);
                $stmt->bindParam(':subject_year', $this->subject_year);
                $stmt->bindParam(':subject_semester', $this->subject_semester);

                $stmt->execute();
                return $stmt;

            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function addSubject() {
            try {
                $query = 'INSERT INTO ' .$this->subjects. '
                    SET 
                        subject_code = :subject_code,
                        subject_name = :subject_name,
                        subject_year = :subject_year,
                        subject_semester = :subject_semester,
                        subject_curriculum = :curriculum_name,
                        subject_units = :subject_units';

                $stmt = $this->conn->prepare($query);

                $this->subject_code = htmlspecialchars(strip_tags($this->subject_code));
                $this->subject_name = htmlspecialchars(strip_tags($this->subject_name));
                $this->subject_year = htmlspecialchars(strip_tags($this->subject_year));
                $this->subject_semester = htmlspecialchars(strip_tags($this->subject_semester));
                $this->curriculum_name = htmlspecialchars(strip_tags($this->curriculum_name));
                $this->subject_units = htmlspecialchars(strip_tags($this->subject_units));
                
                $stmt->bindParam(':subject_code', $this->subject_code);
                $stmt->bindParam(':subject_name', $this->subject_name);
                $stmt->bindParam(':subject_year', $this->subject_year);
                $stmt->bindParam(':subject_semester', $this->subject_semester);
                $stmt->bindParam(':curriculum_name', $this->curriculum_name);
                $stmt->bindParam(':subject_units', $this->subject_units);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        //layer 2 start

        public function approveSecretary() {
            try {
                $query = 'UPDATE ' .$this->approved_forms. '
                    SET 
                        institute_secretary_approval = "yes"
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

        public function approveChairperson() {
            try {
                $query = 'UPDATE ' .$this->approved_forms. '
                    SET 
                        chairperson_approval = "yes"
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

        public function viewSecretary() {
            try {
                $query = 'SELECT * FROM ' .$this->approved_forms. '
                WHERE institute_secretary_approval = "no"
                AND computer_science_department_head_approval = "yes"
                AND information_technology_department_head_approval = "yes"';

                $stmt = $this->conn->prepare($query);
                    
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function viewChairperson() {
            try {
                $query = 'SELECT * FROM ' .$this->approved_forms. '
                WHERE chairperson_approval = "no"
                AND institute_secretary_approval = "yes"';

                $stmt = $this->conn->prepare($query);
                    
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        //layer 2 end

        //layer 1 start

        public function approveGenderCoordinator() {
            try {
                $query = 'UPDATE ' .$this->approved_forms. ' SET 
                        gender_coordinator_approval = "yes"
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

        public function viewGenderCoordinator() {
            try {
                $query = 'SELECT * FROM ' .$this->approved_forms. '
                WHERE gender_coordinator_approval = "no"
                AND student_affair_coordinator_approval = "yes"';

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

        public function approveStudentAffair() {
            try {
                $query = 'UPDATE ' .$this->approved_forms. '
                    SET 
                        student_affair_coordinator_approval = "yes"
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

        public function viewStudentAffair() {
            try {
                $query = 'SELECT * FROM ' .$this->approved_forms. '
                WHERE student_affair_coordinator_approval = "no"';

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

        public function approveInformationTechnology() {
            try {
                $query = 'UPDATE ' .$this->approved_forms. '
                    SET 
                        information_technology_department_head_approval = "yes"
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

        public function viewInformationTechnology() {
            try {
                $query = 'SELECT * FROM ' .$this->approved_forms. '
                WHERE information_technology_department_head_approval = "no"
                AND gender_coordinator_approval = "yes"';

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

        public function approveComputerScience() {
            try {
                $query = 'UPDATE ' .$this->approved_forms. '
                    SET 
                        computer_science_department_head_approval = "yes"
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

        public function viewComputerScience() {
            try {
                $query = 'SELECT * FROM ' .$this->approved_forms. '
                WHERE computer_science_department_head_approval = "no"
                AND gender_coordinator_approval = "yes"';

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

        //layer 1 end 

        public function viewApprovedFinal() {
            try {
                $query = 'SELECT * FROM ' .$this->approved_forms. '
                WHERE institute_secretary_approval = "yes"
                AND chairperson_approval = "yes"';

                $stmt = $this->conn->prepare($query);

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

        public function editArchive() {
            try {
                $query = 'UPDATE ' .$this->defaults. '
                    SET 
                        archive_date = :archive_date
                    WHERE default_id = 1';

                $stmt = $this->conn->prepare($query);

                $this->archive_date = htmlspecialchars(strip_tags($this->archive_date));
                $stmt->bindParam(':archive_date', $this->archive_date);
                
                $stmt->execute();
                 return $stmt;
            } catch (PDOException $err) {
                return false;
            }
        }

        public function editArchive2() {
            try {
                $query = 'UPDATE ' .$this->defaults. '
                    SET 
                        archive_date2 = :archive_date
                    WHERE default_id = 1';

                $stmt = $this->conn->prepare($query);

                $this->archive_date = htmlspecialchars(strip_tags($this->archive_date));
                $stmt->bindParam(':archive_date', $this->archive_date);
                
                $stmt->execute();
                 return $stmt;
            } catch (PDOException $err) {
                return false;
            }
        }

        public function editFinalizing() {
            try {
                $query = 'UPDATE ' .$this->defaults. '
                    SET 
                        finalizing = :finalizing
                    WHERE default_id = 1';

                $stmt = $this->conn->prepare($query);

                $this->finalizing = htmlspecialchars(strip_tags($this->finalizing));
                $stmt->bindParam(':finalizing', $this->finalizing);
                
                $stmt->execute();
                 return $stmt;
            } catch (PDOException $err) {
                return false;
            }
        }

        public function editApplication() {
            try {
                $query = 'UPDATE ' .$this->defaults. '
                    SET 
                        application = :application
                    WHERE default_id = 1';

                $stmt = $this->conn->prepare($query);

                $this->application = htmlspecialchars(strip_tags($this->application));
                $stmt->bindParam(':application', $this->application);
                
                $stmt->execute();
                 return $stmt;
            } catch (PDOException $err) {
                return false;
            }
        }

        public function editSchoolYear() {
            try {
                $query = 'UPDATE ' .$this->defaults. '
                    SET 
                        school_year = :school_year
                    WHERE default_id = 1';

                    $stmt = $this->conn->prepare($query);

                    $this->school_year = htmlspecialchars(strip_tags($this->school_year));
                    $stmt->bindParam(':school_year', $this->school_year);
                    
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                return false;
            }
        }

        public function editSemester() {
            try {
                $query = 'UPDATE ' .$this->defaults. '
                    SET 
                        semester = :semester
                    WHERE default_id = 1';

                    $stmt = $this->conn->prepare($query);

                    $this->semester = htmlspecialchars(strip_tags($this->semester));
                    $stmt->bindParam(':semester', $this->semester);
                    
                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
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

        public function addCurriculum() {
            try {
                $query = 'INSERT INTO ' .$this->subject_curriculum. '
                    SET 
                        curriculum_name = :course_name,
                        course = :course_name_curriculum';

                $stmt = $this->conn->prepare($query);

                $this->course_name = htmlspecialchars(strip_tags($this->course_name));
                $this->course_name_curriculum = htmlspecialchars(strip_tags($this->course_name_curriculum));
                $stmt->bindParam(':course_name_curriculum', $this->course_name_curriculum);
                $stmt->bindParam(':course_name', $this->course_name);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function checkCommitteeRole() {
            try {
                $query = 'SELECT committee_role FROM ' .$this->faculty. ' WHERE committee_role = :committee_role';

                $stmt = $this->conn->prepare($query);

                $this->committee_role = htmlspecialchars(strip_tags($this->committee_role));
                $stmt->bindParam(':committee_role', $this->committee_role);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function checkMemberRole() {
            try {
                $query = 'SELECT committee_role FROM ' .$this->faculty. ' WHERE faculty_id = :faculty_id_change';

                $stmt = $this->conn->prepare($query);

                $this->faculty_id_change = htmlspecialchars(strip_tags($this->faculty_id_change));
                $stmt->bindParam(':faculty_id_change', $this->faculty_id_change);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }


        public function viewOneFaculty() {
            try {
                $query = 'SELECT * FROM ' .$this->faculty. ' WHERE faculty_id = :faculty_id';

                $stmt = $this->conn->prepare($query);

                $this->faculty_id = htmlspecialchars(strip_tags($this->faculty_id));
                $stmt->bindParam(':faculty_id', $this->faculty_id);

                $stmt->execute();
                return $stmt;
            } catch (PDOException $err) {
                echo $err;
                return false;
            }
        }

        public function viewOneStudent() {
            try {
                $query = 'SELECT * FROM ' .$this->students. ' WHERE student_id = :student_id';

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


        public function search() {
            try {
                $query = 'SELECT *
                FROM ' .$this->currentAchievers. '
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

        public function searchFaculty() {
            try {
                $query = 'SELECT *
                FROM ' .$this->faculty. '
                WHERE name LIKE :search
                OR faculty_id LIKE :search';

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

        public function sortAdmin() {
            $query = 'SELECT *
            FROM ' .$this->faculty. '
            WHERE admin = "yes"';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function sortAdviser() {
            $query = 'SELECT *
            FROM ' .$this->faculty. '
            WHERE adviser = "yes"';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function sortCommittee() {
            $query = 'SELECT *
            FROM ' .$this->faculty. '
            WHERE committee_role <> "none"';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
        
        public function waitingStatus() {
            $query = 'SELECT *
            FROM ' . $this->waitingForm;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
        
        public function approvedStatus() {
            $query = 'SELECT *
            FROM ' . $this->approvedForms;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

    }
?>