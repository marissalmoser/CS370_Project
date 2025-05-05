<?php include 'header.php'; ?>

<div class="bg-info-subtle p-4"> <br>
    <div class="container hei">
        <h1>Report 2: <small class="text-body-secondary">Story, Author, Tag, and Advertisement</small> </h1>
        <br>
        <p>This is the report 2 page. Here you can see story, author, tag, and advertisement data. It shows all stories and
            whatever other data they have.</p>
        <br>
    </div>

    <?php
    include 'ReportsHeader.php';
    ?>
</div><br>

<div class="container">

    <?php
    $conn = mysqli_connect("localhost", "root", "root3", "news");
    if (!$conn) {
        die("<div class='alert alert-danger'>Connection Error: " . mysqli_connect_error() . "</div>");
    }

    $story_query = "SELECT * FROM Story";
    $story_result = mysqli_query($conn, $story_query);

    if ($story_result && mysqli_num_rows($story_result) > 0) {
        while ($story = mysqli_fetch_assoc($story_result)) {
            $storyID = $story['StoryID'];
            echo "<div class='mb-5 p-3 border rounded'>";

            // Story Header
            echo "<h2 class='mb-2'>{$story['Title']}</h2>";
            echo "<p><strong>Body:</strong> {$story['Body']}</p>";
            echo "<p><strong>Published:</strong> {$story['PublishedTimestamp']}</p>";
            echo "<p><strong>Comic URL:</strong> <a href='{$story['ComicURL']}' target='_blank'>{$story['ComicURL']}</a></p>";

            // ---- TAGS ----
            $tag_query = $conn->prepare("
                SELECT t.DisplayName, t.DateAdded
                FROM Tag t
                JOIN StoryTag st ON t.TagID = st.TagID
                WHERE st.StoryID = ?
            ");
            $tag_query->bind_param("i", $storyID);
            $tag_query->execute();
            $tag_result = $tag_query->get_result();

            if ($tag_result && mysqli_num_rows($tag_result) > 0) {
                echo "<h5 class='mt-4'>Tags</h5>";
                echo "<table class='table table-striped'>
                        <thead><tr>
                            <th>Display Name</th>
                            <th>Date Added</th>
                        </tr></thead><tbody>";
                while ($tag = mysqli_fetch_assoc($tag_result)) {
                    echo "<tr>
                        <td>{$tag['DisplayName']}</td>
                        <td>{$tag['DateAdded']}</td>
                    </tr>";
                }
                echo "</tbody></table>";
            }

            // ---- ADS ----
            $ad_query = $conn->prepare("
                SELECT a.AdName, a.AdType, a.StartDate, a.EndDate, a.ContentURL
                FROM Advertisement a
                JOIN AdvertisementStory ads ON a.AdID = ads.AdvertisementID
                WHERE ads.StoryID = ?
            ");
            $ad_query->bind_param("i", $storyID);
            $ad_query->execute();
            $ad_result = $ad_query->get_result();

            if ($ad_result && mysqli_num_rows($ad_result) > 0) {
                echo "<h5 class='mt-4'>Advertisements</h5>";
                echo "<table class='table table-striped'>
                        <thead><tr>
                            <th>Ad Name</th>
                            <th>Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Content</th>
                        </tr></thead><tbody>";
                while ($ad = mysqli_fetch_assoc($ad_result)) {
                    echo "<tr>
                        <td>{$ad['AdName']}</td>
                        <td>{$ad['AdType']}</td>
                        <td>{$ad['StartDate']}</td>
                        <td>{$ad['EndDate']}</td>
                        <td><a href='{$ad['ContentURL']}' target='_blank'>View Ad</a></td>
                    </tr>";
                }
                echo "</tbody></table>";
            }

            // ---- AUTHOR ----
            $author_query = $conn->prepare("
                SELECT a.PenName, a.Bio, a.Degree
                FROM Author a
                JOIN StoryAuthor sa ON a.AuthorID = sa.AuthorID
                WHERE sa.StoryID = ?
            ");
            $author_query->bind_param("i", $storyID);
            $author_query->execute();
            $author_result = $author_query->get_result();

            if ($author_result && mysqli_num_rows($author_result) > 0) {
                echo "<h5 class='mt-4'>Author</h5>";
                echo "<table class='table table-striped'>
                        <thead><tr>
                            <th>Pen Name</th>
                            <th>Bio</th>
                            <th>Degree</th>
                        </tr></thead><tbody>";
                while ($author = mysqli_fetch_assoc($author_result)) {
                    echo "<tr>
                        <td>{$author['PenName']}</td>
                        <td>{$author['Bio']}</td>
                        <td>{$author['Degree']}</td>
                    </tr>";
                }
                echo "</tbody></table>";
            }

            echo "</div>"; // Close story block
        }
    } else {
        echo "<div class='alert alert-info'>No stories found.</div>";
    }

    mysqli_close($conn);
    ?>

</div>

<?php include 'footer.php'; ?>
