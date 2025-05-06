<?php include 'header.php'; ?>

<div class="bg-info-subtle p-4"> <br>
    <div class="container hei">
        <h1>Report 3: <small class="text-body-secondary">Location, Tag, Event</small></h1>
        <br>
        <p>This is the report 3 page. This report displays each event in its own block, with associated location and tag info shown in sub-tables.</p>
        <br>
    </div>
    <?php include 'ReportsHeader.php'; ?>
</div><br>

<div class="container">
    <?php
    $conn = mysqli_connect("localhost", "root", "rootpass", "news");

    if (!$conn) {
        echo "<div class='alert alert-danger'>Connection Error: " . mysqli_connect_error() . "</div>";
    } else {
        $query = "
        SELECT 
            e.EventID, e.EventName, e.Description, e.Sponsor, e.EventStart, e.EventEnd,
            l.LocationName, l.LocationAddress, l.DateAdded AS LocationDate
        FROM Event e
        LEFT JOIN Location l ON e.LocationID = l.LocationID
        ORDER BY e.EventStart DESC
    ";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='mb-5 p-3 border rounded'>";
                echo "<h2 class='mb-2'>Event: {$row['EventName']}</h2>";
                echo "<p><strong>Sponsor:</strong> {$row['Sponsor']}</p>";
                echo "<p><strong>Description:</strong> {$row['Description']}</p>";
                echo "<p><strong>Start Time:</strong> {$row['EventStart']}</p>";
                echo "<p><strong>End Time:</strong> {$row['EventEnd']}</p>";

                echo "<h5 class='mt-4'>Location Info</h5>";
                echo "<table class='table table-sm table-striped'>
                    <thead><tr><th>Location Name</th><th>Address</th><th>Date Added</th></tr></thead>
                    <tbody><tr>
                        <td>{$row['LocationName']}</td>
                        <td>{$row['LocationAddress']}</td>
                        <td>{$row['LocationDate']}</td>
                    </tr></tbody>
                  </table>";

                echo "<h5 class='mt-4'>Tag Info</h5>";
                echo "<table class='table table-sm table-striped'>
                    <thead><tr><th>Tag Name</th><th>Date Added</th></tr></thead><tbody>";

                $tagQuery = "
                SELECT t.DisplayName AS TagName, t.DateAdded AS TagCreationDate
                FROM EventTag et
                JOIN Tag t ON et.TagID = t.TagID
                WHERE et.EventID = {$row['EventID']}
            ";
                $tagResult = mysqli_query($conn, $tagQuery);

                if ($tagResult && mysqli_num_rows($tagResult) > 0) {
                    while ($tag = mysqli_fetch_assoc($tagResult)) {
                        echo "<tr><td>{$tag['TagName']}</td><td>{$tag['TagCreationDate']}</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No tags found.</td></tr>";
                }

                echo "</tbody></table>";
                echo "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>No events found.</div>";
        }

        mysqli_close($conn);
    }
    ?>
</div>

<?php include 'footer.php'; ?>
