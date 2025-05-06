<?php include 'header.php'; ?>

<div class="bg-info-subtle p-4"> <br>
    <div class="container hei">
        <h1>Report 1: <small class="text-body-secondary">User, Story, Comment</small></h1>
        <br>
        <p>This is the report 1 page. Here you can see user, story, and comment data. It shows all stories and whatever comments they have.</p>
        <br>
    </div>
    <?php include 'ReportsHeader.php'; ?>
</div><br>

<div class="container">
    <?php
    $conn = mysqli_connect('localhost', 'root', 'rootpass', 'news');
    if (!$conn) {
        echo "<div class='alert alert-danger'>Connection Error: " . mysqli_connect_error() . "</div>";
    } else {
        $result = mysqli_query($conn, "SELECT * FROM story");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='mb-5 p-3 border rounded'>";
            echo "<h2 class='mb-2'>{$row['Title']}</h2>";
            echo "<p><strong>Body:</strong> {$row['Body']}</p>";
            echo "<p><strong>Published:</strong> {$row['PublishedTimestamp']}</p>";
            echo "<p><strong>Comic URL:</strong> <a href='{$row['ComicURL']}' target='_blank'>{$row['ComicURL']}</a></p>";

            $storyID = $row['StoryID'];

            $users = $conn->prepare("SELECT DISTINCT u.* 
                                 FROM user u 
                                 JOIN comment c ON u.UserID = c.UserID 
                                 WHERE c.StoryID = ?");
            $users->bind_param("i", $storyID);
            $users->execute();
            $user_result = $users->get_result();

            if ($user_result->num_rows > 0) {
                echo "<h5 class='mt-4'>Users who Commented on this Story</h5>";
                echo "<table class='table table-striped'>
                    <thead><tr>
                        <th>Display Name</th>
                        <th>Role</th>
                        <th>Subscription Status</th>
                        <th>Email</th>
                        <th>Date Joined</th>
                    </tr></thead><tbody>";
                while ($u = mysqli_fetch_assoc($user_result)) {
                    echo "<tr>
                        <td>{$u['DisplayName']}</td>
                        <td>{$u['Role']}</td>
                        <td>{$u['SubscriptionStatus']}</td>
                        <td>{$u['Email']}</td>
                        <td>{$u['DateJoined']}</td>
                      </tr>";
                }
                echo "</tbody></table>";
            }

            $comments = $conn->prepare("SELECT c.*, u.DisplayName 
                                    FROM comment c 
                                    JOIN user u ON c.UserID = u.UserID 
                                    WHERE c.StoryID = ?");
            $comments->bind_param("i", $storyID);
            $comments->execute();
            $comment_result = $comments->get_result();

            if ($comment_result->num_rows > 0) {
                echo "<h5 class='mt-4'>Comments</h5>";
                echo "<table class='table table-sm table-striped'>
                    <thead><tr>
                        <th>User</th>
                        <th>Comment Text</th>
                        <th>Timestamp</th>
                    </tr></thead><tbody>";
                while ($c = mysqli_fetch_assoc($comment_result)) {
                    echo "<tr>
                        <td>{$c['DisplayName']}</td>
                        <td>{$c['CommentText']}</td>
                        <td>{$c['Timestamp']}</td>
                      </tr>";
                }
                echo "</tbody></table>";
            }

            echo "</div>";
        }
        mysqli_close($conn);
    }
    ?>
</div>

<?php include 'footer.php'; ?>
