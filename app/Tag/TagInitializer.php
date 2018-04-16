<?php
namespace ParserApplication\Tag;

class TagInitializer
{
    private $tags = [];

    public function __construct()
    {
        $this->tags[] = new TagImg();
    }

    public function getTag($tagName, $methodName): ? TagInterface
    {
        /** @var TagInterface $tag */
        foreach ($this->tags as $tag) {
            if ($tag->getName() == $tagName) {
                if (!method_exists($tag, $methodName)) {
                    throw new \Exception();
                }
                return $tag;
            }
        }
        throw new \Exception();
    }
}