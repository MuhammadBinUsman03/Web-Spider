<?php

function searchString($searchString){
    //Collect results
    $result = FetchRecords($searchString);
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<a href='" . $row["url"] . "'>" . $row["url"] . "</a><br>";
     
            // Display the matched content snippet
            $htmlContent = $row["html_content"];
            $position = stripos($htmlContent, $searchString);
            $maxLength = 100; // Maximum length of displayed snippet
            if ($position !== false) {
                $start = max(0, $position - ($maxLength / 2));
                $snippet = substr($htmlContent, $start, $maxLength);
                
                // Display the snippet around the matched content
                echo '...' . htmlspecialchars($snippet) . '...' . "<br><br>";
            }

        }
    } 
    else {
        echo "No matching content found";
    }
}
?>
