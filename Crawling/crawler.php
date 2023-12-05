<?php
set_time_limit(600);

function crawl_page($url, $depth = 2) {
    static $URLQueue = array();

    if (isset($URLQueue[$url])) {
        return;
    }

    $URLQueue[$url] = true;

    $dom = new DOMDocument();
    @$dom->loadHTMLFile($url);
    

    $htmlContent = $dom->saveHTML();
    InsertHTML($url, $htmlContent);  
    
    // If depth has reached end, return because last layer has been inserted into database
    if ($depth === 0){
        return;
    }
    $anchors = $dom->getElementsByTagName('a');
    foreach ($anchors as $element) {
        $href = $element->getAttribute('href');
        $a_text = $element->nodeValue;

        //Convert All relative urls into absolute urls
        $href = resolve_url($url, $href);

        //Output URL and crawl deeper
        echo "<a href='".$href."'>".$a_text."</a><br>";
        crawl_page($href, $depth - 1);
    }
}



// appropriate HTML Parsing
function resolve_url($base, $href) {
    $url_parts = parse_url($base);
    if (strpos($href, "//") === 0) {
        return $url_parts['scheme'] . ":" . $href;
    } elseif (strpos($href, "/") === 0) {
        return $url_parts['scheme'] . "://" . $url_parts['host'] . $href;
    } else {
        return $base . $href;
    }
}
?>
