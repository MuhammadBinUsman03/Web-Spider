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

function InsertHTML($url, $content){
    global $conn;
    // Insert URL and HTML content into the database
    //Remove escape characters in HTML content
    $content = mysqli_real_escape_string($conn, $content);
    $query = "INSERT INTO crawled_pages (url, html_content) VALUES ('$url', '$content')";
    mysqli_query($conn, $query);
}

?>
