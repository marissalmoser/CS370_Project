<?php include 'header.php'; ?>

<div class="container">
    <h1>Event Report</h1>
    <p>This report displays each event in its own table, with associated location and tag info shown in subtables.</p>
</div>

<?php
$conn = mysqli_connect("localhost", "root", "sqlpassword_1", "news", 3300);

if (!$conn) {
    die("<div class='alert alert-danger'>Connection Error: " . mysqli_connect_error() . "</div>");
}

$query = "
    SELECT 
        e.EventID, e.EventName, e.Description, e.Sponsor, e.EventStart, e.EventEnd,
        t.DisplayName AS TagName, t.DateAdded AS TagDate,
        l.LocationName, l.LocationAddress, l.DateAdded AS LocationDate
    FROM Event e
    LEFT JOIN Tag t ON e.TagID = t.TagID
    LEFT JOIN Location l ON e.LocationID = l.LocationID
    ORDER BY e.EventStart DESC
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='mb-4'>";

        // Main event table
        echo "<table class='table table-bordered'>\n";
        echo "<thead class='table-primary'>
                <tr><th colspan='2'>Event: {$row['EventName']}</th></tr>
              </thead>\n";
        echo "<tbody>
                <tr><th style='width: 20%'>Sponsor</th><td>{$row['Sponsor']}</td></tr>
                <tr><th>Description</th><td>{$row['Description']}</td></tr>
                <tr><th>Start</th><td>{$row['EventStart']}</td></tr>
                <tr><th>End</th><td>{$row['EventEnd']}</td></tr>
              </tbody>
              </table>";

        // Tag subtable
        echo "<h6>Tag Info</h6>";
        echo "<table class='table table-sm table-striped table-bordered w-50 mb-3'>
                <thead><tr><th>Tag Name</th><th>Date Added</th></tr></thead>
                <tbody><tr><td>{$row['TagName']}</td><td>{$row['TagDate']}</td></tr></tbody>
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
    }
} else {
    echo "<div class='alert alert-warning'>No events found.</div>";
}
?>

<?php include 'footer.php'; ?>
