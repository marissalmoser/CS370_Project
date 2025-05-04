<?php include 'header.php'; ?>

<div class="container">
    <h1>Stories and Advertisements</h1>
    <p>This page displays all stories and active advertisements on the site.</p>
</div>

<?php
$conn = mysqli_connect("localhost", "root", "sqlpassword_1", "news", 3300);
if (!$conn) {
    die("<div class='alert alert-danger'>Connection Error: " . mysqli_connect_error() . "</div>");
}

// ---- STORY TABLE ----
function story_table_open() {
    echo "<h2>Stories</h2>";
    echo "<table class='table table-striped' style='width: 100%'>\n";
    echo "<thead><tr>
        <th>Title</th>
        <th>Body</th>
        <th>Published</th>
        <th>Comic URL</th>
    </tr></thead>\n";
}

function story_table_close() {
    echo "</table>\n";
}

function output_story_row($story) {
    echo "<tr>
        <td>{$story['Title']}</td>
        <td>{$story['Body']}</td>
        <td>{$story['PublishedTimestamp']}</td>
       <td><a href='{$story['ComicURL']}' target='_blank'>{$story['ComicURL']}</a></td>

    </tr>\n";
}

$story_query = "SELECT * FROM Story";
$story_result = mysqli_query($conn, $story_query);

if ($story_result) {
    story_table_open();
    while ($story = mysqli_fetch_assoc($story_result)) {
        output_story_row($story);
    }
    story_table_close();
} else {
    echo "<div class='alert alert-danger'>Failed to retrieve stories: " . mysqli_error($conn) . "</div>";
}

// ---- ADVERTISEMENT TABLE ----
function ad_table_open() {
    echo "<h2>Advertisements</h2>";
    echo "<table class='table table-bordered' style='width: 100%'>\n";
    echo "<thead><tr>
        <th>Ad Name</th>
        <th>Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Content</th>
    </tr></thead>\n";
}

function ad_table_close() {
    echo "</table>\n";
}

function output_ad_row($ad) {
    echo "<tr>
        <td>{$ad['AdName']}</td>
        <td>{$ad['AdType']}</td>
        <td>{$ad['StartDate']}</td>
        <td>{$ad['EndDate']}</td>
        <td><a href='{$ad['ContentURL']}' target='_blank'>View Ad</a></td>
    </tr>\n";
}

$ad_query = "SELECT * FROM Advertisement ORDER BY StartDate DESC";
$ad_result = mysqli_query($conn, $ad_query);

if ($ad_result) {
    ad_table_open();
    while ($ad = mysqli_fetch_assoc($ad_result)) {
        output_ad_row($ad);
    }
    ad_table_close();
} else {
    echo "<div class='alert alert-warning'>No advertisements found.</div>";
}
?>

<?php include 'footer.php'; ?>
