<?php
include 'db_connect.php';

if (isset($_POST["savebtn"])) {
    $target_dir = "image/";
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $random_number = rand(10000, 99999);
    $target_file = $target_dir . date('Y-m-d_H-i-s') . '_' . $random_number . '.' . $imageFileType;

    $name   = $_POST["name"];
    $brand  = $_POST["brand"];
    $price  = $_POST["price"];
    $stock  = $_POST["stock"];

    if (is_null($file_name) || $file_name == "") {
        mysqli_query($conn, "INSERT INTO products (name,brand,price,stock) VALUES('$name','$brand','$price','$stock')");
        header('Location: index.php');
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            mysqli_query($conn, "INSERT INTO products (name,brand,price,stock,product_picture) VALUES('$name','$brand','$price','$stock','$target_file')");
            header('Location: index.php');
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <div class="py-4">
            <h4>Create Product</h4>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="fileToUpload" class="form-label">Product Picture</label>
                <input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" type="text" class="form-control" placeholder="Name" value="<?= $_POST['name'] ?? null ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Brand</label>
                <input name="brand" type="text" class="form-control" placeholder="Brand" value="<?= $_POST['brand'] ?? null ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input name="price" type="number" class="form-control" placeholder="Price" value="<?= $_POST['price'] ?? null ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input name="stock" type="number" class="form-control" placeholder="Stock" value="<?= $_POST['stock'] ?? null ?>" required>
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