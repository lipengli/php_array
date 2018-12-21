<?php
namespace App;

class phpArray{
	/**
	 * 将一维数组转换为二维数组
	 * @param array $arr 要检测的数组
	 */
	public static function format_array($arr){
		if (count($arr) == count($arr, 1)) {
			$temp_arr = array($arr);
			return $temp_arr;
		}
		return $arr;
	}
    
    /**
    *   排序 支持多字段
    *   $arr  需要排序的数组
    *   $order 排序方式 
    *   使用方法：
    *        arrayOrderBy( array() , ' 字段1 desc , 字段2 asc , 字段3 desc' )
    */
    public static function arrayOrderBy(array &$arr, $order = null) {
        if (is_null($order)) {
            return $arr;
        }
        $orders = explode(',', $order);
        usort($arr, function($a, $b) use($orders) {
            $result = array();
            foreach ($orders as $value) {
                list($field, $sort) = array_map('trim', explode(' ', trim($value)));
                if (!(isset($a[$field]) && isset($b[$field]))) {
                    continue;
                }
                if (strcasecmp($sort, 'desc') === 0) {
                    $tmp = $a;
                    $a = $b;
                    $b = $tmp;
                }
                if (is_numeric($a[$field]) && is_numeric($b[$field]) ) {
                    $result[] = $a[$field] - $b[$field];
                } else {
                    $result[] = strcmp($a[$field], $b[$field]);
                }
            }
            return implode('', $result);
        });
        return $arr;
    }
}
