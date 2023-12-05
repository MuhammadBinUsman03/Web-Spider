<?php
$HostName = "localhost";
$UserName = "root";
$Password = "";
$Database = "crawling";

$conn = mysqli_connect($HostName, $UserName, $Password, $Database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function InsertHTML($url, $title, $content, $description){
    global $conn;
    // Insert URL and HTML content into the database
    //Remove escape characters in HTML content
    $content = mysqli_real_escape_string($conn, $content);
    $query = "INSERT INTO crawled_pages (url, title, html_content, Description) VALUES ('$url', '$title','$content', '$description')";
    mysqli_query($conn, $query);
}

function TruncateTable(){
    global $conn;
    $query = "TRUNCATE TABLE crawled_pages";
    mysqli_query($conn, $query);
}

function FetchRecords($searchString){
    global $conn;
    // Search for links containing the search string and returns the set object containing all results
    $searchQuery = "SELECT url, html_content FROM crawled_pages WHERE html_content LIKE '%$searchString%'";
    $result = mysqli_query($conn, $searchQuery);
    return $result;
}
// Close the connection
function closeConn(){
    global $conn;
    mysqli_close($conn);
}
?>
