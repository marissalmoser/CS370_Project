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
/*
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="./index.php">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Imports
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                    <a class="dropdown-item" href="./Import1.php">Import 1</a>
                    <a class="dropdown-item" href="./Import2.php">Import 2</a>
                    <a class="dropdown-item" href="./Import3.php">Import 3</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Reports
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                    <a class="dropdown-item" href="./Report1.php">Report 1</a>
                    <a class="dropdown-item" href="./Report2.php">Report 2</a>
                    <a class="dropdown-item" href="./Report3.php">Report 3</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
*/