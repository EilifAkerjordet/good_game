<?php
  // Require libraries
  use voku\helper\HtmlDomParser;
  require_once 'vendor/autoload.php';

  // Function declarations
  function saveToDisk ($arr) {
    $currTimeDate =  date('Y-m-d-H:i:s');
    $fileName = 'results' . '_' . $currTimeDate . '.json';
    $fileLocation = './results/' . $fileName;
    file_put_contents($fileLocation, json_encode($arr,
      JSON_UNESCAPED_UNICODE |
      JSON_UNESCAPED_SLASHES |
      JSON_PRETTY_PRINT
    ));
  }

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

  function findCurrentPrice ($string) {
    $exp = '/\d+,\d+ kr/im';
    $regexSuccess = preg_match_all($exp, $string, $matches);
    $price = 'not found'; // not found as default value
    if ($regexSuccess) {
      // If there are more than 2 matches, the first match will be the before discount price. We disregard this price.
      if (count($matches[0]) === 2) {
        $price = $matches[0][1];
      } else {
        $price = $matches[0][0];
      }
    }
    return $price;
  }

  function fetchData ($templateUrl, $start, $count) {
    $vars = [
    '$start' => $start,
    '$count' => $count
    ];
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
    // Decode and return response object with the curl object (so the request can be ended outside the function)
    return [
      'response' => json_decode($response, true),
      'curl_object' => $ch
    ];
  }

  function extractGamesData ($html) {
    $games = [];
    $dom = HtmlDomParser::str_get_html($html);
    // Find all items in search results
    $elements = $dom->findMulti('a.search_result_row');
    foreach ($elements as $element) {
      $title = $element->findOne('span.title')->innerText();
      // Only do operation if game does not contain letter A and has positive reviews
      if (!containsLetterA($title) && hasPositiveReview($element)) {
        $imgUrl = $element->findOne('img')->getAttribute('src');
        $releaseDate = $element->findOne('div.search_released')->innerText();
        $priceElInnerHtml = $element->findOne('div.search_price')->innerText();
        $price = findCurrentPrice($priceElInnerHtml);

        $games = [
          ...$games,
          [
            'title' => $title,
            'image_url' => $imgUrl,
            'price' => $price,
            'release_date' => $releaseDate
          ]
        ];
      }
    };
    return $games;
  }

  function getAllMatchingGames ($templateUrl, $count) {
    $start = 0; // Start should always have an initial value of 0.
    $games = ['results' => []];
    ['response' => $json, 'curl_object' => $ch] = fetchData($templateUrl, $start, $count);
    $pageResult = extractGamesData($json['results_html']);
    $games['results'] = [...$games['results'], ...$pageResult];
    // If there are still more results, loop to get the rest
    $totalCount = $json['total_count'];
    // Calc new start value here
    $start = $start + $count + 1; // +1 to avoid duplicates
    while ($totalCount > $start + $count) {
      ['response' => $json, 'curl_object' => $ch] = fetchData($templateUrl, $start, $count);
      $pageResults = extractGamesData($json['results_html']);
      $games['results'] = [...$games['results'], ...$pageResults];
      $start = $start + $count + 1;
    }
    curl_close($ch);
    $games['total_mathing_results'] = count($games['results']);
    return $games;
  }

  // First parameter is the template URL to scrape, the second one is the default value for $count in the string
  // Most of the query parameters are handled in the URL. However, getting results with positive reviews and no 'a' int the
  // title will have to be handled manually.
  $games = getAllMatchingGames('https://store.steampowered.com/search/results/?query&start=$start&count=$count&dynamic_data=&sort_by=_ASC&maxprice=90&tags=5350&category1=998&supportedlang=norwegian&snr=1_7_7_240_7&infinite=1',
    50);

  // Save json file with timestamp
  saveToDisk($games);
?>
