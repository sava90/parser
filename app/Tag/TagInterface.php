<?php
namespace ParserApplication\Tag;

interface TagInterface
{
    /**
     * @param string $pageContent
     * @return array
     */
    public function findAll(string $pageContent): array;
    public function getName(): string;
}