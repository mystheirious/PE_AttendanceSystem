<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student'){
    header("Location: ../index.php");
    exit;
}

include 'navbar.php';
require_once '../classes/attendance.php';
require_once '../classes/student.php';

$attendanceObj = new Attendance();
$studentObj = new Student();
$studentId = $_SESSION['user']['id'];
$student = $studentObj->selectOne("students", "user_id = :uid", ["uid"=>$studentId]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: dimgray;
            font-weight: bold;
            margin: 0;
            text-align: center;
            padding: 30px 20px 10px;
        }

        .form-box {
            background: #fff;
            border: 2px solid #a78385;
            border-radius: 10px;
            padding: 25px;
            margin: 20px auto;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h2 {
            color: dimgray;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #a78385;
            font-weight: bold;
        }

        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            display: block;
            margin: 0 auto;
            background: #a78385;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #c8aeb0;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['full_name']) ?>!</h1>

    <div class="form-box">
        <h2>File Attendance</h2>
        <form method="POST" action="../handlers/handleForms.php">
            <input type="hidden" name="student_id" value="<?= $studentId ?>">

            <label for="course">Course</label>
            <input type="text" name="course" id="course" value="<?= $student['course'] ?>" readonly>

            <label for="year_level">Year Level</label>
            <input type="text" name="year_level" id="year_level" value="<?= $student['year_level'] ?>" readonly>

            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Excused">Excused</option>
            </select>

            <button type="submit" name="file_attendance">Mark Attendance</button>
        </form>
    </div>
</body>
</html>