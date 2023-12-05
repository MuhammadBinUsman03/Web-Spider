<?php
// Require the file containing the crawl function
require_once('../Crawling/crawler.php');
require_once('../Crawling/searcher.php');
//Include crud file
include('../Data/CRUD.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the "crawl" button is clicked
    if (isset($_POST['crawl'])) {
        // Collecting form data
        $seedURL = $_POST['seedURL'];
        $depthLimit = intval($_POST['depthLimit']);
        
        echo "SEED URL: <a href='$seedURL'>$seedURL</a><br>";
        
        // Check if the seed URL and depth limit are not empty
        if (!empty($seedURL) && !empty($depthLimit)) {
            // Call crawl function with the collected data
            // echo $seedURL, $depthLimit;
            
            //Empty Last crawled content (if any) from database
            TruncateTable();
            crawl_page($seedURL, $depthLimit);
        } else {
            echo "Please provide both Seed URL and Depth Limit.";
        }
    }
    
    // Check if the "search" button is clicked
    if (isset($_POST['search'])) {
        
        $seedURL = $_POST['seedURL'];
        if (!empty($seedURL)){
            echo "SEED URL: <a href='$seedURL'>$seedURL</a><br>";
            
        }

        // Collecting search string
        $searchString = $_POST['searchString'];

        // Check if the search string is not empty
        if (!empty($searchString)) {
            searchString($searchString);
        } else {
            echo "Please provide a Search String.";
        }
    }
    //Close Mysqli Conn
    closeConn();
}
?>
