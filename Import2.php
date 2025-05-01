<?php
include 'header.php';
?>

    <!DOCTYPE html>

    <div class="container">
        <h1>Welcome to My Website</h1>
        <p>This is the import 2 page. Here you can import tag data.</p>
    </div>

    <!--connect to server-->
<?php

mysqli_report(MYSQLI_REPORT_ERROR);
$import_attempted = false;
$import_successful = false;
$import_error_message = "";

if($_SERVER["REQUEST_METHOD"] == 'POST')
{

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
            //set up file to read
            $contents = file_get_contents($_FILES["importFile"]["tmp_name"]);
            $lines = explode("\n", $contents);
            $headers = str_getcsv(array_shift($lines), ",", '"', "\\");
            $sql = "INSERT INTO tag (DisplayName)
                    VALUES (?)";

            $stmt = $mysqli->prepare($sql);

            foreach ($lines as $line)
            {
                // skip empty lines
                if (trim($line) === '')
                    continue;
                $parsed = str_getcsv($line, ",", '"', "\\");

                // Sanity check: make sure we have all 4 fields
                if (count($parsed) < 1) continue;

                //assign variables from data
                $displayName = trim($parsed[0]);

                //put data into query
                $stmt->bind_param("s", $displayName);
                $stmt->execute();
            }

            $stmt->close();
            $import_successful = true;
        }
        catch (Error $e)
        {
            $import_error_message = $e->getMessage() . " at: " . $e->getFile() . " line " . $e->getLine() . "<br/>";
        }
    }
}
?>

    <!--success and fail messages-->
<?php
if($import_attempted){
    if($import_successful){
        ?>
        <h1><span class="text-success"> Import Succeeded! </span></h1>
        <?php
    }
    else
    {
        ?>
        <h1><span class="text-danger"> Import Failed</span></h1>
        <?php
        echo $import_error_message; ?>
        <?php
    }
}
?>

    <!--form-->
    <br><br>
    <form method = "post" enctype = "multipart/form-data">
        <div class = "input-group mb-3">
            File: <input type = "file" name = "importFile" />
            <br><br>
            <input type ="submit" value ="Upload Data" />
        </div>
    </form>

<?php include_once("footer.php"); ?>