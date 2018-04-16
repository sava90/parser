<?php
namespace ParserApplication\Manager;

use ParserApplication\Client\SimpleClient;
use ParserApplication\Provider\TagProvider;
use ParserApplication\Provider\UrlProvider;

class ParserManager
{
    /**
     * @var SimpleClient
     */
    private $client;

    /**
     * @var UrlProvider
     */
    private $urlProvider;

    /**
     * @var TagProvider
     */
    private $tagProvider;

    /**
     * ParserManager constructor.
     */
    public function __construct()
    {
        $this->client = new SimpleClient();
        $this->urlProvider = new UrlProvider();
        $this->tagProvider = new TagProvider();
    }

    /**
     * @param string $startUrl
     * @return array
     */
    public function parseSite(string $startUrl)
    {
        $urlContent = '';
        $result = [];

        try {
            $urlContent = $this->client->fetch($startUrl);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $urls[] = $this->urlProvider->normalizeUrl($startUrl);
        $urlContent = preg_replace("#<!--[^-]*(?:-(?!->)[^-]*)*-->#", "", $urlContent);
        preg_match_all('/(<a\s.*href=["\'](.+)["\'].*>.*<\/a>)/', $urlContent, $matches);

        if (is_array($matches) && isset($matches[1])) {
            $urls = array_merge($urls, $matches[2]);
        }

        $newDomainUrlArray = $this->urlProvider->findOnlyDomainLinks($startUrl, $urls);

        foreach ($newDomainUrlArray as $newUrl) {
            $result[] = $this->processUrl($newUrl);
        }

        usort($result, 'self::sortArray');

        return $result;
    }

    private function sortArray($result1, $result2)
    {
        if ($result1[1] == $result2[1]) {
            return 0;
        }

        if ($result1[1] > $result2[1]) {
            return -1;
        }

        return 1;
    }

    /**
     * @param string $url
     * @return array
     */
    private function processUrl($url)
    {
        $start = microtime(true);

        $urlContent = '';

        try {
            $urlContent = $this->client->fetch($url);
        } catch (\Exception $e) {
            exit($e->getMessage());

        }

        $urlContent = preg_replace("#<!--[^-]*(?:-(?!->)[^-]*)*-->#", "", $urlContent);
        $totalTagImg = $this->tagProvider->getTagQuantity('img', $urlContent);
        $time = microtime(true) - $start;
        $result = [$url, $totalTagImg, number_format($time,3)];

        return $result;
    }

    /**
     * @param string $url
     * @return string
     */
    public function getDomainFromUrl(string $url): string
    {
        $domain = $this->urlProvider->getDomainFromUrl($url);

        return $domain;
    }
}