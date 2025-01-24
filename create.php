<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["savebtn"])) {
    // Initialize variables
    $base64 = null;

    // Handle file upload
    if (!empty($_FILES['fileToUpload']['tmp_name'])) {
        $file_tmp = $_FILES['fileToUpload']['tmp_name'];
        $type = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Check for valid file type
        if (in_array(strtolower($type), $allowed_extensions)) {
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            echo "<script>alert('Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.');</script>";
        }
    }

    // Sanitize input
    $username = htmlspecialchars(trim($_POST["username"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $role = htmlspecialchars(trim($_POST["role"] ?? ''));
    $status = htmlspecialchars(trim($_POST["status"] ?? ''));

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Error: Invalid email address.');</script>";
    }

    // Check for duplicate email
    $check_query = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Email already exists
        echo "<script>alert('Error: The email address is already in use. Please use a different email.');</script>";
        $check_stmt->close();
        $conn->close();
    } else {
        // Insert data into database
        $query = "INSERT INTO users (username, email, role, status, profile_picture) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $username, $email, $role, $status, $base64);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('User successfully saved!');</script>";
        } else {
            echo "<script>alert('Error: Unable to save user. Please try again.');</script>";
        }

        $stmt->close();
        $conn->close();
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
