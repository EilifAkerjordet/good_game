<?php
  // Require libraries
  use voku\helper\HtmlDomParser;
  require_once 'vendor/autoload.php';

  // Function declarations
  function containsLetterA ($string) {
    return preg_match_all('/a/mi', $string);
  }

  function hasPositiveReview ($element) {
    $positiveReview = $element->findOneOrFalse('span.positive');
    if ($positiveReview) {
      return true;
    } else {
      return false;
    }
  }

  function fetchData ($templateUrl, $start, $count) {
    $vars = array(
    '$start' => $start,
    '$count' => $count
    );
    $url = strtr($templateUrl, $vars);
    // Initialize curl object
    $ch = curl_init();
    // Set options for the request
    curl_setopt($ch, CURLOPT_URL, $url);
    // Follow in case of redirect
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    // Set RETURNTRANSFER to ensure that it returns false in the case of failure
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Execure initial curl request, save the response
    $response = curl_exec($ch);
    // Decode response object
    return array(
      'response' => json_decode($response, true),
      'curl_object' => $ch
    );
  }

  function extractData ($element) {
    $title = $element->findOne('span.title')->innerText();
    // Return false if title contains the letter a or if game reviews are not positive
    if (containsLetterA($title)) { return false; }
    if (!hasPositiveReview($element)) { return false; }

    $imgUrl = $element->findOne('img')->getAttribute('src');
    $priceElInnerHtml = $element->findOne('div.search_price')->innerText();
    $releaseDate = $element->findOne('div.search_released')->innerText();

    $exp = '/\d+,\d+ kr/im';
    $regexSuccess = preg_match_all($exp, $priceElInnerHtml, $matches);
    $price = 'not found'; // not found as default value
    if ($regexSuccess) {
      // If there are more than 2 matches, the first match will be the before discount price. We disregard this price.
      if (count($matches[0]) === 2) {
        $price = $matches[0][1];
      } else {
        $price = $matches[0][0];
      }
    }
    return array(
      "title" => $title,
      "image_url" => $imgUrl,
      "price" => $price,
      "release_date" => $releaseDate,
   );
  }

  function getAllData ($templateUrl, $start, $count) {
    // Array to store the sorted return data
    $games = array(
      "results" => array()
    );
    // Loop end condition is at the bottom of the loop, in order to prevent having to do an initial request only to get the totalCount.
    while(true) {
      // 'Destructure' response from fetch_data
      ['response' => $json, 'curl_object' => $ch] = fetchData($templateUrl, $start, $count);
      // Save total result count
      $totalCount = $json['total_count'];
      // Create DOM object with the HTML response from the curl request
      $dom = HtmlDomParser::str_get_html($json['results_html']);
      // Find all items in search results
      $elements = $dom->findMulti('a');
      foreach ($elements as $element) {
        $resultData = extractData($element);
        if ($resultData) {
          array_push($games['results'], $resultData);
        }
      };
      // Condition to end the loop
      if($totalCount > $start + $count) {
        $start = $start + 50;
      } else {
        curl_close($ch);
        break 1;
      }
    }
    $games['total_mathing_results'] = count($games['results']);
    return $games;
  }

  // First parameter is the template URL to scrape, the two others are the default values for $start and $count in the string
  // Most of the query parameters are handled in the URL. However, getting results with positive reviews and no 'a' int the
  // title will have to be handled manually.
  $games = getAllData(
    'https://store.steampowered.com/search/results/?query&start=$start&count=$count&dynamic_data=&sort_by=_ASC&maxprice=90&tags=5350&category1=998&supportedlang=norwegian&snr=1_7_7_240_7&infinite=1',
    0,
    50);

  // Save json file with timestamp
  $currTimeDate =  date('Y-m-d-H:i:s');
  $fileName = 'results' . '_' . $currTimeDate . '.json';
  $fileLocation = './results/' . $fileName;
  file_put_contents($fileLocation, json_encode($games));
?>
