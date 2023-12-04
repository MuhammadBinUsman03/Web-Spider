<?php
include('../Data/CRUD.php');

function crawl_page($url, $depth = 2) {
    static $URLQueue = array();

    if ($depth === 0 || isset($URLQueue[$url])) {
        return;
    }

    $URLQueue[$url] = true;

    $dom = new DOMDocument();
    @$dom->loadHTMLFile($url);
    
    $htmlContent = $dom->saveHTML();

    $anchors = $dom->getElementsByTagName('a');
    foreach ($anchors as $element) {
        $href = $element->getAttribute('href');
        $a_text = $element->nodeValue;
        if (strpos($href, 'http') === 0) {
            //Insert HTML Content into database
            InsertHTML($href, $htmlContent);
            // Echo the crawled hyperlinks
            echo "<a href='".$href."'>".$a_text."</a><br>";
            crawl_page($href, $depth - 1);
        }
    }
}

?>
