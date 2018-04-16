<?php
namespace ParserApplication\Provider;

interface TagProviderInterface
{
    /**
     * @param string $tagName
     * @param string $pageContent
     * @return int
     */
    public function getTagQuantity(string $tagName, string $pageContent): int;
}