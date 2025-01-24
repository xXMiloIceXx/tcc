<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $q1 = mysqli_query($conn, "select * from users where id='$id'");
    $row = mysqli_fetch_array($q1);
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <div class="py-4">
            <h4>View User</h4>
        </div>
        <?php
        if (isset($row['profile_picture'])) {
            echo "<img src='" . $row['profile_picture'] . "' class='img-thumbnail' alt='profile_picture'>";
        }
        ?>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="form-control"><?= $row['username'] ?? 'Unknown' ?></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="form-control"><?= $row['email'] ?? 'No email' ?></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <div class="form-control"><?= $row['role'] ?? 'No role' ?></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <div class="form-control"><?= $row['status'] ?? 'Inactive' ?></div>
        </div>
        <div class="mb-3">
            <a class="btn btn-secondary" href="index.php">Back</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
