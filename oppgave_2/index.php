<?php
  // Require libraries
  require __DIR__ . '/vendor/autoload.php';
  
  // URL to scrape with the required query parameters. Only thing that is not queried is
  // positive reviews and titles that do not contain the letter "a" in the title.
  // This will have to be filtered through manually.
  $url = 'https://store.steampowered.com/search/?maxprice=90&tags=5350&category1=998&supportedlang=norwegian';
  // Initialize curl object
  $ch = curl_init();
  // Set options for the request
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  // Execure curl request and save response
  $response = curl_exec($ch);
  // Close the connection
  curl_close($ch);
  $dom = new simple_html_dom();
  $dom->load($response);
?>
