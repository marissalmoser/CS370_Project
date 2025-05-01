<?php
include 'header.php';
?>

<!DOCTYPE html>

<div class="container">
    <h1>Welcome to My Website</h1>
    <p>This is the import 1 page. Here you can import story, user, and comment data.</p>
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
        try {
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
                $stmt = $mysqli->prepare("SELECT UserID FROM User WHERE DisplayName = ?");
                $stmt->bind_param("s", $displayName);
                $stmt->execute();
                $stmt->bind_result($userID);

                if (!$stmt->fetch()) {
                    //User doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO User 
                        (SubscriptionStatus, DisplayName, Email, Password, DateJoined, Role)
                        VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $subscriptionStatus, $displayName, $email, $password, $dateJoined, $role);
                    $stmt->execute();
                    $userID = $stmt->insert_id;
                } else {
                    //User already exists — update
                    $stmt->close();

                    //Update the existing records
                    $stmt = $mysqli->prepare("UPDATE User
                              SET SubscriptionStatus = ?, Email = ?, Password = ?, dateJoined = ?, Role = ? 
                              WHERE UserID = ?");
                    $stmt->bind_param("ssssss", $subscriptionStatus, $email, $password, $dateJoined, $role, $userID);
                    $stmt->execute();
                }

                // ---------- Insert/Update STORY  ----------
                $stmt = $mysqli->prepare("SELECT StoryID FROM Story WHERE Title = ?");
                $stmt->bind_param("s", $storyTitle);
                $stmt->execute();
                $stmt->bind_result($storyID);

                if (!$stmt->fetch()) {
                    //Story doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO Story (Title, Body, PublishedTimestamp, ComicURL)
                                  VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $storyTitle, $storyBody, $publishedTimestamp, $comicUrl);
                    $stmt->execute();
                    $storyID = $stmt->insert_id;
                } else {
                    //Story already exists — update
                    $stmt->close();

                    //Update the existing records
                    $stmt = $mysqli->prepare("UPDATE Story
                              SET Body = ?, PublishedTimestamp = ?, ComicURL = ?
                              WHERE StoryID = ?");
                    $stmt->bind_param("ssss", $storyBody, $publishedTimestamp, $comicUrl, $storyID);
                    $stmt->execute();
                }

                // ---------- Insert/Update COMMENT ----------
                $stmt = $mysqli->prepare("SELECT 1 FROM Comment WHERE UserID = ? AND StoryID = ? AND Timestamp = ?");
                $stmt->bind_param("iis", $userID, $storyID, $commentTimestamp);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows === 0) {
                    //Comment doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO Comment (UserID, StoryID, Timestamp, CommentText)
                              VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiss", $userID, $storyID, $commentTimestamp, $commentText);
                    $stmt->execute();
                } else {
                    //Comment already exists — update
                    $stmt->close();

                    //Update the existing comment text
                    $stmt = $mysqli->prepare("UPDATE Comment
                              SET CommentText = ?
                              WHERE UserID = ? AND StoryID = ? AND Timestamp = ?");
                    $stmt->bind_param("siis", $commentText, $userID, $storyID, $commentTimestamp);
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
    <form method = "post" enctype = "multipart/form-data">
        <div class = "input-group mb-3">
            File: <input type = "file" name = "importFile" />
            <br><br>
            <input type ="submit" value ="Upload Data" />
        </div>
    </form>

<?php
//success and fail messages
if($import_attempted){
    if($import_successful){
        ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>Success!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
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

<?php include_once("footer.php"); ?>