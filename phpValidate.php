<?php
namespace App;

class phpValidate{
	/**
	*	身份证解析
    *   将证件号转换为用户生日及性别
    *   
	*/
	public static function identityInfo( $idcard ){
		if( strlen( $idcard ) == '15' ){
			$idcard = self::getIDCard( $idcard );
		}
		$birth = date("Y-m-d",strtotime( substr($idcard, 6, 8) ));
		$sex = substr($idcard, -2 , 1) % 2 ? '1' : '2'; //1为男 2为女
		return ['birth'=>$birth,'sex'=>$sex];
	}
    
    
	/**
     * 功能：把15位身份证转换成18位
     *
     * @param string $idCard 证件号
     * @return 返回一个18位的证件号
     */
    public static function getIDCard($idCard) {
        // 若是15位，则转换成18位；否则直接返回ID
        if (15 == strlen( $idCard )) {
            $W = array (7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1 );
            $A = array ("1","0","X","9","8","7","6","5","4","3","2" );
            $s = 0;
            $idCard18 = substr ( $idCard, 0, 6 ) . "19" . substr ( $idCard, 6 );
            $idCard18_len = strlen ( $idCard18 );
            for($i = 0; $i < $idCard18_len; $i ++) {
                $s = $s + substr ( $idCard18, $i, 1 ) * $W [$i];
            }
            $idCard18 .= $A [$s % 11];
            return $idCard18;
        } else {
            return $idCard;
        }
    }
    
    
	/**
     * 功能：身份证校验
     *
     * @param string $idCard 证件号
     * @return newid or id
     */
	public static function validateIdCard( $idCard ){
        //15位和18位身份证号码的正则表达式
        $regIdCard = "/(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)|(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)/";


        //如果通过该验证，说明身份证格式正确，但准确性还需计算
        if (preg_match($regIdCard,$idCard)) {
            if(strlen($idCard) == 15) {
                $idCard = self::getIDCard($idCard);
            }
            //echo $idCard;
            if (strlen($idCard) == 18) {
                $idCardWi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); //将前17位加权因子保存在数组里
                $idCardY = array(1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2); //这是除以11后，可能产生的11位余数、验证码，也保存成数组
                $idCardWiSum = 0; //用来保存前17位各自乖以加权因子后的总和
                for ($i = 0; $i < 17; $i++) {
                    $idCardWiSum += substr($idCard , $i,  1) * $idCardWi[$i];
                }
                
                $idCardMod = fmod(floatval($idCardWiSum),11);//计算出校验码所在数组的位置
            
                $idCardLast = substr($idCard,17);//得到最后一位身份证号码

                //如果等于2，则说明校验码是10，身份证号码最后一位应该是X
                if ($idCardMod == 2) {
                    if ($idCardLast == "X" || $idCardLast == "x") {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    //用计算出的验证码与最后一位身份证号码匹配，如果一致，说明通过，否则是无效的身份证号码
                    if ($idCardLast == $idCardY[$idCardMod]) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else{
                //临时验证规则
                return false;
            }
        } else {
            return false;
        }
				
		
		
	}
	
	/**
	*	数字切换
	*/
	public static function changeWeekNum( $num ){
		$arr = array('1'=>'一', '2'=>'二' , '3'=>'三' , '4'=>'四' , '5'=>'五' , '6'=>'六' , '7'=>'日'  );
		return $arr[$num];
	}
	


	/**
	 * 判断pc端还是移动端
	 */
	public static function isMobile()
	{ 
	    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
	    {
	        return true;
	    } 
	    // 如果via信息含有wap则一定是移动设备
	    if (isset ($_SERVER['HTTP_VIA']))
	    { 
	        // 找不到为flase,否则为true
	        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	    } 
	    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
	    if (isset ($_SERVER['HTTP_USER_AGENT']))
	    {
	        $clientkeywords = array ('nokia',
	            'sony',
	            'ericsson',
	            'mot',
	            'samsung',
	            'htc',
	            'sgh',
	            'lg',
	            'sharp',
	            'sie-',
	            'philips',
	            'panasonic',
	            'alcatel',
	            'lenovo',
	            'iphone',
	            'ipod',
	            'blackberry',
	            'meizu',
	            'android',
	            'netfront',
	            'symbian',
	            'ucweb',
	            'windowsce',
	            'palm',
	            'operamini',
	            'operamobi',
	            'openwave',
	            'nexusone',
	            'cldc',
	            'midp',
	            'wap',
	            'mobile'
	            ); 
	        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
	        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
	        {
	            return true;
	        } 
	    } 
	    // 协议法，因为有可能不准确，放到最后判断
	    if (isset ($_SERVER['HTTP_ACCEPT']))
	    { 
	        // 如果只支持wml并且不支持html那一定是移动设备
	        // 如果支持wml和html但是wml在html之前则是移动设备
	        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
	        {
	            return true;
	        } 
	    } 
	    return false;
	}     

	/**
	 * 获取用户的操作系统以及版本
	 */
	public static function Get_Os(){
		$ua = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';//这里只进行IOS和Android两个操作系统的判断，其他操作系统原理一样  
			// return json_encode($ua,true);
   		switch ($ua) {
    		case strpos($ua, 'Android') !== false:
    			# code...
	    		preg_match("/(?<=Android )[\d\.]{1,}/", $ua, $version);  
		        return 'Platform:Android   OS_Version:'.(!empty($version) ? $version[0] : '');  
    			break;
    		case strpos($ua, 'iPhone') !== false:
    			# code...
					preg_match("/(?<=CPU iPhone OS )[\d\_]{1,}/", $ua, $version);  
	       		return 'Platform:iPhone   OS_Version:'.str_replace('_', '.', !empty($version) ? $version[0] : '');
    			break;
    		case strpos($ua, 'iPad') !== false:
    			# code...
	    		preg_match("/(?<=CPU OS )[\d\_]{1,}/", $ua, $version);  
		        return 'Platform:iPad   OS_Version:'.str_replace('_', '.',!empty($version) ? $version[0] : ''); 
    			break;	
    		default:
    			# code...
    			return "未检测到系统版本";
    			break;
    	}
	}
    
	/**  
	 * 获取客户端操作系统信息包括win10  
	 * @param  null  
	 * @author  
	 * @return string   
	 */  
    public static function getplat(){  

		$agent =  isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"";
	    $os = false;  
	    if (preg_match('/win/i', $agent) && strpos($agent, '95'))  
	    {  
	      $os = 'Windows 95';  
	    }  
	    else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90'))  
	    {  
	      $os = 'Windows ME';  
	    }  
	    else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent))  
	    {  
	      $os = 'Windows 98';  
	    }  
	    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))  
	    {  
	      $os = 'Windows Vista';  
	    }  
	    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))  
	    {  
	      $os = 'Windows 7';  
	    }  
	    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))  
	    {  
	      $os = 'Windows 8';  
	    }else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))  
	    {  
	      $os = 'Windows 10';#添加win10判断  
	    }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))  
	    {  
	      $os = 'Windows XP';  
	    }  
	    else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))  
	    {  
	      $os = 'Windows 2000';  
	    }  
	    else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))  
	    {  
	      $os = 'Windows NT';  
	    }  
	    else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent))  
	    {  
	      $os = 'Windows 32';  
	    }  
	    else if (preg_match('/linux/i', $agent))  
	    {  
	      $os = 'Linux';  
	    }  
	    else if (preg_match('/unix/i', $agent))  
	    {  
	      $os = 'Unix';  
	    }  
	    else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))  
	    {  
	      $os = 'SunOS';  
	    }  
	    else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))  
	    {  
	      $os = 'IBM OS/2';  
	    }  
	    else if (preg_match('/Mac/i', $agent) && preg_match('/OS/i', $agent))  
	    {  
	      $os = 'Macintosh';  
	    }  
	    else if (preg_match('/PowerPC/i', $agent))  
	    {  
	      $os = 'PowerPC';  
	    }  
	    else if (preg_match('/AIX/i', $agent))  
	    {  
	      $os = 'AIX';  
	    }  
	    else if (preg_match('/HPUX/i', $agent))  
	    {  
	      $os = 'HPUX';  
	    }  
	    else if (preg_match('/NetBSD/i', $agent))  
	    {  
	      $os = 'NetBSD';  
	    }  
	    else if (preg_match('/BSD/i', $agent))  
	    {  
	      $os = 'BSD';  
	    }  
	    else if (preg_match('/OSF1/i', $agent))  
	    {  
	      $os = 'OSF1';  
	    }  
	    else if (preg_match('/IRIX/i', $agent))  
	    {  
	      $os = 'IRIX';  
	    }  
	    else if (preg_match('/FreeBSD/i', $agent))  
	    {  
	      $os = 'FreeBSD';  
	    }  
	    else if (preg_match('/teleport/i', $agent))  
	    {  
	      $os = 'teleport';  
	    }  
	    else if (preg_match('/flashget/i', $agent))  
	    {  
	      $os = 'flashget';  
	    }  
	    else if (preg_match('/webzip/i', $agent))  
	    {  
	      $os = 'webzip';  
	    }  
	    else if (preg_match('/offline/i', $agent))  
	    {  
	      $os = 'offline';  
	    }  
	    else  
	    {  
	      $os = '未知操作系统';  
	    }  
	    return $os;    
	}
}
