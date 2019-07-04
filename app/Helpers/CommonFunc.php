<?php

if (!function_exists('formatProvince')) {
	
	/**
	 * @todo Hàm hỗ trợ format danh sách Province
	 * @param datas: array province lấy từ database
	 * @return array
	*/
	function formatProvince($datas)
	{
		foreach ($datas as $item) {
			if($item['children'])
			{
				foreach ($item['children'] as $itemChild) {
					$provincesCitiesOfVietnam[$item['value']][$itemChild['value']] = $itemChild['label'];
				}
			}
			else
			{
				$provincesCitiesOfVietnam[$item['value']] = $item['label'];
			}
		}

		return $provincesCitiesOfVietnam;
	}
}

if (!function_exists('formatValueLabel')) {
	
	/**
	 * @todo Hàm hỗ trợ format danh sách Province
	 * @param datas: array province lấy từ database
	 * @return array
	*/
	function formatValueLabel($datas, $valueBefore = true)
	{
		$arrReturn = array();
		if($valueBefore)
		{
			foreach ($datas as $key => $item) {
				if($item['children'])
				{
					foreach ($item['children'] as $itemChild) {
						$arrReturn[$item['value']][$itemChild['value']] = $itemChild['label'];
					}
				}
				else
				{
					$arrReturn[$item['value']] = $item['label'];
				}
			}
		}
		else
		{
			foreach ($datas as $item) {
				if($item['children'])
				{
					foreach ($item['children'] as $itemChild) {
						$arrReturn[$item['label']][$itemChild['label']] = $itemChild['value'];
					}
				}
				else
				{
					$arrReturn[$item['label']] = $item['value'];
				}
			}
		}
		return $arrReturn;
	}
}

if (!function_exists('previousUrl')) {
	
	/**
	 * @todo Hàm hỗ trợ back về trang trước
	 * @return true | false
	*/
	function previousUrl()
	{
		$baseUrl = config('app.url');
		$urlPrevious = $_SERVER["HTTP_REFERER"];
		if( $urlPrevious && preg_match("|" . preg_quote($baseUrl, "|") . "|", $urlPrevious) ) {

			return $urlPrevious;
		}

		return $baseUrl;
	}
}

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

// if (!function_exists('goBack')) {
	
// 	/**
// 	 * @todo Hàm hỗ trợ back về trang trước
// 	 * @return true | false
// 	*/
// 	function goBack()
// 	{
// 		$baseUrl = config('app.url');
// 		$urlPrevious = $_SERVER["HTTP_REFERER"];
// 		if( $urlPrevious && preg_match("|" . preg_quote($baseUrl, "|") . "|", $urlPrevious) ) {

// 			dd( $urlPrevious );
// 			return redirect( $urlPrevious );
// 		}

// 		return false;
// 	}
// }