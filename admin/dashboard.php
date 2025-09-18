<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: ../index.php");
    exit;
}

include 'navbar.php';
require_once '../classes/course.php';

$courseObj = new Course();
$courses = $courseObj->getAllCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            margin: 20px 0;
            text-align: center;
            color: #333;
        }

        h2 {
            color: #333;
            margin-top: 40px;
            margin-bottom: 15px;
            text-align: center;
        }

        .form-box {
            background: #fff;
            border: 2px solid #c8aeb0;
            border-radius: 8px;
            padding: 20px;
            margin: 30px auto;
            max-width: 500px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }

        .form-box h2 {
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
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

        .form-box button[type="submit"] {
            display: block;
            width: 60%;
            margin: 15px auto 0 auto;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #c8aeb0;
            color: white;
        }

        td button {
            margin: 2px;
        }

        .edit input[type="text"] {
            width: 90%;
            padding: 6px;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h1>Add New Course</h1>
        <form method="POST" action="../handlers/handleForms.php">
            <label for="code">Course Code</label>
            <input type="text" name="code" id="code" placeholder="Course Code" required>

            <label for="name">Course Name</label>
            <input type="text" name="name" id="name" placeholder="Course Name" required>

            <label for="year_levels">Year Levels</label>
            <input type="text" name="year_levels" id="year_levels" placeholder="Year Levels">

            <button type="submit" name="add_course">Add Course</button>
        </form>
    </div>

    <h2>Existing Courses</h2>
    <table>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Year Levels</th>
            <th>Actions</th>
        </tr>
        <?php foreach($courses as $c): ?>
        <tr id="row-<?= $c['id'] ?>">
            <form method="POST" action="../handlers/handleForms.php" id="form-<?= $c['id'] ?>">
                <td class="view"><?= htmlspecialchars($c['code']) ?></td>
                <td class="view"><?= htmlspecialchars($c['name']) ?></td>
                <td class="view"><?= htmlspecialchars($c['year_levels']) ?></td>

                <!-- Hidden inputs for editing -->
                <td class="edit" style="display:none;">
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    <input type="text" name="code" value="<?= htmlspecialchars($c['code']) ?>" required>
                </td>
                <td class="edit" style="display:none;">
                    <input type="text" name="name" value="<?= htmlspecialchars($c['name']) ?>" required>
                </td>
                <td class="edit" style="display:none;">
                    <input type="text" name="year_levels" value="<?= htmlspecialchars($c['year_levels']) ?>">
                </td>

                <td>
                    <div class="view">
                        <button type="button" onclick="toggleEdit('<?= $c['id'] ?>')">Edit</button>
                        <button type="submit" name="delete_course" onclick="return confirm('Are you sure?')">Delete</button>
                    </div>
                    <div class="edit" style="display:none;">
                        <button type="submit" name="edit_course">Save</button>
                        <button type="button" onclick="toggleEdit('<?= $c['id'] ?>')">Cancel</button>
                    </div>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </table>

    <script>
    function toggleEdit(id) {
        let row = document.getElementById("row-" + id);
        let viewElems = row.querySelectorAll(".view");
        let editElems = row.querySelectorAll(".edit");

        viewElems.forEach(el => { el.style.display = (el.style.display === "none") ? "table-cell" : "none"; });
        editElems.forEach(el => { el.style.display = (el.style.display === "none") ? "table-cell" : "none"; });
    }
    </script>
</body>
</html>