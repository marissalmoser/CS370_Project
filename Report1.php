<?php
include 'header.php';
?>

    <!DOCTYPE html>

    <div class="container">
        <h1>Story Comments</h1>
        <p>This page reports on all comments on this site. It shows all stories and whatever comments they have.</p>
    </div>

<!--example table-->
    <table class="table table-striped">
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
    </table>

<?php
$connection_error = false;
$connection_error_message = "";

$con = mysqli_connect("localhost", "root", "SQLP@ss");

if(!$con) {
    $connection_error = true;
    $connection_error_message = "Failed to connect to MySql " . mysqli_connect_error();
}

function output_error($title, $error)
{
    echo "<span = \"color: red;\">\n";
    echo "<h2>" . $title . "</h2>\n";
    echo "<h4>" . $error . "</h4>\n";
    echo "</span>\n";
}

?>
    <style>
        .pizzaDataTable{
            font-family : Calibri, monospace;
            font-size : larger;
            border-spacing : 0
        }
        .pizzaDataHeaderRow td{
            font-weight : bold padding-right : 20px

        }
        .pizzaDataRow td
        {
            border-bottom : 1px solid #888888; padding-left : 10px;
        }
        .pizzaDataDetailsCell
        {
            padding-left: 20px; font-size : medium;
        }
        .pizzaDataTable tr:nth-child(2n+2)
        {
            background-color : #cccccc;
        }
    </style>
<?php
    if($connection_error) {
        output_error("Database Connection Error! " . $connection_error_message);
    }
    else
    {
        function output_table_open()
    {
        echo "<table\n";
        echo "<tr>\n";
        echo "<td>Title</td>\n";
        echo "<td>Body Text</td>\n";
        echo "<td>Published Timestamp</td>\n";
        echo "<td>Comic URL</td>\n";
        echo "</tr>";
    }

    function output_table_close()
    {
        echo "</table>\n";
    }

    function output_person_row($name, $age, $gender)
    {
        echo "<tr class>\n";
        echo "<td> . $name . </td>";
        echo "<td>" . $age . "</td>";
        echo "<td>" . $gender . "</td>";
        echo "</tr>\n";
    }



    $query = "SELECT * FROM Author";
    $result = mysqli_query($con, $query);
    // used for selects, inserts, updates, and deletes

    if(!$result) {
    if(mysqli_errno($con)) {
        output_error("Database retrieval Failed! " . mysqli_error($con));
    }
    else
    {
    echo "No pizza data found!\n";
    }
    }
    else
    {
    output_table_open();

    $last_name = null;
    $pizzas = array();
    $pizzerias = array();

    while($row = $result ->fetch_array())
    {
    if($last_name != $row[" name"]) {
    if ($last_name != null)
    {
    output_person_details_row($pizzas, $pizzerias);
    }
    output_person_row($row[" name"], $row[" age"], $row[" gender"]);

    $pizzas[] = $row[" pizza"];
    $pizzas[] = $row[" pizzeria"];

    $last_name = $row[" name"];

    }


    }
    output_person_details_row($pizzas, $pizzerias);

    output_table_close();
    }
    }

?>
<?php include 'footer.php'; ?>