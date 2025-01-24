<?php
include 'db_connect.php';

// Sanitize input function
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

// Fetch user data for the provided ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        die("User not found.");
    }
}

// Handle form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["updatebtn"])) {
    $base64 = null;

    // Sanitize inputs
    $username = sanitizeInput($_POST["username"] ?? '');
    $role = sanitizeInput($_POST["role"] ?? '');
    $status = sanitizeInput($_POST["status"] ?? '');

    // Handle file upload if a new file is provided
    if (!empty($_FILES['fileToUpload']['tmp_name'])) {
        $file_tmp = $_FILES['fileToUpload']['tmp_name'];
        $type = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file type
        if (in_array(strtolower($type), $allowed_extensions)) {
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');</script>";
            return;
        }
    } else {
        // If no new image uploaded, keep the current image (if exists)
        $base64 = $row['profile_picture'] ?? null;
    }

    // Update query with or without profile picture
    if ($base64) {
        $query = "UPDATE users SET username = ?, role = ?, status = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $username, $role, $status, $base64, $id);
    } else {
        $query = "UPDATE users SET username = ?, role = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $username, $role, $status, $id);
    }

    // Execute the update
    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href = 'index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error updating user: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <div class="py-4">
            <h4>Edit User</h4>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <?php
            if (isset($row['profile_picture']) && !empty($row['profile_picture'])) {
                echo "<img src='" . $row['profile_picture'] . "' class='img-thumbnail' alt='profile_picture' style='max-height: 150px;'>";
            }
            ?>
            <div class="mb-3">
                <label for="fileToUpload" class="form-label">Profile Picture</label>
                <input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input name="username" type="text" class="form-control" placeholder="Username" value="<?= $_POST['username'] ?? $row['username'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="member" <?= (isset($_POST['role']) && $_POST['role'] == 'member') || (isset($row['role']) && $row['role'] == 'member') ? 'selected' : '' ?>>Member</option>
                    <option value="staff" <?= (isset($_POST['role']) && $_POST['role'] == 'staff') || (isset($row['role']) && $row['role'] == 'staff') ? 'selected' : '' ?>>Staff</option>
                    <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] == 'admin') || (isset($row['role']) && $row['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="active" <?= (isset($_POST['status']) && $_POST['status'] == 'active') || (isset($row['status']) && $row['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= (isset($_POST['status']) && $_POST['status'] == 'inactive') || (isset($row['status']) && $row['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <a class="btn btn-danger" href="index.php">Cancel</a>
                <button name="updatebtn" type="submit" class="btn btn-primary float-end">Save</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
