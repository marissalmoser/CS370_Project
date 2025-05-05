<?php
include 'header.php';
?>

    <!DOCTYPE html>
    <div class="bg-info-subtle p-4"> <br>
        <div class="container hei">
            <h1>Report 1: <small class="text-body-secondary">User, Story, Comment</small> </h1>
            <br>
            <p>This is the report 1 page. Here you can see user, story, and comment data. It shows all stories and
                whatever comments they have.</p>
            <br>
        </div>

        <?php
        include 'ReportsHeader.php';
        ?>
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
//I keep encountering issues with connecting to mysql. I have reinstalled a few times and made sure things are properly linked up
//I'm overlooking something; the below commented lines are test lines
/*output_table_open();
output_story_row("0", "Test Title", "Test Body", "2020-01-23");
comment_table_open();
output_comment_row("0", "Test Comment", "1", "test comment", "2918-128-12");
user_table_open();
output_user_row("Test user", "Admin", "active", "fake@gmail.com", "2918-128-12");
user_table_close();
output_comment_row("0", "Test user2", "1", "test comment2", "2918-128-12");
user_table_open();
output_user_row("Test user1", "Admin1", "active1", "fake1@gmail.com", "2918-128-12");
user_table_close();
comment_table_close();
output_story_row("1", "Test Title1", "Test Body1", "2020-01-01");*/

$connection_error = false;
$connection_error_message = "";

$conn = mysqli_connect('localhost', 'root', 'root3', 'news');

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
    echo "<thead class='table-info'>
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
    /*echo "<tr class>\n";
    echo "<td>";
    echo "<table id = 'output' class = 'table table-striped' style = 'width: 100%'>\n";
    echo "<thead>\n";
    echo "<tr>\n";
    echo "<th>Display Name</th>\n";
    echo "<th>Role</th>\n";
    echo "<th>Subscription Status</th>\n";
    echo "<th>Email</th>\n";
    echo "<th>Date Joined</th>\n";
    echo "</tr>\n";
    echo "</thead>\n";*/
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
/*
function user_table_close()
{
    echo "</td>\n";
    echo "</table>\n";
}
function output_story_row($id, $title, $body_tex, $published_timestamp, $comic_url = 'N/A')
{
    echo "<tr class>\n";
    echo "<td> $title </td>";
    echo "<td> $body_tex </td>";
    echo "<td> $published_timestamp </td>";
    echo "<td> $comic_url </td>";
    echo "</tr>\n";
}

function output_comment_row($id, $user, $story, $comment_text, $time_stamp)
{
    echo "<p> entered </p>";
    echo "<tr class>\n";
    echo "<td>$user</td>";
    echo "<td>$comment_text</td>";
    echo "<td>$time_stamp</td>";
    echo "</tr>\n";
}

function output_user_row($display_name, $role, $subscription_status, $email, $date_joined)
{
    echo "<tr class>\n";
    echo "<td>$display_name</td>";
    echo "<td>$role</td>";
    echo "<td>$subscription_status</td>";
    echo "<td>$email</td>";
    echo "<td>$date_joined</td>";
    echo "</tr>\n";
}*/

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
            $saved_id = $row[0];

            // Run a subquery and find all comments on this story
            $subquery = $conn->prepare('SELECT * FROM comment WHERE StoryID = ?');
            $subquery->bind_param("i", $saved_id);
            $subquery->execute();
            $commentResult = $subquery->get_result();

            // If there are results
            if(mysqli_num_rows($commentResult) > 0)
            {
                comment_table_open();

                foreach($commentResult as $comment)
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

                comment_table_close();


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