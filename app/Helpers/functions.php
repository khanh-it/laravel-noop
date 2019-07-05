<?php

if (!function_exists('numberFormat')) {
	/**
	 * Format a number with grouped thousands
	 *
	 * @param float $number
	 * @param float $decimals
	 * @param string $dec_point
	 * @param string $thousands_sep
	 * @return string
	 */
	function numberFormat($number, int $decimals = 0, string $dec_point = "." , string $thousands_sep = ",")
	{
		return number_format($number, $decimals, $dec_point, $thousands_sep);
	}
}

if (!function_exists('numberFormatTax')) {
	/**
	 * Format a tax number with grouped thousands
	 *
	 * @param float $number
	 * @param string $dec_point
	 * @param string $thousands_sep
	 * @param float $decimals
	 * @return string
	 */
	function numberFormatTax($number, string $dec_point = "." , string $thousands_sep = ",", int $decimals = 2)
	{
		return number_format($number, $decimals, $dec_point, $thousands_sep);
	}
}

if (!function_exists('numberFormatWorktime')) {
	/**
	 * Format a worktime number with grouped thousands
	 *
	 * @param float $number
	 * @param string $dec_point
	 * @param string $thousands_sep
	 * @param float $decimals
	 * @return string
	 */
	function numberFormatWorktime($number, string $dec_point = "." , string $thousands_sep = ",", int $decimals = 3)
	{
		return number_format($number, $decimals, $dec_point, $thousands_sep);
	}
}

if (!function_exists('timeStrRemoveParts')) {
	/**
	 * Format time (H:i:s) parts
	 *
	 * @param string $timeStr Time string (H:i:s)
	 * @param string|array $parts
	 * @return string
	 */
	function timeStrRemoveParts($timeStr, $parts = [2])
	{
		if ($timeStr) {
			$timeStr = explode(':', $timeStr);
			foreach ((array)$parts as $pos)
			{
				if (!is_null($timeStr[$pos]))
				{
					unset($timeStr[$pos]);
				}
			}
			$timeStr = implode(':', $timeStr);
		}
		return $timeStr;
	}
}

if (!function_exists('defaultSelectOptTag')) {
	/**
	 * Default <select/></option> tag
	 *
	 * @param array $options An array of options
	 * @return array
	 */
	function defaultSelectOptTag(array $options = array())
	{
		return [ $options['value'] ?: '' => $options['label'] ?: ' -- Chọn -- ' ];
	}
}

if (!function_exists('date_now')) {

	/**
	 * @todo Hàm hỗ trợ lấy ngày hiện tại
	 * @return array
	*/
	function date_now()
	{
		$now = new \DateTime();
		$now = $now->format('Y-m-d');
		$now = new \DateTime( $now );
		$now = $now->getTimestamp();
		return $now;
	}
}

if (!function_exists('timeStartOfDay')) {

	/**
	 * @todo Hàm hỗ trợ lấy thời gian đầu ngày
	 * @return array
	*/
	function timeStartOfDay($value)
	{
		if (is_string($value)){
			$value = str_replace("/", ".", $value);
			$value = \DateTime::createFromFormat('d.m.Y', $value);
			$value = $value->format('d.m.Y') ?: null;
			if( $value ) {

				$value = (int)strtotime($value);
			}
		}
		return ($value && $value > 0) ? mktime(0, 0, 0, date('m', $value), date('d', $value), date('Y', $value)) : null;
	}
}


if (!function_exists('timeEndOfDay')) {

	/**
	 * @todo Hàm hỗ trợ lấy thời gian cuối ngày
	 * @return array
	*/
	function timeEndOfDay($value)
	{

		if (is_string($value)){
			$value = str_replace("/", ".", $value);
			$value = \DateTime::createFromFormat('d.m.Y', $value);
			$value = $value->format('d.m.Y') ?: null;
			if( $value ) {

				$value = (int)strtotime($value);
			}
		}
		return ($value && $value > 0) ? mktime(23, 59, 59, date('m', $value), date('d', $value), date('Y', $value)) : null;
	}
}
