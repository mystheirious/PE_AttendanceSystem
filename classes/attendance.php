<?php
require_once 'database.php';

class Attendance extends Database {
    protected $table = "attendance";

    public function getByStudent($student_id, $sortOrder = 'DESC') {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE student_id = :uid
                ORDER BY date $sortOrder, time $sortOrder";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["uid" => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCourseYear($course, $year_level, $sortOrder = 'DESC') {
        $sql = "SELECT a.*, s.full_name, s.student_id
                FROM attendance a
                JOIN students s ON a.student_id = s.user_id
                WHERE a.course = :course AND a.year_level = :year
                ORDER BY a.date $sortOrder, a.time $sortOrder";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "course" => $course,
            "year" => $year_level
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>