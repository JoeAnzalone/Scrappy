# Scrappy
Scrappy is a really simple web scraper with a CLI interface. You give it a URL and an XPath and it spits out results.
More feature coming soon?????

## Installation
1. [Install Composer](https://getcomposer.org/)
2. `git clone https://github.com/JoeAnzalone/Scrappy`
3. `cd Scrappy` duh
4. `composer install` to install dependencies
5. `php app.php scrape -h` to view the help info

## Usage
Scrappy has two required parameters (`--url` and `--selector`)
and two optional parameters (`--cookies` and `--header`)

    php app.php scrape \
        --url=https://htmlbyjoe.com \
        --selector="//*[@class=\"meta-item reblog-link\"]" \
        --cookies="awesome=true; some_session_id_perhaps=9cdfb439c7876e703e307864c9167a15" \
        --header="User-Agent: Internet Explorer 6"
