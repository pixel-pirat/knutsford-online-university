<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knutsford - Sign Up</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .container input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .container button {
            width: 100%;
            padding: 10px;
            background-color: #6200ea;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #3700b3;
        }
        .profile-pic {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Knutsford</h2>
        <form method="POST" action="signup.php">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email (gmail.com only)" required>
            <input type="text" name="studentid" placeholder="Student ID (26103XXX)" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirmpassword" placeholder="Confirm Password" required>
            <div>
                <input type="checkbox" name="terms" required>
                <label for="terms">I've read and agree to the terms of service</label>
            </div>
            <button type="submit" name="signup">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Sign in</a></p>
    </div>

    <?php
    if (isset($_POST['signup'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $studentid = $_POST['studentid'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirmpassword'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
            echo "<script>alert('Email must be a valid gmail.com address');</script>";
        } elseif (!preg_match("/^26103\d{4}$/", $studentid)) {
            echo "<script>alert('Student ID must start with 26103 and be 8 characters long');</script>";
        } elseif ($password !== $confirmpassword) {
            echo "<script>alert('Passwords do not match');</script>";
        } else {
            $conn = new mysqli('localhost', 'username', 'password', 'user_management');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (fullname, email, studentid, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $email, $studentid, $password_hash);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('User with this email or student ID already exists');</script>";
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>
</body>
</html>
