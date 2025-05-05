<?php include 'header.php'; ?>

<div class="bg-info-subtle p-4"> <br>
    <div class="container hei">
        <h1>Report 3: <small class="text-body-secondary">Location, Tag, Event</small> </h1>
        <br>
        <p>This is the report 3 page. This report displays each event in its own table, with associated location and
            tag info shown in sub-tables.</p>
        <br>
    </div>

    <?php
    include 'ReportsHeader.php';
    ?>
</div>

<?php
$conn = mysqli_connect("localhost", "root", "root3", "news");

if (!$conn) {
    die("<div class='alert alert-danger'>Connection Error: " . mysqli_connect_error() . "</div>");
}

$query = "
    SELECT 
        e.EventID, e.EventName, e.Description, e.Sponsor, e.EventStart, e.EventEnd,
        l.LocationName, l.LocationAddress, l.DateAdded AS LocationDate
        
    FROM Event e
    LEFT JOIN Location l ON e.LocationID = l.LocationID
    ORDER BY e.EventStart DESC
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0)
{
    while ($row = mysqli_fetch_assoc($result))
    {
        echo "<div class='mb-4'>";

        // Main event table
        echo "<table class='table table-bordered'>\n";
        echo "<thead class='table-info'>
                <tr><th colspan='2'>Event: {$row['EventName']}</th></tr>
              </thead>\n";
        echo "<tbody>
                <tr><th style='width: 20%'>Sponsor</th><td>{$row['Sponsor']}</td></tr>
                <tr><th>Description</th><td>{$row['Description']}</td></tr>
                <tr><th>Start Time</th><td>{$row['EventStart']}</td></tr>
                <tr><th>End Time</th><td>{$row['EventEnd']}</td></tr>
              </tbody>
              </table>";

        // Location subtable
        echo "<h6>Location Info</h6>";
        echo "<table class='table table-sm table-striped table-bordered w-75'>
                <thead><tr><th>Location Name</th><th>Address</th><th>Date Added</th></tr></thead>
                <tbody><tr>
                    <td>{$row['LocationName']}</td>
                    <td>{$row['LocationAddress']}</td>
                    <td>{$row['LocationDate']}</td>
                </tr></tbody>
              </table>";

        echo "</div>";

        // Tag subtable
        echo "<h6>Tag Info</h6>";
        ?>
        <table class='table table-sm table-striped table-bordered w-50 mb-3'>
            <thead>
                <tr><th>Tag Name</th><th>Date Added</th></tr>
            </thead>

            <?php

            //get tags
            $query = "
                SELECT 
                    t.DisplayName AS TagName, t.DateAdded AS TagCreationDate
                    
                FROM Event e
                LEFT JOIN EventTag et ON e.EventID = et.EventID
                LEFT JOIN Tag t ON et.TagID = t.TagID
                WHERE e.EventID = '{$row['EventID']}'
            ";
            $tagResult = mysqli_query($conn, $query);

            if ($tagResult && mysqli_num_rows($tagResult) > 0)
            {
                while ($tagRow = mysqli_fetch_assoc($tagResult))
                {
                    echo "<tr><td>{$tagRow['TagName']}</td><td>{$tagRow['TagCreationDate']}</td></tr>";
                }
            }

            ?>
                
        </table>

        <?php
    }
}
else
{
    echo "<div class='alert alert-warning'>No events found.</div>";
}
?>

<?php include 'footer.php'; ?>
