<?php
require_once 'database.php';

class Course extends Database {
    protected $table = "courses";

    public function addCourse($code, $name, $year_levels="") {
        $data = [
            "code"=>$code,
            "name"=>$name,
            "year_levels"=>$year_levels
        ];
        return $this->insert($this->table, $data);
    }

    public function editCourse($id, $code, $name, $year_levels="") {
        $data = [
            "code" => $code,
            "name" => $name,
            "year_levels" => $year_levels
        ];
        return $this->update($this->table, $data, "id = :id", ["id" => $id]);
    }

    public function deleteCourse($id) {
        return $this->delete($this->table, "id = :id", ["id" => $id]);
    }

    public function getAllCourses() {
        return $this->selectAll($this->table);
    }
}
?>