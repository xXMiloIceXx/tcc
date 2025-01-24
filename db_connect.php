<?php
$conn = new mysqli("localhost", "root", "TCCProject", "tcc");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}