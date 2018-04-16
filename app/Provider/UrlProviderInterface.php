<?php
namespace ParserApplication\Provider;

interface UrlProviderInterface
{
    /**
     * @param string $domainUrl
     * @param array $urlList
     * @return array
     */
    public function findOnlyDomainLinks(string $domainUrl, array $urlList): array;

    /**
     * @param string $url
     * @return string
     */
    public function getDomainFromUrl(string $url): string;

    /**
     * @param string $url
     * @return string
     */
    public function normalizeUrl(string $url): string;
}