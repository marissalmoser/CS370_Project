<?php
include 'header.php';
?>

<!DOCTYPE html>

<!--header-->
<div class="bg-primary-subtle p-4"> <br>
    <div class="container hei">
        <h1>Import Process 2: <small class="text-body-secondary">Story, Author, Tag, and Advertisement </small> </h1>
        <br>
        <p>This is the import 2 page. Here you can import story, author, tag, and advertisement data.</p>
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
            while (($data = fgetcsv($handle, $length = null, $separator = ',', $enclosure = '"', $escape = '\\')) !== false) {
                list(
                    $displayName,
                    $email,
                    $password,
                    $role,
                    $subscriptionStatus,
                    $dateJoined,
                    $storyTitle,
                    $storyBody,
                    $publishedTimestamp,
                    $comicUrl,
                    $commentText,
                    $commentTimestamp
                    ) = $data;

                // skip blank or all-empty rows
                if (empty(array_filter($data))) {
                    continue;
                }

                // ---------- Insert/Update USER ----------
//                $stmt = $mysqli->prepare("SELECT UserID FROM User WHERE DisplayName = ?");
//                $stmt->bind_param("s", $displayName);
//                $stmt->execute();
//                $stmt->bind_result($userID);
//
//                if (!$stmt->fetch()) {
//                    //User doesn't exist, insert it
//                    $stmt->close();
//                    $stmt = $mysqli->prepare("INSERT INTO User
//                        (SubscriptionStatus, DisplayName, Email, Password, DateJoined, Role)
//                        VALUES (?, ?, ?, ?, ?, ?)");
//                    $stmt->bind_param("ssssss", $subscriptionStatus, $displayName, $email, $password, $dateJoined, $role);
//                    $stmt->execute();
//                    $userID = $stmt->insert_id;
//                } else {
//                    //User already exists — update
//                    $stmt->close();
//
//                    //Update the existing records
//                    $stmt = $mysqli->prepare("UPDATE User
//                              SET SubscriptionStatus = ?, Email = ?, Password = ?, dateJoined = ?, Role = ?
//                              WHERE UserID = ?");
//                    $stmt->bind_param("ssssss", $subscriptionStatus, $email, $password, $dateJoined, $role, $userID);
//                    $stmt->execute();
//                }
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
                    <a type="button" class="btn btn-lg btn-primary" href="./Report1.php">View Report 2</a>
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