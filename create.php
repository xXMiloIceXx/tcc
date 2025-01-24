<?php
include 'db_connect.php';

if (isset($_POST["savebtn"])) {
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    if (!is_null($file_tmp) && $file_tmp != "") {
        $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
        $data = file_get_contents($file_tmp);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    $username = $_POST["username"];
    $email    = $_POST["email"];
    $role     = $_POST["role"];
    $status   = $_POST["status"];

    if (is_null($file_tmp) || $file_tmp == "") {
        mysqli_query($conn, "INSERT INTO users (username, email, role, status) VALUES('$username', '$email', '$role', '$status')");
        header('Location: index.php');
    } else {
        mysqli_query($conn, "INSERT INTO users (username, email, role, status, profile_picture) VALUES('$username', '$email', '$role', '$status', '$base64')");
        header('Location: index.php');
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <div class="py-4">
            <h4>Create User</h4>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="fileToUpload" class="form-label">Profile Picture</label>
                <input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input name="username" type="text" class="form-control" placeholder="Username" value="<?= $_POST['username'] ?? null ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Email" value="<?= $_POST['email'] ?? null ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="member" <?= (isset($_POST['role']) && $_POST['role'] == 'member') || !isset($_POST['role']) ? 'selected' : '' ?>>Member</option>
                    <option value="staff" <?= isset($_POST['role']) && $_POST['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                    <option value="admin" <?= isset($_POST['role']) && $_POST['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="active" <?= (isset($_POST['status']) && $_POST['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= (isset($_POST['status']) && $_POST['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <a class="btn btn-danger" href="index.php">Cancel</a>
                <button name="savebtn" type="submit" class="btn btn-primary float-end">Save</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>