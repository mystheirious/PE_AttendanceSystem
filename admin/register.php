<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            font-weight: bold;
            margin-bottom: 35px;
            text-align: center;
        }

        .form-box {
            background: #fff;
            border: 2px solid #c8aeb0;
            border-radius: 8px;
            padding: 20px;
            margin: 30px auto;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        a {
            color: #c8aeb0;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #c8aeb0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: #896A67;
            color: white;
        }
    </style>
</head>
<body>
    <a href="../index.php">Back to Login</a>

    <div class="form-box">
        <h1>Admin Registration</h1>
        <form method="POST" action="../handlers/handleForms.php">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" id="full_name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit" name="register_admin">Register Admin</button>
        </form>
    </div>

</body>
</html>