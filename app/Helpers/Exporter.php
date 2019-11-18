<?php
namespace App\Helpers;

/**
 * @author KhanhDTP 2019-01-09
 */
class Exporter
{
    /**
     * Get text: company's name
     * @param array $options An array of options
     * @return string
     */
    public static function txtCompanyName(array $options = array())
    {
        return 'bannersys.zoharb.com';
    }

    /**
     * Get text - excel: newline
     * @param array $options An array of options
     * @return string
     */
    public static function txtExcelNewline(array $options = array())
    {
        return '<br style="mso-data-placement:same-cell;"/>';
    }

    /**
     * Download file excel
     * @param string $filename Download file name
     * @param array $options An array of options
     * @return void
     */
    public static function downloadExcel($filename, array $options = array())
    {
        // Get, format input(s)
        // +++ File extension
        $fnext = $options['fn_ext'] ?? 'xls';
        // +++ Filename's subfix
        $filename = trim($filename);
        if (!isset($options['skip_fn_subfix'])) {
            $filename = $filename . "_" . time();
        }
        $filename = "{$filename}.{$fnext}";

        // Send headers
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        // header("Content-type: application/x-msexcel; charset=utf-8");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
    }
}
