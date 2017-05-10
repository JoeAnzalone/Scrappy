<?php

namespace JoeAnzalone;

use GuzzleHttp\Client;
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

        $client = new Client();
        $cookie_jar = new \GuzzleHttp\Cookie\CookieJar();

        $html = (string) $client->request('GET', $url, [
            'cookies' => $cookie_jar,
        ])->getBody();

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $element = $crawler->filterXPath($this->options['selector']);

        return $element;
    }
}
