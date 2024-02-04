<?php
include 'db_connect.php';

if (isset($_GET['del'])) {
    $id = $_GET['id'];
    $q2 = mysqli_query($conn, "update products set deleted_at='" . date('Y-m-d H:i:s') . "' where id='$id'");
    if ($q2) {
        header('location:index.php');
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://kit.fontawesome.com/c19f58079b.js" crossorigin="anonymous"></script>
    <style>
        td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row py-4">
            <div class="col-md-6 col-12">
                <h4>List of Products</h4>
            </div>
            <div class="col-md-6 col-12"><a class="btn btn-primary float-end" href="create.php">Add Product</a></div>
        </div>
        <table class="table table-striped table-bordered datatable border">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th class="text-center">action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q1 = mysqli_query($conn, "select * from products where deleted_at is null");
                while ($d1 = mysqli_fetch_assoc($q1)) {
                ?>
                    <tr>
                        <td><?= $d1['id'] ?? '0' ?></td>
                        <td><?= $d1['name'] ?? 'name' ?></td>
                        <td><?= $d1['brand'] ?? 'No brand' ?></td>
                        <td>RM <?= $d1['price'] ?? '0.00' ?></td>
                        <td><?= $d1['stock'] ?? '0' ?></td>
                        <td class="d-flex gap-3 text-center justify-content-center">
                            <a href="view.php?id=<?= $d1['id'] ?? '#' ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i> View</a>
                            <a href="edit.php?id=<?= $d1['id'] ?? '#' ?>" class="btn btn-primary"><i class="fa-solid fa-pencil"></i> Edit</a>
                            <a href="index.php?del&id=<?= $d1['id'] ?? '#' ?>" class="btn btn-primary"><i class="fa-solid fa-trash-can"></i> Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                "dom": "<'row'<'col-md-6'l><'col-md-6'f>><'my-3't><'row'<'col-md-6'i><'col-md-6'p>>",
                "columnDefs": [{
                    "targets": 5,
                    "orderable": false
                }]
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>