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
function output_table_open()
{
   echo "<table id = 'output' class = 'table table-striped' style = 'width: 100%'>\n";
       echo "<thead>\n";
       echo "<tr>\n";
           echo "<th>Article Title</th>\n";
           echo "<th>Body Text</th>\n";
           echo "<th>Published Timestamp</th>\n";
           echo "<th>Comic URL</th>\n";
       echo "</tr>\n";
       echo "</thead>\n";

}

function comment_table_open()
{
    echo "<tr class>\n";
    echo "<td>";
    echo "<table id = 'output' class = 'table table-striped' style = 'width: 100%'>\n";
        echo "<thead>\n";
            echo "<tr>\n";
            echo "<th>User</th>\n";
            echo "<th>Comment Text</th>\n";
            echo "<th>TimeStamp</th>\n";
            echo "</tr>\n";
        echo "</thead>\n";
}

function user_table_open()
{
    echo "<tr class>\n";
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
    echo "</thead>\n";
}


function output_table_close()
{
    echo "</td>\n";
    echo "</table>\n";
}

function comment_table_close()
{
    echo "</td>\n";
    echo "</table>\n";
}

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
        // Open the output table
        output_table_open();

        // While there are results from $query_get_all_stories
        while($row = $result ->fetch_array())
        {
            // Output the data from this current row
            // ID is there to prevent data shifting errors
            output_story_row($row[" id"], $row[" title"], $row[" body text"], $row[" published_timestamp"],
                $row[" comic_url"]);

            // Run a subquery and find all comments on this story
            $subquery = 'SELECT * FROM comment WHERE story_id = $row[" story_id"]';;
            $commentResult = mysqli_query($conn, $subquery);

            // If there are results
            if($commentResult)
            {
                // Open the comment table
                comment_table_open();

                // While there are results from $subquery
                while($row = $commentResult ->fetch_array())
                {
                    // Output the data from this current row. The IDs are there to prevent data shifting errors

                    $user = 'SELECT * FROM user WHERE user_id = $row[" user_id"]';
                    output_comment_row($row [" id"], 'SELECT display name FROM $user',
                        $row[" story id"], $row[ "comment text"], $row[ "published timestamp"]);

                    user_table_open();

                    // Using individual select statements here as there should be one user per comment
                    output_user_row(
                            'SELECT DisplayName FROM $user',
                            'SELECT Role FROM $user',
                            'SELECT SubscriptionStatus FROM $user',
                            'SELECT Email FROM $user',
                            'SELECT DateJoined FROM $user'
                    );

                    // Close the user tab;e
                    user_table_close();

                }

                //Close the comment table
                comment_table_close();
            }

        }

        // Close the output table
        output_table_close();
    }
}

?>
<?php include 'footer.php'; ?>