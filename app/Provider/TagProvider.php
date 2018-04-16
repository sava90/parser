<?php
namespace ParserApplication\Provider;

use ParserApplication\Tag\TagInitializer;
use ParserApplication\Tag\TagQuantityCountableInterface;

class TagProvider implements TagProviderInterface
{
    /**
     * @param string $tagName
     * @param string $pageContent
     * @return int
     */
    public function getTagQuantity(string $tagName, string $pageContent): int
    {
        try {
            $tagInitializer = new TagInitializer();
            /** @var TagQuantityCountableInterface $tag */
            $tag = $tagInitializer->getTag($tagName, 'countQuantity');
            $count = $tag->countQuantity($pageContent);
        } catch (\Exception $e) {
            $count = 0;
        }

        return $count;
    }
}