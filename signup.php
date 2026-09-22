<?php
session_start();
require_once("dbconnection.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($fname) || empty($email) || empty($password)) {
        $error = "All required fields must be filled.";
    } else {
        // Hash the password before it ever touches the database.
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "INSERT INTO tblusers (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("SQL Prepare Error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "ssss", $fname, $lname, $email, $hashedPassword);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['flash_msg'] = "Registration successful! Please login.";
            header("Location: signin.php");
            exit;
        } else {
            $error = "Registration failed. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col">

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form class="row g-3 needs-validation" novalidate method="post">
                    <div class="col-md-6">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" required>
                        <div class="invalid-feedback">
                            First name is required.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname">
                    </div>
                    <div class="col-md-6">
                        <label for="validationCustomUsername" class="form-label">Email</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                            <input type="email" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="email" required>
                            <div class="invalid-feedback">
                                Email is required.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        <div class="invalid-feedback">
                            Password is required (min 6 characters).
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                            <label class="form-check-label" for="invalidCheck">
                                Agree to terms and conditions
                            </label>
                            <div class="invalid-feedback">
                                You must agree before submitting.
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <a href="signin.php" class="btn btn-secondary">Back</a>
                        <button class="btn btn-primary float-end" type="submit" name="submit">Sign up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>