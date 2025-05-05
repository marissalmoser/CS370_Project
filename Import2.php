<?php
include 'header.php';
?>

<!DOCTYPE html>

<!--header-->
<div class="bg-primary-subtle p-4"> <br>
    <div class="container hei">
        <h1>Import Process 2: <small class="text-body-secondary">Story, Author, Tag, and Advertisement </small> </h1>
        <br>
        <p>This is the import 2 page. Here you can import story, author, tag, and advertisement data. This import
            process reads a CSV file line by line, extracting unnormalized data from our news schema. It checks each
            record for existing entries in the database, inserting new ones or updating existing ones as needed, while
            maintaining relationships between tables through foreign keys. The process also ensures linking tables are
            correctly populated to reflect their associations, visible in the reports generated after each import.</p>
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

    $mysqli = new mysqli("localhost", "root", "rootpass", "news");

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
                    $storyTitle, //Story
                    $storyBody,
                    $publishedTimestamp,
                    $comicURL,
                    $penName, //author
                    $bio,
                    $degree,
                    $displayName, //tag
                    $dateAdded,
                    $adName, //ad
                    $adType,
                    $startDate,
                    $endDate,
                    $contentURL
                    ) = $data;

                // skip blank or all-empty rows
                if (empty(array_filter($data))) {
                    continue;
                }

                // ---------- Insert/Update STORY  ----------
                $stmt = $mysqli->prepare("SELECT StoryID FROM story WHERE Title = ?");
                $stmt->bind_param("s", $storyTitle);
                $stmt->execute();
                $stmt->bind_result($storyID);

                if (!$stmt->fetch()) {
                    //Story doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO story (Title, Body, PublishedTimestamp, ComicURL)
                                  VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $storyTitle, $storyBody, $publishedTimestamp, $comicURL);
                    $stmt->execute();
                    $storyID = $stmt->insert_id;
                } else {
                    //Story already exists — update
                    $stmt->close();

                    //Update the existing records
                    $stmt = $mysqli->prepare("UPDATE story
                              SET Body = ?, PublishedTimestamp = ?, ComicURL = ?
                              WHERE StoryID = ?");
                    $stmt->bind_param("ssss", $storyBody, $publishedTimestamp, $comicURL, $storyID);
                    $stmt->execute();
                }

                // ---------- Insert/Update AUTHOR  ----------
                $stmt = $mysqli->prepare("SELECT AuthorID FROM author WHERE PenName = ?");
                $stmt->bind_param("s", $penName);
                $stmt->execute();
                $stmt->bind_result($authorID);

                if (!$stmt->fetch()) {
                    //Author doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO author (PenName, Bio, Degree, Birthday)
                                  VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $penName, $bio, $degree, $birthday);
                    $stmt->execute();
                    $authorID = $stmt->insert_id;
                } else {
                    //Author already exists — update
                    $stmt->close();

                    //Update the existing records
                    $stmt = $mysqli->prepare("UPDATE author
                              SET Bio = ?, Degree = ?, Birthday = ?
                              WHERE AuthorID = ?");
                    $stmt->bind_param("sssi", $bio, $degree, $birthday, $authorID);
                    $stmt->execute();
                }

                // ---------- Insert/Update TAG ----------
                $stmt = $mysqli->prepare("SELECT TagID FROM tag WHERE DisplayName = ?");
                $stmt->bind_param("s", $displayName);
                $stmt->execute();
                $stmt->bind_result($tagID);

                if (!$stmt->fetch()) {
                    //tag doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO tag 
                            (DisplayName, DateAdded)
                            VALUES (?, ?)");
                    $stmt->bind_param("ss", $displayName, $dateAdded);
                    $stmt->execute();
                    $tagID = $stmt->insert_id;
                } else {
                    //tag already exists — update
                    $stmt->close();

                    //Update the existing records
                    $stmt = $mysqli->prepare("UPDATE tag
                                  SET DateAdded = ?
                                  WHERE TagID = ?");
                    $stmt->bind_param("si", $dateAdded, $tagID);
                    $stmt->execute();
                }

                // ---------- Insert/Update ADVERTISEMENT  ----------
                $stmt = $mysqli->prepare("SELECT AdID FROM advertisement WHERE AdName = ?");
                $stmt->bind_param("s", $adName);
                $stmt->execute();
                $stmt->bind_result($adID);

                if (!$stmt->fetch()) {
                    //Story doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO advertisement (StartDate, EndDate, AdType, ContentURL, AdName)
                                      VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $startDate, $endDate, $adType, $contentURL, $adName);
                    $stmt->execute();
                    $adID = $stmt->insert_id;
                } else {
                    //Story already exists — update
                    $stmt->close();

                    //Update the existing records
                    $stmt = $mysqli->prepare("UPDATE advertisement
                                  SET StartDate = ?, EndDate = ?, AdType = ?, ContentURL = ?
                                  WHERE adID = ?");
                    $stmt->bind_param("ssssi", $startDate, $endDate, $adType, $contentURL, $adID);
                    $stmt->execute();
                }

                // ---------- Insert/Update AD STORY ----------
                //check for data's existence
                $stmt = $mysqli->prepare("SELECT 1 FROM advertisementstory WHERE StoryID = ? AND AdvertisementID = ?");
                $stmt->bind_param("ii", $storyID, $adID);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows === 0) {
                    //event tag doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO advertisementstory (StoryID, AdvertisementID)
                                  VALUES (?, ?)");
                    $stmt->bind_param("ii", $storyID, $adID);
                    $stmt->execute();
                }

                // ---------- Insert/Update STORY AUTHOR ----------
                //check for data's existence
                $stmt = $mysqli->prepare("SELECT 1 FROM storyauthor WHERE StoryID = ? AND AuthorID = ?");
                $stmt->bind_param("ii", $storyID, $authorID);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows === 0) {
                    //event tag doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO storyauthor (StoryID, AuthorID)
                                  VALUES (?, ?)");
                    $stmt->bind_param("ii", $storyID, $authorID);
                    $stmt->execute();
                }

                // ---------- Insert/Update STORY TAG ----------
                //check for data's existence
                $stmt = $mysqli->prepare("SELECT 1 FROM storytag WHERE TagID = ? AND StoryID = ?");
                $stmt->bind_param("ii", $tagID, $storyID);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows === 0) {
                    //event tag doesn't exist, insert it
                    $stmt->close();
                    $stmt = $mysqli->prepare("INSERT INTO storytag (StoryID, TagID)
                                  VALUES (?, ?)");
                    $stmt->bind_param("ii", $storyID, $tagID);
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
                <input class="btn btn-light fs-6" type ="submit" value ="Upload Data" />
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
                    <a type="button" class="btn btn-lg btn-primary" href="./Report2.php">View Report 2</a>
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
    <div class="container d-grid gap-2" >
        <a type="button" class="btn btn-lg btn-light" href="./index.php">Return Home</a>
    </div>
    <br><br>

<?php include_once("footer.php"); ?>