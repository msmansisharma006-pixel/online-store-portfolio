<?php
session_start();
require_once("dbconnection.php");

// 1. Session check: redirect to signin if not logged in
if (empty($_SESSION['uid'])) {
    header("Location: signin.php");
    exit;
}

$uid = (int)$_SESSION['uid'];

// 2. Prepared statement using columns that actually exist in your database
$stmt = mysqli_prepare($conn, "SELECT firstname, lastname, email, address, reg_date FROM tblusers WHERE id = ?");
if (!$stmt) {
    die("Database query error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $uid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!($user = mysqli_fetch_assoc($result))) {
    session_destroy();
    header("Location: signin.php");
    exit;
}
mysqli_stmt_close($stmt);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-light py-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm p-4">
            <h1 class="h3 mb-3">Dashboard</h1>
            
            <!-- Securely escaped output using htmlspecialchars -->
            <h4 class="text-primary mb-3">
                Welcome, <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>!
            </h4>

            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                </li>
                <?php if (!empty($user['address'])): ?>
                <li class="list-group-item">
                    <strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?>
                </li>
                <?php endif; ?>
                <?php if (!empty($user['reg_date'])): ?>
                <li class="list-group-item">
                    <strong>Member Since:</strong> <?php echo htmlspecialchars($user['reg_date']); ?>
                </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-outline-primary">Continue Shopping</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>