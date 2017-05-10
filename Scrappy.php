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
        $headers = self::parse_header_string($this->options['headers']);

        $html = (string) $client->request('GET', $url, [
            'cookies' => $cookie_jar,
            'headers' => $headers,
            'debug' => true,
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
            $cookie = explode('=', $line_str);
            $cookie = array_map('trim', $cookie);
            $cookies_arr[$cookie[0]] = $cookie[1];
        }

        return $cookies_arr;
    }

    /**
     * Convert an array of header strings to a single associative array
     */
    private static function parse_header_string(array $header_strings)
    {
        $headers = [];

        foreach ($header_strings as $header_string) {
            $header = explode(':', $header_string, 2);
            $header = array_map('trim', $header);
            $headers[$header[0]] = $header[1];
        }

        return $headers;
    }
}
