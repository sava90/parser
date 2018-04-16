<?php
namespace ParserApplication\Provider;

class UrlProvider implements UrlProviderInterface
{
    /**
     * @param string $domainUrl
     * @param array $urlList
     * @return array
     */
    public function findOnlyDomainLinks(string $domainUrl, array $urlList): array
    {
        $correctLinks = [];
        $domain = $this->getDomainFromUrl($domainUrl);
        $domainWithProtocol = $this->normalizeUrl($domain);
        foreach ($urlList as $key => $url) {
            $url = trim($url);
            /* get rid of PHPSESSID, #linkname, &amp; and javascript: */
            $url = preg_replace(array(
                '/([\?&]PHPSESSID=\w+)$/i',
                '/(#[^\/]*)$/i',
                '/&amp;/',
                '/^(javascript:.*)/i',
                '/^(tel:.*)/i',
                '/^(skype:.*)/i',
                '/^(viber:.*)/i'
            ), array('', '', '&', ''), $url);
            if (!preg_match("/^http[s]?:\/\/[^\/]*/", $url) && preg_match('/^[\/]?[^\/]+/', $url)) {
                $baseUrl = strpos($url, '/') === 0 ? $domainWithProtocol : $domainUrl;
                $url = $this->relativeToAbsolute($baseUrl, $url);
            }
            // check if in the same (sub-)$domain
            if (preg_match("/^http[s]?:\/\/[^\/]*" . str_replace('.', '\.', $domain) . "/i", $url)) {
                //save the URL
                if (!in_array($url, $correctLinks)) {
                    $correctLinks[] = $url;
                }
            }
        }

        return $correctLinks;
    }

    /**
     * @param string $url
     * @return string
     */
    public function getDomainFromUrl(string $url): string
    {
        $normalizedUrl = $this->normalizeUrl($url);
        $domain = parse_url($normalizedUrl, PHP_URL_HOST);

        return $domain;
    }

    /**
     * @param string $url
     * @return string
     */
    public function normalizeUrl(string $url): string
    {
        $normalizedUrl = ltrim($url, '/');
        // If scheme included, return
        if (preg_match('#^http(s)?://#', $normalizedUrl)) {
            return $normalizedUrl;
        }
        $schema = $this->checkHttps($normalizedUrl) ? 'https://' : 'http://';
        $normalizedUrl = $schema . $normalizedUrl;

        return $normalizedUrl;
    }

    /**
     * @param string $url
     * @return bool
     */
    private function checkHttps(string $url): bool
    {
        if (!function_exists('curl_version')) {
            $exceptionMessage = 'CURL not installed/enabled';
            throw new \Exception($exceptionMessage);
        }
        $curlRequest = curl_init('https://' . $url);
        curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, 'HEAD');
        curl_setopt($curlRequest, CURLOPT_NOBODY, true);
        curl_setopt($curlRequest, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlRequest, CURLOPT_VERBOSE, 0);
        curl_setopt($curlRequest, CURLOPT_HEADER, 1);
        curl_setopt($curlRequest, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlRequest, CURLOPT_TIMEOUT, 15);
        curl_exec($curlRequest);
        $header = curl_getinfo($curlRequest, CURLINFO_HTTP_CODE);
        if ($header === 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $baseUrl
     * @param $relativeUrl
     * @return string
    */
    private function relativeToAbsolute($baseUrl, $relativeUrl): string
    {
        if ($relativeUrl[0] == '/') {
            $relativeUrl = substr($relativeUrl, 1);
        }

        if (!$relativeUrl) {
            return $baseUrl;
        }

        if ($baseUrl[strlen($baseUrl) - 1] == '/') {
            $baseUrl = substr($baseUrl, 0, strlen($baseUrl) - 1);
        }

        $absoluteUrl = $baseUrl . '/' . $relativeUrl;

        return $absoluteUrl;
    }
}