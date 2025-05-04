<?php
include 'header.php';
?>

<!DOCTYPE html>

<!--header-->
<div class="bg-primary-subtle p-4"> <br>
    <div class="container hei">
        <h1>Import Process 3: <small class="text-body-secondary">Location, Tag, Event</small> </h1>
        <br>
        <p>This is the import 3 page. Here you can import location, tag, and event data.</p>
        <br>
        <br>
        <br>
        <br>
    </div>

    <?php
    include 'ImportsHeader.php';
    ?>
</div>


<?php
//connect to server
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
        try
        {
            // Open and read the uploaded CSV
            $handle = fopen($_FILES["importFile"]["tmp_name"], "r");
            $headers = fgetcsv($handle, $length = null, $separator = ',', $enclosure = '"', $escape = '\\');

            //store data from each line in these variables
            while (($data = fgetcsv($handle, $length = null, $separator = ',', $enclosure = '"', $escape = '\\')) !== false)
            {
                list(
                    $locationName,//location
                    $locationAddress,
                    $dateAddedLoc,
                    $displayName, //tag
                    $dateAddedTag,
                    $eventName, //event
                    $sponsor,
                    $eventStartTime,
                    $eventEndTime,
                    $description
                    ) = $data;

                // skip blank or all-empty rows
                if (empty(array_filter($data)))
                {
                    continue;
                }

                // ---------- Insert/Update LOCATION ----------
                $stmt = $mysqli->prepare("SELECT LocationID FROM location WHERE LocationName = ?");
                $stmt->bind_param("s", $locationName);
                $stmt->execute();
                $stmt->bind_result($locationID);

                if (!$stmt->fetch())
                {
                    //location doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO location 
                        (LocationName, LocationAddress, DateAdded)
                        VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $locationName, $locationAddress, $dateAddedLoc);
                    $stmt->execute();
                    $locationID = $stmt->insert_id;
                }
                else
                {
                    //location already exists — update
                    $stmt->close();

                    //Update the existing records
                    $stmt = $mysqli->prepare("UPDATE location
                              SET LocationAddress = ?, DateAdded = ? 
                              WHERE LocationID = ?");
                    $stmt->bind_param("sss", $locationAddress, $dateAddedLoc, $locationID);
                    $stmt->execute();
                }


                // ---------- Insert/Update TAG ----------
                $stmt = $mysqli->prepare("SELECT TagID FROM tag WHERE DisplayName = ?");
                $stmt->bind_param("s", $displayName);
                $stmt->execute();
                $stmt->bind_result($tagID);

                if (!$stmt->fetch())
                {
                    //tag doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO tag 
                        (DisplayName, DateAdded)
                        VALUES (?, ?)");
                    $stmt->bind_param("ss", $displayName, $dateAddedTag);
                    $stmt->execute();
                    $tagID = $stmt->insert_id;
                }
                else
                {
                    //tag already exists — update
                    $stmt->close();

                    //Update the existing records
                    $stmt = $mysqli->prepare("UPDATE tag
                              SET DateAdded = ?
                              WHERE TagID = ?");
                    $stmt->bind_param("ss", $dateAddedTag, $TagID);
                    $stmt->execute();
                }


                // ---------- Insert/Update EVENT ----------
                //check for data's existence
                $stmt = $mysqli->prepare("SELECT 1 FROM event WHERE TagID = ? AND LocationID = ?");
                $stmt->bind_param("ii", $tagID, $locationID);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows === 0)
                {
                    //Comment doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO event (TagID, LocationID, Sponsor, EventStart, EventEnd, Description, EventName)
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("iisssss", $tagID, $locationID, $sponsor, $eventStartTime, $eventEndTime, $description, $eventName);
                    $stmt->execute();
                }
                else
                {
                    //Comment already exists — update
                    $stmt->close();

                    //Update the existing comment text
                    $stmt = $mysqli->prepare("UPDATE event
                              SET Sponsor = ?, EventStart = ?, EventEnd = ?, Description = ?, EventName = ?
                              WHERE TagID = ? AND LocationID = ?");
                    $stmt->bind_param("sssssii", $sponsor, $eventStartTime, $eventEndTime, $description, $eventName, $tagID, $locationID);
                    $stmt->execute();
                }
            }

            fclose($handle);
            $mysqli->close();
            $import_successful = true;
        }
        catch (Error $e)
        {
            $import_error_message = $e->getMessage() . " at: " . $e->getFile() . " line " . $e->getLine() . "<br/>";
        }
    }
}

?>

    <!--form html-->
    <br><br>
    <div class="container">
        <form method = "post" enctype = "multipart/form-data">
            <div class = "input-group mb-3">
                <input class="form-control" type = "file" name = "importFile" />
                <input class="btn btn-light" type ="submit" value ="Upload Data" />
            </div>
        </form>

        <?php
        //success and fail messages
        if($import_attempted)
        {
            if($import_successful)
            {
                ?>
                <div class="alert alert-success alert-dismissible fade show mt-3 w-auto d-inline-block" role="alert">
                    <strong>Success!</strong> Data has successfully been imported.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <br>
                <div class="d-grid gap-2" >
                    <a type="button" class="btn btn-lg btn-primary" href="./Report1.php">View Report 3</a>
                </div>

                <?php
            }
            else
            {
                ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3 w-auto d-inline-block" role="alert">
                    <strong>Import Failed:</strong>
                    <?php echo htmlspecialchars($import_error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <br>
                <?php
            }
        }

        ?>

    </div>
    <br><br>

<?php include_once("footer.php"); ?>