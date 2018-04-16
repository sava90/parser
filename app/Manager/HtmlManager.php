<?php
namespace ParserApplication\Manager;

class HtmlManager
{
    /**
     * @param array $data
     * @return string
     */
    public function createReport($data): string
    {
        $date = date('d.m.Y');
        $reportFilePath = dirname(dirname(__DIR__)).'/report_'.$date.'.html';
        $dataArray['reportsData'] = $data;
        $dataArray['date'] = $date;

        $content = $this->fetchFile(dirname(__DIR__).'/View/index.html.php', $dataArray);

        file_put_contents($reportFilePath, $content);

        return $reportFilePath;
    }

    /**
     * @param string $filePath
     * @param array $dataArray
     * @return string
     */
    private function fetchFile($filePath, $dataArray): string
    {
        $dataArray = $dataArray ? $dataArray : [];
        extract($dataArray, EXTR_PREFIX_ALL, 'view');
        ob_start();

        if (is_file($filePath)) {
            require($filePath);
        }

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}