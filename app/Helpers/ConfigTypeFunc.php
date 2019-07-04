<?php
//use ServiceManager;
if (!function_exists('getConfigType')) {
	
	/**
	 * @todo Hàm hỗ trợ lấy danh sách config
	 * @param code: tên cần lấy
	 * @param parentOnly: chỉ lấy parent
	 * @param locale: ngôn ngữ
	 * @return array
	*/
	function getConfigType($code, $parentOnly = false, $locale = null)
	{

		// format name
		$code = explode( ".", $code );
		$i = count( $code ) - 1;
		$name = $code[ $i ];
		array_splice($code, $i, 1);
		$code = implode( ".", $code );

		// khởi tạo service
		$serviceResponse = app('service.manager')->make("Constant.ConfigTypeService")->getArrayList($code, $name, $parentOnly, $locale);
		return $serviceResponse->getData();
	}
}

if (!function_exists('convertConfigTypeAliasToValue')) {
	
	/**
	 * @todo Hàm hỗ trợ chuyển config từ alias sang value
	 * @param code: tên cần lấy
	 * @param alias: chỉ lấy parent
	 * @param aliasParent: parent nếu có
	 * @return array
	*/
	function convertConfigTypeAliasToValue($code, $alias, $aliasParent = "")
	{

		// format name
		$code = explode( ".", $code );
		$i = count( $code ) - 1;
		$name = $code[ $i ];
		array_splice($code, $i, 1);
		$code = implode( ".", $code );

		$alias = str_slug( $alias );
		$aliasParent = str_slug( $aliasParent );

		// khởi tạo service
		$serviceResponse = app('service.manager')->make("Constant.ConfigTypeService")
								->convertAliasToValue($code, $name, $alias, $aliasParent);
		return $serviceResponse->getData();
	}
}

if (!function_exists('convertConfigTypeValueToLabel')) {
	
	/**
	 * @todo Hàm hỗ trợ chuyển config từ alias sang value
	 * @param code: tên cần lấy
	 * @param alias: chỉ lấy parent
	 * @param aliasParent: parent nếu có
	 * @return array
	*/
	function convertConfigTypeValueToLabel($code, $value, $valueParent = "")
	{

		// format name
		$code = explode( ".", $code );
		$i = count( $code ) - 1;
		$name = $code[ $i ];
		array_splice($code, $i, 1);
		$code = implode( ".", $code );

		// khởi tạo service
		$serviceResponse = app('service.manager')->make("Constant.ConfigTypeService")
								->convertValueToLabel($code, $name, $value, $valueParent);
		return $serviceResponse->getData();
	}
}

if (!function_exists('convertConfigTypeValueToAlias')) {
	
	/**
	 * @todo Hàm hỗ trợ chuyển config từ value sang alias
	 * @param code: tên cần lấy
	 * @param alias: chỉ lấy parent
	 * @param aliasParent: parent nếu có
	 * @return array
	*/
	function convertConfigTypeValueToAlias($code, $value, $valueParent = "")
	{
   
		// format name
		$code = explode( ".", $code );
		$i = count( $code ) - 1;
		$name = $code[ $i ];
		array_splice($code, $i, 1);
		$code = implode( ".", $code );

		// khởi tạo service
		$serviceResponse = app('service.manager')->make("Constant.ConfigTypeService")
			->convertValueToAlias($code, $name, $value, $valueParent);
		return $serviceResponse->getData();
	}
}

if (!function_exists('convertConfigTypeLabelToValue')) {
	
	/**
	 * @todo Hàm hỗ trợ chuyển config từ label sang value
	 * @param code: tên cần lấy
	 * @param alias: chỉ lấy parent
	 * @param aliasParent: parent nếu có
	 * @return array
	*/
	function convertConfigTypeLabelToValue($code, $label, $labelParent = "")
	{
   
		// format name
		$code = explode( ".", $code );
		$i = count( $code ) - 1;
		$name = $code[ $i ];
		array_splice($code, $i, 1);
		$code = implode( ".", $code );

		// khởi tạo service
		$serviceResponse = app('service.manager')->make("Constant.ConfigTypeService")
			->convertLabelToValue($code, $name, $label, $labelParent);
		return $serviceResponse->getData();
	}
}

if (!function_exists('getConfigTypeRegion')) {
	
	/**
	 * @todo Hàm hỗ trợ lấy mảng config data modal khu vực
	 * @param module_name: module cần lấy
	 * @param multi: modal multi hoặc single
	 * @return array
	*/
	function getConfigTypeRegion($module_name, $multi = false)
	{

		$region = getConfigType( $module_name . ".region" );

		// nếu đường biển
		if( $module_name == "seas" ) {

			// phần tử tất cả
			if( $multi ) {

				array_unshift( $region, [
					"label"         => trans("seas::modalLabel.allPort"),
					"value"         => "",
					"isSelectAll"   => true,
					"autoCheck"		=> true
				] );
			}
	
			foreach( $region as &$value ) {
				unset( $value["alias"] );
	
				// nếu có phần tử con
				if( is_array( $value["children"] ) && count( $value["children"] ) ) {

					if( $value["value"] == "Vietnam" ) {

						$value["hideLabel"] = true;
					}


					// phần tử tất cả
					if( $multi ) {
						array_unshift( $value["children"], [
							"label"         => trans("seas::modalLabel.allPortOf", ["port" => $value["label"]]),
							"value"         => "All",
							"isSelectAll"   => true,
							"autoCheck"		=> true
						] );
					}

					$other = [
						"label"         => trans("seas::modalLabel.otherPort", ["port" => $value["label"]]),
						"value"         => "Other"
					];
					if( !$multi ) {

						$other["value"] = "";
						$other["isOther"] = true;
					}

					
					// phần tử other
					$value["children"][] = $other;

					$hotArr = config('seas.provincesCitiesOfWorldIsHot');
					if( $hotArr && is_array( $hotArr ) ) {

						foreach( $value["children"] as &$child ) {
	
							if( in_array( $child["value"], $hotArr ) ) {

								$child["isHot"] = true;
							}
						}
					}
				}
			}
		} else {
			
			// phần tử tất cả
			if( $multi ) {
				array_unshift( $region, [
					"label"         => "Toàn quốc",
					"value"         => "",
					"isSelectAll"   => true,
					"autoCheck"		=> true
				] );
			}
	
			foreach( $region as &$value ) {

				unset( $value["alias"] );
	
				// nếu có phần tử con
				if( is_array( $value["children"] ) && count( $value["children"] ) ) {

					$value["hideLabel"] = true;

					// phần tử tất cả
					if( $multi ) {
						array_unshift( $value["children"], [
							"label"         => "Tất cả",
							"value"         => "Tất cả",
							"isSelectAll"   => true,
							"autoCheck"		=> true
						] );
					}
					
					$other = [
						"label"         => "Tỉnh khác",
						"value"         => "Tỉnh khác"
					];
					if( !$multi ) {

						$other["value"] = "";
						$other["isOther"] = true;
					}
	
					// phần tử other
					$value["children"][] = $other;
				}
			}
			
		}

		return $region;
	}
}