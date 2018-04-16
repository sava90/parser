<?php
namespace ParserApplication\Tag;

abstract class Tag implements TagInterface
{
    /**
     * @return string
     */
    abstract protected function getFindTagPattern(): string;

    /**
     * @param string $pageContent
     * @return array
     */
    public function findAll(string $pageContent): array
    {
        $pattern = $this->getFindTagPattern();
        $tagContentArray = $this->findAllByPattern($pattern, $pageContent);

        return $tagContentArray;
    }

    /**
     * @param string $pattern
     * @param string $pageContent
     * @return array
     */
    protected function findAllByPattern(string $pattern, string $pageContent): array
    {
        $array = [];
        preg_match_all($pattern, $pageContent, $matches);
        if (is_array($matches) && isset($matches[1])) {
            $array = $matches[1];
        }

        return $array;
    }
}