<?php include 'header.php'; ?>

<div class="container">
    <h1>Event Listings</h1>
    <p>This page displays all events with their associated tag and location information.</p>
</div>

<?php
$conn = mysqli_connect("localhost", "root", "sqlpassword_1", "news", 3300);

if (!$conn) {
    die("<div class='alert alert-danger'>Connection Error: " . mysqli_connect_error() . "</div>");
}

// --- Functions to render HTML tables ---
function output_event_table_open() {
    echo "<table class='table table-striped' style='width: 100%'>\n";
    echo "<thead><tr>
        <th>Event Name</th>
        <th>Description</th>
        <th>Sponsor</th>
        <th>Start</th>
        <th>End</th>
        <th>Tag</th>
        <th>Location</th>
    </tr></thead>\n";
}

function output_event_row($event) {
    echo "<tr>
        <td>{$event['EventName']}</td>
        <td>{$event['Description']}</td>
        <td>{$event['Sponsor']}</td>
        <td>{$event['EventStart']}</td>
        <td>{$event['EventEnd']}</td>
        <td>{$event['TagDisplayName']}</td>
        <td>{$event['LocationName']}<br><small>{$event['LocationAddress']}</small></td>
    </tr>\n";
}

function output_event_table_close() {
    echo "</table>\n";
}

// --- Query to get all events with JOINs for Tag and Location ---
$query = "
    SELECT 
        e.EventID, e.Sponsor, e.TagID, e.LocationID, e.EventStart, e.EventEnd, e.Description, e.EventName,
        t.DisplayName AS TagDisplayName, t.DateAdded AS TagDateAdded,
        l.LocationName, l.LocationAddress, l.DateAdded AS LocationDateAdded
    FROM Event e
    LEFT JOIN Tag t ON e.TagID = t.TagID
    LEFT JOIN Location l ON e.LocationID = l.LocationID
";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<div class='alert alert-danger'>Database Query Failed: " . mysqli_error($conn) . "</div>";
} else {
    output_event_table_open();

    while ($event = mysqli_fetch_assoc($result)) {
        output_event_row($event);
    }

    output_event_table_close();
}
?>

<?php include 'footer.php'; ?>
