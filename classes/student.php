<?php
require_once 'user.php';

class Student extends User {
    protected $studentTable = "students";
    protected $attendanceTable = "attendance";

    public function createProfile($user_id, $full_name, $student_id, $course, $year_level) {
        $data = [
            "user_id" => $user_id,            
            "full_name" => $full_name,
            "student_id" => $student_id,
            "course" => $course,
            "year_level" => $year_level,
            "date_added" => date("Y-m-d H:i:s")
        ];
        return $this->insert($this->studentTable, $data);
    }

    public function updateStudent($student_id, $full_name, $email, $course, $year_level) {
        $sql = "SELECT user_id FROM {$this->studentTable} WHERE id = :sid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['sid' => $student_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;

        $user_id = $user['user_id'];

        $sqlUser = "UPDATE users SET full_name = :full_name, email = :email WHERE id = :uid";
        $stmtUser = $this->pdo->prepare($sqlUser);
        $stmtUser->execute([
            'full_name' => $full_name,
            'email' => $email,
            'uid' => $user_id
        ]);

        $sqlStudent = "UPDATE {$this->studentTable} SET full_name = :full_name, course = :course, year_level = :year_level WHERE id = :sid";
        $stmtStudent = $this->pdo->prepare($sqlStudent);
        $stmtStudent->execute([
            'full_name' => $full_name,
            'course' => $course,
            'year_level' => $year_level,
            'sid' => $student_id
        ]);

        return true;
    }

    public function fileAttendance($student_id, $course, $year_level, $status = "Present", $scheduled_time="21:40:00") {
        date_default_timezone_set('Asia/Manila'); 
        $now = date("H:i:s");
        $is_late = ($status === "Present" && strtotime($now) > strtotime($scheduled_time)) ? 1 : 0;

        $data = [
            "student_id" => $student_id,
            "course" => $course,
            "year_level" => $year_level,
            "date" => date("Y-m-d"),
            "time" => $now,
            "status" => $status,
            "is_late" => $is_late
        ];
        return $this->insert($this->attendanceTable, $data);
    }
}
?>