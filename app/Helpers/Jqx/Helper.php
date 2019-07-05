<?php

namespace App\Helpers\Jqx;

use Closure;

/**
 * @author
 */
class Helper
{
    /**
     * @var
     */
    const UNDEF = '(undef)';

    /**
     * Remove special undefined values from array
     * @param array $data
     * @return void
     */
    public static function rmUndef($data) {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $val) {
                if ($val === static::UNDEF) {
                    unset($data[$key]);
                }
            }
        }
        // Return
        return $data;
    }

    /**
     * 
     * @param string $js
     * @return Closure
     */
    public static function jsFunc($js) {
        return function() use ($js) { return trim($js); };
    }

    /**
     * Convert data to json, support undefined and function
     * @param mixed $data
     * @return void
     */
    public static function toJson($data)
    {
        $gensK = [
            ('"' . static::UNDEF . '"'),
            '"{!!', '!!}"'
        ];
        $gensV = [
            'undefined',
            '', ''
        ];
        $output = $data;
        if (is_array($data) && !empty($data))
        {
            $output = [];
            foreach ($data as $key => $val)
            {
                if ($val === static::UNDEF)
                {
                    continue;
                }
                if ($val instanceof Closure)
                {
                    $gensK[] = '"' . ($idx = ("__" . count($gensK) . "__")) . '"';
                    $gensV[] = $val($key);
                    $val = $idx;
                }
                $output[$key] = $val;
            }
        }
        unset($data);
        $output = @json_encode($output, JSON_PRETTY_PRINT);
        // 
        if ($output) {
            $output = str_replace($gensK, $gensV, $output);
        }
        // Return
        return $output;
    }
}
