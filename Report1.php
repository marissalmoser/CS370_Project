<?php
include 'header.php';
?>

    <!DOCTYPE html>

    <div class="container">
        <h1>Story Comments</h1>
        <p>This page reports on all comments on this site. It shows all stories and whatever comments they have.</p>
    </div>

<!--example table-->
   <!-- <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Title</th>
            <th scope="col">Body Text</th>
            <th scope="col">Published Timestamp</th>
            <th scope="col">Comic URL</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>John</td>
            <td>Doe</td>
            <td>@social</td>
        </tr>
        </tbody>
    </table>-->

<?php

$connection_error = false;
$connection_error_message = "";

$conn = mysqli_connect('localhost', 'root', 'SQLP@ss', 'news');

if(!$conn)
{
    echo 'Connection Error: ' . mysqli_connect_error();
    $connection_error = true;
}

$query_get_all_stories = 'SELECT * FROM story';

?>


<?php

// These functions shouldn't need to be messed with, unless you would like to change the style
function story_table($row)
{
    echo "<div class = 'mb-4'>";

   echo "<table id = 'output' class = 'table table-bordered' style = 'width: 100%'>\n";
    echo "<thead class='table-primary'>
                <tr><th colspan='2'>Story Title: {$row[1]}</th></tr>
              </thead>\n";
    echo "<tbody>
                <tr><th style='width: 20%'>Body Text</th><td>{$row[2]}</td></tr>
                <tr><th>Published Timestamp</th><td>{$row[3]}</td></tr>
                <tr><th>Comic URL</th><td>{$row[4]}</td></tr>
              </tbody>
    </table>";
       /*echo "<tr>\n";
           echo "<th>Article Title</th>\n";
           echo "<th>Body Text</th>\n";
           echo "<th>Published Timestamp</th>\n";
           echo "<th>Comic URL</th>\n";
       echo "</tr>\n";
       echo "</thead>\n";*/

}

function comment_table($row, $user)
{
    echo "<p></p>";
    echo "
                <tbody>
                    <tr>
                        <td>{$user['DisplayName']}</td>
                        <td>{$row['CommentText']}</td>
                        <td>{$row['Timestamp']}</td>
                    </tr>";
    /*echo "<tr class>\n";
    echo "<td>";
    echo "<table id = 'output' class = 'table table-striped' style = 'width: 100%'>\n";
        echo "<thead>\n";
            echo "<tr>\n";
            echo "<th>User</th>\n";
            echo "<th>Comment Text</th>\n";
            echo "<th>TimeStamp</th>\n";
            echo "</tr>\n";
        echo "</thead>\n";*/
}
function display_user_rows($row, $userComments)
{
    echo "<tr>
                    <td>{$row['DisplayName']}</td>
                    <td>{$row['Role']}</td>
                    <td>{$row['SubscriptionStatus']}</td>
                    <td>{$row['Email']}</td>
                    <td>{$row['DateJoined']}</td>
                </tr>";
    echo "<h6>Comments on this Story</h6>";
    echo "<table  id = 'comment' class='table table-sm table-striped table-bordered w-50 mb-3'>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Comment Text</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>";



}

function open_user_table()
{
    echo "<tr> 
    <h6>Users who Commented on this Story</h6>
    <table  id = 'user' class='table table-sm table-striped table-bordered w-75'>
                <thead>
                    <tr>
                        <th>Display Name</th>
                        <th>Role</th>
                        <th>Subscription Status</th>
                        <th>Email</th>
                        <th>Date Joined</th>
                    </tr>
                </thead>";
}

function user_table($row)
{
    echo "<tr> 
    <h6>User Information</h6>
    <table  id = 'user' class='table table-sm table-striped table-bordered w-75'>
                <thead>
                    <tr>
                        <th>Display Name</th>
                        <th>Role</th>
                        <th>Subscription Status</th>
                        <th>Email</th>
                        <th>Date Joined</th>
                    </tr>
                </thead>
               <tr>
                    <td>{$row['DisplayName']}</td>
                    <td>{$row['Role']}</td>
                    <td>{$row['SubscriptionStatus']}</td>
                    <td>{$row['Email']}</td>
                    <td>{$row['DateJoined']}</td>
                </tr>
              </table id = 'user'></tr>";
}

function comment_table_open()
{
    echo "<h6>Article Comments</h6>";
    echo "<table  id = 'comment' class='table table-sm table-striped table-bordered w-50 mb-3'>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Comment Text</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>";
}
function output_table_close()
{
    echo "</div>";
}

function comment_table_close()
{
    echo "</tbody>\n";
    echo "</table>\n";
}


// Here is where stuff may need to be changed

//If there's a connection error, display it
if($connection_error)
{
    output_error("Database Connection Error! " . $connection_error_message);
}
// Otherwise, try to display the table
else
{
    // Go through using the SELECT * FROM story query
    // Save the results
    $result = mysqli_query($conn, $query_get_all_stories);

    // If there aren't results, throw an error
    if(!$result) {
        // If it's a connection error, throw it
        if(mysqli_errno($conn))
        {
            output_error("Database retrieval Failed! " . mysqli_error($conn));
        }
        // Otherwise, display there is no data
        else
        {
            echo "No story data found!\n";
        }
    }

    // Otherwise, if there are results
    else
    {

        // While there are results from $query_get_all_stories
        while($row = $result ->fetch_array())
        {
            story_table($row);

            // Get the StoryID and save it
            $story_id = $row[0];




            // Run a subquery and find all unique users who commented on this story
            $unique_users = $conn->prepare('SELECT DISTINCT UserID FROM comment WHERE StoryID = ?');
            $unique_users->bind_param("i", $story_id);
            $unique_users->execute();
            $unique_users_result = $unique_users->get_result();

            // If there are results
            if(mysqli_num_rows($unique_users_result) > 0)
            {
                echo "<p> Found at least one user</p>";

                open_user_table();

                $users = $conn->prepare('SELECT * FROM user WHERE UserID = ?');
                $users->bind_param("i", $user_id);
                $users->execute();
                $user_results = $users->get_result();

                $user_comments = $conn->prepare('SELECT * FROM comment INNER JOIN user WHERE comment.UserID = user.userID AND comment.StoryID = ?');
                $user_comments->bind_param("i", $story_id);
                $user_comments->execute();
                $user_comments_result = $user_comments->get_result();

                foreach($user_results as $curr_user)
                {
                    display_user_rows($curr_user, $user_comments);
                }
                /*
                comment_table_open();


                foreach($unique_users_result as $comment)
                {
                // While there are results from $subquery
                    //Output the data from this current row. The IDs are there to prevent data shifting errors



                    $user_query = $conn->prepare('SELECT * FROM user WHERE UserID = ?');
                    $user_query->bind_param("i", $comment['UserID']);
                    $user_query->execute();
                    $user = $user_query->get_result();

                    if(mysqli_num_rows($user) > 0)
                    {

                        foreach($user as $userRow)
                        {
                            comment_table($comment, $userRow);

                            user_table($userRow);
                        }

                    }
                    else
                    {
                        echo '<p>Cannot find users for comments with userID </p>';

                    }


                }

                comment_table_close();*/


            }
            else
            {
                echo "<p> No comments on this article </p>";
            }

        }

        // Close the output table
        output_table_close();
    }
}

?>
<?php include 'footer.php'; ?>