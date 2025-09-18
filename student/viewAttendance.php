<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student'){
    header("Location: ../index.php");
    exit;
}

include 'navbar.php';
require_once '../classes/attendance.php';

$attendanceObj = new Attendance();
$studentId = $_SESSION['user']['id'];
$attendances = $attendanceObj->getByStudent($studentId, 'DESC');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance History</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        h1 {
            color: dimgray;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #a78385;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Attendance History</h1>
    <table>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Course</th>
            <th>Status</th>
            <th>Late</th>
        </tr>
        <?php foreach($attendances as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['date']) ?></td>
            <td><?= htmlspecialchars($a['time']) ?></td>
            <td><?= htmlspecialchars($a['course']) ?></td>
            <td><?= htmlspecialchars($a['status']) ?></td>
            <td><?= $a['is_late'] ? 'Yes':'No' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>