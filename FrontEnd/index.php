<?php


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the "crawl" button is clicked
    if (isset($_POST['crawl'])) {
        // Collecting form data
        $seedURL = $_POST['seedURL'];
        $depthLimit = $_POST['depthLimit'];

        // Check if the seed URL and depth limit are not empty
        if (!empty($seedURL) && !empty($depthLimit)) {
            // Call crawl function with the collected data

        } else {
            echo "Please provide both Seed URL and Depth Limit.";
        }
    }

    // Check if the "search" button is clicked
    if (isset($_POST['search'])) {
        // Collecting search string
        $searchString = $_POST['searchString'];

        // Check if the search string is not empty
        if (!empty($searchString)) {

        } else {
            echo "Please provide a Search String.";
        }
    }
}
?>
