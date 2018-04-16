<?php
namespace ParserApplication\Tag;

interface TagQuantityCountableInterface extends TagInterface
{
    /**
     * @param string $pageContent
     * @return int
     */
    public function countQuantity(string $pageContent): int;
}