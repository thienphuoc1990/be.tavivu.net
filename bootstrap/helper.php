<?php

function make_option($array, $select = 0, $field = 'name'){
	$result = '';
	foreach ($array as $key => $value) {
		if ($value['id'] == $select) {
			$result .= '<option value="'.$value['id'].'" selected="selected">'.$value[$field].'</option>';
		}else{
			$result .= '<option value="'.$value['id'].'">'.$value[$field].'</option>';
		}
	}
	return $result;
}

function make_tree($array, $parent = 0){
	$result = [];
	if (count($array) <= 0) {
		return $result;
	}

	foreach ($array as $key => $value) {
		if ($value->parent_id == $parent) {
			$result[$value->id] = [
				'name'	=>	$value['name'],
				'level'	=>	$value['level'],
				'children'	=>	make_tree($array, $value->id)
			];
		}
	}
	return $result;
}

function format_price($price)
{
	return number_format($price) . ' VND';
}

function format_number($price)
{
	return number_format($price);
}

function list_ids($array){
	$return = array();
	foreach ($array as $key => $value) {
		$return[$value['id']] = $value['name'];
	}
	return $return;
}

function array_to_string($array){
	$return = '';
	foreach ($array as $key => $value) {
		$return .= $key.',';
	}
	$return = rtrim($return, ',');
	return $return;
}

function vn_to_str($str){

	$unicode = array(

		'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

		'd'=>'đ',

		'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

		'i'=>'í|ì|ỉ|ĩ|ị',

		'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

		'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

		'y'=>'ý|ỳ|ỷ|ỹ|ỵ',

		'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

		'D'=>'Đ',

		'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

		'I'=>'Í|Ì|Ỉ|Ĩ|Ị',

		'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

		'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

		'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

	);

	foreach($unicode as $nonUnicode=>$uni){

		$str = preg_replace("/($uni)/i", $nonUnicode, $str);

	}

	return $str;

}

function removeNonDigit($number){
	return preg_replace('/[^0-9]/', '', $number);
}

function get_aspect_ratio($width, $height)
{
	$gcd = function($width, $height) use (&$gcd) {
		return ($width % $height) ? $gcd($height, $width % $height) : $height;
	};
	$g = $gcd($width, $height);
	return $width/$g . ':' . $height/$g;
}