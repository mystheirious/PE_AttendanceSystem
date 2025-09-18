<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: ../index.php");
    exit;
}

include 'navbar.php';
require_once '../classes/course.php';
require_once '../classes/student.php';
require_once '../classes/attendance.php';

$courseObj = new Course();
$studentObj = new Student();
$attendanceObj = new Attendance();
$courses = $courseObj->getAllCourses();
$students = $studentObj->selectAll("students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Records</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 35px 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin: 30px 0 15px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #c8aeb0;
            color: white;
        }

        form {
            text-align: center;
            margin: 20px auto;
        }

        select, input[type="text"] {
            padding: 8px;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: lightgray;
            color: #333;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: dimgray;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Filter Attendance</h1>
    <form method="GET" action="">
        <select name="course" required>
            <?php foreach($courses as $c): ?>
                <option value="<?= htmlspecialchars($c['name']) ?>" 
                    <?= (isset($_GET['course']) && $_GET['course'] == $c['name']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="year_level" placeholder="Year Level" 
            value="<?= isset($_GET['year_level']) ? htmlspecialchars($_GET['year_level']) : '' ?>">
        <button type="submit" name="filter_attendance">Filter</button>
    </form>

    <?php
    if(isset($_GET['course']) && isset($_GET['year_level'])){
        $course = $_GET['course'];
        $year = $_GET['year_level'];

        $list = $attendanceObj->getByCourseYear($course, $year, 'DESC');
        if($list){
            echo "<table><tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Late</th>
            </tr>";
            foreach($list as $a){
                echo "<tr>
                    <td>".htmlspecialchars($a['student_id'])."</td>
                    <td>".htmlspecialchars($a['full_name'])."</td>
                    <td>".htmlspecialchars($a['date'])."</td>
                    <td>".htmlspecialchars($a['time'])."</td>
                    <td>".htmlspecialchars($a['status'])."</td>
                    <td>".($a['is_late'] ? 'Yes':'No')."</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='text-align:center;'>No attendance records found.</p>";
        }
    }
    ?>
</body>
</html>