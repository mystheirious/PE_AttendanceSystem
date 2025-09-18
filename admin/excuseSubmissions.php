<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: ../index.php");
    exit;
}

include 'navbar.php';
require_once '../classes/course.php';
require_once '../classes/student.php';
require_once '../classes/excuseLetter.php';

$courseObj = new Course();
$excuseObj = new ExcuseLetter();

$courses = $courseObj->getAllCourses();

$courseFilter = isset($_GET['course']) ? $_GET['course'] : '';
$yearFilter   = isset($_GET['year_level']) ? $_GET['year_level'] : '';

if ($courseFilter && $yearFilter) {
    $excuses = $excuseObj->getByCoursesYear($courseFilter, $yearFilter);
} else {
    $excuses = $excuseObj->getAllWithStudents();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Excuse Letters</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { 
            font-family: Arial, 
            sans-serif; 
            background: #f5f5f5; 
            margin: 0; 
            padding: 0;
        }
        h1 { 
            margin-top: 30px; 
            text-align: center; 
            color: #333; 
        }
        form { 
            text-align: center; 
            margin: 20px auto; 
        }
        select, input[type="text"] { 
            padding: 8px; 
            border-radius: 4px; 
            border: 1px solid #ccc; 
            margin: 0 5px; 
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
        table { 
            width: 90%; 
            margin: 20px auto;
            border-collapse: collapse; 
            background: #fff; 
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
    </style>
</head>
<body>
    <h1>Excuse Letter Management</h1>

    <form method="GET" action="">
        <select name="course" required>
            <option value="">Select Course</option>
            <?php foreach($courses as $c): ?>
                <option value="<?= htmlspecialchars($c['name']) ?>"
                    <?= ($courseFilter == $c['name']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="year_level" placeholder="Year Level" value="<?= htmlspecialchars($yearFilter) ?>">

        <button type="submit">Filter</button>
    </form>

    <?php if($excuses): ?>
    <table>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Reason</th>
            <th>Letter</th>            
            <th>Date Submitted</th>
            <th>Status</th>
        </tr>
        <?php foreach($excuses as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['student_number']) ?></td>
            <td><?= htmlspecialchars($e['full_name']) ?></td>
            <td><?= htmlspecialchars($e['reason']) ?></td>
            <td>
                <?php if (!empty($e['photo'])): ?>
                    <a href="<?= htmlspecialchars($e['photo']) ?>" target="_blank">
                        <img src="<?= htmlspecialchars($e['photo']) ?>" alt="Photo" style="max-width:80px; max-height:80px;">
                    </a>
                <?php else: ?>
                    No Photo
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($e['date_submitted']) ?></td>
            <td>
                <select name="status" class="form-control status_select" data-excuse-id="<?= $e['id']; ?>">
                    <option value="Pending"  <?= ($e['status']=="Pending") ? "selected" : "" ?>>Pending</option>
                    <option value="Approved" <?= ($e['status']=="Approved") ? "selected" : "" ?>>Approved</option>
                    <option value="Rejected" <?= ($e['status']=="Rejected") ? "selected" : "" ?>>Rejected</option>
                </select>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p style="text-align:center;">No excuse letters found.</p>
    <?php endif; ?>

    <script>
    $('.status_select').on('change', function (event) {
        event.preventDefault();
        var formData = {
          excuse_id: $(this).data('excuse-id'),
          status: $(this).val(),
          updateExcuseStatus: 1
        }

        if (formData.excuse_id != "" && formData.status != "") {
          $.ajax({
            type: "POST",
            url: "../handlers/handleForms.php",
            data: formData,
            success: function (data) {
              if (data) {
                location.reload();
              } else {
                alert("Status update failed");
              }
            }
          })
        }
    })
    </script>

</body>
</html>