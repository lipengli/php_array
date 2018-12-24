<?php
namespace App;

class phpImg{
    
    /**
	*	将本地图片转为base64编码
    *   
    *   $image_file 文件名称
    *  用法： 
    *   $base64_img = self::base64EncodeImage($img_file);
    *   echo '<img src="'.$base64_img.'"/>';
	*/
    public static function base64EncodeImage ($image_file) {
        $base64_image = '';
        $image_info = getimagesize($image_file);
        
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }
}
