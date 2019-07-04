<?php
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