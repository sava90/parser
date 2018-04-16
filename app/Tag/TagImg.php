<?php
namespace ParserApplication\Tag;

class TagImg extends Tag implements TagQuantityCountableInterface
{
    const FIND_TAG_PATTERN = '/(<img[^>]+>)/simU';
    const TAG_NAME = 'img';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TAG_NAME;
    }

    /**
     * @return string
     */
    protected function getFindTagPattern(): string
    {
        return self::FIND_TAG_PATTERN;
    }

    /**
     * @param string $pageContent
     * @return int
     */
    public function countQuantity(string $pageContent): int
    {
        $tagsArray = $this->findAll($pageContent);
        $tagsCount = count($tagsArray);

        return $tagsCount;
    }
}