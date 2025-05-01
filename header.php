<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Schema</title>
    <!-- Link to Bootstrap CSS (adjust as needed) -->
    <link href="bootstrap.css" rel="stylesheet">
</head>
<body>
<ul class = "nav">
    <li class = "nav=item"></li>
    <a class = "nav-linl" href = "index.php"> Pizza Home</a>
    <li class = "nav-item dropdown">
        <a class = "nav-link dropdown-toggle" data-bs-toggle = "dropdown"
           href = "#" role = "button" aria-expanded = "false"> Data Import</a>
        <ul class = "dropdown-menu">
            <li>
                <a class = "dropdown-item" href = "Import1.php">Import Author/Story</a>
            </li>
            <li>
                <a class = "dropdown-item" href = "Import2.php"> Import Tag</a>
            </li>
            <li>
                <a class = "dropdown-item" href = "Import3.php"> Import User/Comment</a>
            </li>
        </ul>
    </li>
    <li class = "nav-item dropdown">
        <a class = "nav-link dropdown-toggle" data-bs-toggle = "dropdown"
           href = "#" role = "button" aria-expanded = "false">Data Reports</a>
        <ul class = "dropdown-menu">
            <li>
                <a class = "dropdown-item" href = "Report1.php">Author/Story Report</a>
            </li>
            <li>
                <a class = "dropdown-item" href = "Report2.php"> Tag Report</a>
            </li>
            <li>
                <a class = "dropdown-item" href = "Report3.php">User/Comment Report</a>
            </li>
        </ul>
    </li>
</ul>
