<?php
include 'header.php';
?>

<!DOCTYPE html>

<div class="container">
    <h1>Welcome to My Website</h1>
    <p>This is the import 1 page.</p>
</div>

<?php

mysqli_report(MYSQLI_REPORT_ERROR);
$import_attempted = false;
$import_successful = false;
$import_error_message = "";

//connect to server
if($_SERVER["REQUEST_METHOD"] == 'POST') {
    //echo "Posted!!!\n";
    $import_attempted = true;

    $mysqli = new mysqli("localhost", "root", "root3", "news");

    if ($mysqli->connect_errno)
    {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
    else
    {
        try {
            $contents = file_get_contents($_FILES["importFile"]["tmp_name"]);
            $lines = explode("\n", $contents);

            foreach ($lines as $line) {
                $parsed_csv_line = str_getcsv($line, ",", '"', "\\");
                // TODO: do something with parsed data
            }

            $import_successful = true;

        } catch (Error $e)
        {
            $import_error_message = $e->getMessage() . " at: " . $e->getFile() . " line " . $e->getLine() . "<br/>";
        }
    }
}
?>

<?php

//success and fail messages
if($import_attempted){
    if($import_successful){
        ?>
        <h1><span class="text-success"> Import Succeeded! </span></h1>
        #coun
        <?php
    }
    else
    {
        ?>
        <h1><span class="text-danger"> Import Failed</span></h1>
        <?php
        echo $import_error_message; ?>
        <br><br>
        <?php
    }
}
?>

//form
<form method = "post" enctype = "multipart/form-data">
    <div class = "input-group mb-3">
        File: <input type = "file" name = "importFile" />
        <br><br>
        <input type ="submit" value ="Upload Data" />
    </div>
</form>

<?php include_once("footer.php"); ?>