# A simple web scraper to scrape Steam search results

## About the script

The script is created in order to be able to present the results from [the steam search results](https://store.steampowered.com/search/). In theory, the script could take any link, so long as it has the query parameter `start=$start&count=$count&infinite=1`, in the url. i.e.  
`https://store.steampowered.com/search/results/?query&start=$start&count=$count&dynamic_data=&sort_by=_ASC&maxprice=90&tags=5350&category1=998&supportedlang=norwegian&snr=1_7_7_240_7&infinite=1`. (This is also the default URL that will be scraped). `$start` and `$count` are variables used for pagination in the case that there are multiple pages of result data. `infinite=1` makes sure that the request returns JSON with the total number of results.This data is also used for pagination purposes.  

The script leverages the [voku/simple_html_dom](https://github.com/voku/simple_html_dom) library for the scraping process itself. The script, once run will create a JSON file with the results, and save it to `'./results/results_$timestamp.json'` where$timestamp is the current date-time.

### Filtering

By default, the script filters for results that:

  * **Is a game** (Handled in the query URL, and can be changed)

  * **Supports the Norwegian language** (Handled in the query URL, and can be changed)

  * **Has a max cost of 90 NOK** (Handled in the query URL, and can be changed)

  * **Is labaled family friendly** (Handled in the query URL, and can be changed)

  * **Has positive reviews** (Handled in the script, and will be done for any input url.)

  * **Does not contain the letter "a" in the title** (Handled in the script, and will be done for any input url.)

## Running the script

  * `cd` into the project folder and `composer install`
  * To run the script, do `php scraper.php`
   
This will save a file with a timestamp under `./results/` with the matching results.

## Potential future improvements

* Make a CLI / API that is completely dynamic, where the user can paste/POST a URL with their desired parameters, and get the data returned to them.
 
* Make filtering that happens in the script dynamic. (User can choose what kinds of reviews they want to filter for, and what letters/words they do not want in the title)
 
* Implement more advanced error handling
