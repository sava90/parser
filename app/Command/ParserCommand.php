<?php
namespace ParserApplication\Command;

use ParserApplication\Manager\ParserManager;
use ParserApplication\Manager\HtmlManager;

class ParserCommand implements CommandInterface
{
    const NAME = "parser";

    /**
     * @param array $params
     */
    public function execute(array $params = [])
    {
        if (!isset($params[0])) {
            throw new \Exception();
        }

        $url = $params[0];
        $parserManager = new ParserManager();
        $htmlManager = new HtmlManager();
        $data = $parserManager->parseSite($url);
        $fileName = $htmlManager->createReport($data);
        echo $fileName . "\n";
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return "{$this->getName()} %url% - Run parser with url start page.";
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }
}