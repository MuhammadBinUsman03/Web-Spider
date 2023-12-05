<?php
set_time_limit(600);

function crawl_page($url, $depth = 2) {
    static $URLQueue = array();

    if (isAllowedByRobotsTxt($url) || isset($URLQueue[$url])) {
        return;
    }

    $URLQueue[$url] = true;

    $dom = new DOMDocument();
    @$dom->loadHTMLFile($url);

    $htmlContent = $dom->saveHTML();
    $metaInfo = extractMetaInfo($htmlContent);
    $title = $metaInfo['title'];
    $metaDesc = $metaInfo['meta_description'];
    echo "Title: " . $metaInfo['title'] . "<br>";
    echo "Meta Description: " . $metaInfo['meta_description'] . "<br><br><hr>";
    InsertHTML($url, $title, $htmlContent, $metaDesc);  
    
    // If depth has reached end, return because last layer has been inserted into database
    if ($depth === 0){
        return;
    }
    //Extract anchor tags from the HTML document
    $anchors = $dom->getElementsByTagName('a');
    foreach ($anchors as $element) {
        $href = $element->getAttribute('href');
        $a_text = $element->nodeValue;

        //Convert All relative urls into absolute urls
        $href = resolve_url($url, $href);

        //Output URL and crawl deeper
        echo "URL: <a href='".$href."'>".$a_text."</a><br>";
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

// Get Title and Meta Description
function extractMetaInfo($htmlContent) {
    $dom = new DOMDocument();
    @$dom->loadHTML($htmlContent);

    @$title = $dom->getElementsByTagName('title')->item(0)->textContent;

    $metaDescription = '';
    $metaTags = $dom->getElementsByTagName('meta');
    foreach ($metaTags as $tag) {
        if ($tag->getAttribute('name') === 'description') {
            $metaDescription = $tag->getAttribute('content');
            break;
        }
    }

    return [
        'title' => $title,
        'meta_description' => $metaDescription
    ];
}

//Check Robots.txt
function isAllowedByRobotsTxt($url)
{
    $robotsUrl = parse_url($url);
    $robotsUrl['path'] = '/robots.txt';
    $robotsTxtUrl = $robotsUrl['scheme'] . '://' . $robotsUrl['host'] . (isset($robotsUrl['port']) ? ':' . $robotsUrl['port'] : '') . $robotsUrl['path'];
    $robotsContent = @file_get_contents($robotsTxtUrl);

    if ($robotsContent === false) {
        return true;
    }

    $robotsRules = explode("\n", $robotsContent);
    foreach ($robotsRules as $rule) {
        if (strpos($rule, 'Disallow:') !== false) {
            $disallowedPath = trim(str_replace('Disallow:', '', $rule));
            $disallowedUrl = $robotsUrl['scheme'] . '://' . $robotsUrl['host'] . (isset($robotsUrl['port']) ? ':' . $robotsUrl['port'] : '') . $disallowedPath;

            if (strpos($url, $disallowedUrl) === 0) {
                return false;
            }
        }
    }

    return true;
}
?>
