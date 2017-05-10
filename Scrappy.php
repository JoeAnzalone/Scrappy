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

    public function scrape($output)
    {
        $end = $this->options['end'];

        $scraped = [];

        for ($i = $this->options['start']; $i <= $end; $i++) {
            $output->writeln(sprintf('<info>Scraping page %d of %d...</info>', $i, $end));
            $elements = $this->scrape_page($i);

            foreach ($elements as $element) {
                $output->writeln(
                    trim($element->textContent)
                );

                $scraped[$i] = $element;
            }

            if ($i !== $end) {
                // No need to sleep on the last iteration
                sleep($this->options['interval']);
            }
        }

        return $scraped;
    }

    public function scrape_page(int $page = 1)
    {
        $url = sprintf($this->options['url'], $page);
        $cookies_str = $this->options['cookies'];
        $cookies = self::parse_cookies_string($cookies_str);

        $client = new Client();

        $hostname = parse_url($url, PHP_URL_HOST);
        $cookie_jar = CookieJar::fromArray($cookies, $hostname);
        $headers = self::parse_header_string($this->options['headers']);

        $html = (string) $client->request('GET', $url, [
            'cookies' => $cookie_jar,
            'headers' => $headers,
            'debug' => $this->options['debug'],
        ])->getBody();

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $element = $crawler->filterXPath($this->options['selector']);

        return $element;
    }

    /**
     * Convert a cookie string (taken from a browser, for example) to an array
     */
    private static function parse_cookies_string(string $string = '')
    {
        $lines = explode(';', $string);
        $lines = array_filter($lines); // Remove empty element in case of empty string

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
