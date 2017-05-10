<?php

namespace JoeAnzalone;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;

class Scrappy
{
    function __construct($options)
    {
        $this->options = $options;
    }

    public function scrape()
    {
        $url = $this->options['url'];
        $cookies_str = $this->options['cookies'];
        $cookies = self::parse_cookies_string($cookies_str);

        $client = new Client();

        $hostname = parse_url($url, PHP_URL_HOST);
        $cookie_jar = CookieJar::fromArray($cookies, $hostname);

        $html = (string) $client->request('GET', $url, [
            'cookies' => $cookie_jar,
        ])->getBody();

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $element = $crawler->filterXPath($this->options['selector']);

        return $element;
    }

    /**
     * Convert a cookie string (taken from a browser, for example) to an array
     */
    private static function parse_cookies_string(string $string)
    {
        $lines = explode(';', $string);

        $cookies_arr = [];
        foreach ($lines as $line_str) {
            $line = explode('=', $line_str);
            $cookies_arr[$line[0]] = $line[1];
        }

        return $cookies_arr;
    }
}
