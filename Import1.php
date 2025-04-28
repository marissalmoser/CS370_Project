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

    if($_SERVER["REQUEST_METHOD"] == 'POST')
    {
        //echo "Posted!!!\n";
        $import_attempted = true;

        $con = @mysqli_connect("localhost","pizza_user","","acadia");

        if(mysqli_connect_errno()){
        {
            $import_error_message = "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }
    else
    {
        try
        {
            $contents = file_get_contents($_FILES["importFile"]["tmp_name"]);
            $lines = explode("\n", $contents);

            foreach($lines as $line)
            {
                $parsed_csv_line = str_getcsv($line);
                // do something with parsed data
            }

        }
        catch(Error $e)
        {
            $import_error_message = $e->getMessage() . " at: " . $e->getFile() . " line " . $e->getLine() ."<br/>";

        }
    }

}
?>
<?php

if($import_attempted){
    if($import_successful){
        ?>
        <h1><span style = "color: green"> Import Succeeded! </span></h1>
        #coun
        <?php
    }
    else
    {
        ?>
        <h1><span style = "color: red"> Import Failed</span></h1>
        <?php
        echo $import_error_message; ?>
        <br><br>
        <?php
    }
}
?>
    <form method = "post" enctype = "multipart/form-data">
        <div class = "input-group mb-3">
            File: <input type = "file" name = "importFile" />
            <br><br>
            <input type ="submit" value ="Upload Data" />
        </div>
    </form>

    </div>
<?php include_once("footer.php"); ?>