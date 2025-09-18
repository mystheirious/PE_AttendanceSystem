<?php
require_once 'database.php';

class ExcuseLetter extends Database {
    protected $table = "excuse_letters";

    public function submitLetter($student_id, $course, $year_level, $reason, $photo = null) {
        $data = [
            "student_id" => $student_id,
            "course" => $course,
            "year_level" => $year_level,
            "reason" => $reason,
            "photo" => $photo,
            "date_submitted" => date("Y-m-d H:i:s"),
            "status" => "Pending"
        ];
        return $this->insert($this->table, $data);
    }

    public function getByCoursesYear($course, $year_level) {
        $sql = "SELECT excuse_letters.*, students.full_name, students.student_id AS student_number
                FROM excuse_letters
                JOIN students ON excuse_letters.student_id = students.id
                WHERE excuse_letters.course = :course AND excuse_letters.year_level = :year_level
                ORDER BY excuse_letters.date_submitted DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "course" => $course,
            "year_level" => $year_level
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE excuse_letters SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function getAllWithStudents() {
        $sql = "SELECT excuse_letters.*, students.full_name, students.student_id AS student_number, students.course, students.year_level
                FROM excuse_letters
                JOIN students ON excuse_letters.student_id = students.id
                ORDER BY excuse_letters.date_submitted DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>