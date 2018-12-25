<?php
namespace App;

class phpEncrypt{
    
    /**
     * 对接java，服务商做的AES加密通过SHA1PRNG算法
     * @param string $string 需要加密的字符串
     * @param string $key 密钥
     * @return string
     */
    public static function aesEncrypt($string, $key)
    {
        // 对接java，服务商做的AES加密通过SHA1PRNG算法（只要password一样，每次生成的数组都是一样的），Java的加密源码翻译php如下：
        $key = substr(openssl_digest(openssl_digest($key, 'sha1', true), 'sha1', true), 0, 16);

        // openssl_encrypt 加密不同Mcrypt，对秘钥长度要求，超出16加密结果不变
        $data = openssl_encrypt($string, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

        $data =  base64_encode( $data  );

        return $data;
    }


    /**
     *   对接java，服务商做的AES加密通过SHA1PRNG算法
     * @param string $string 需要解密的字符串
     * @param string $key 密钥
     * @return string
     */
    public static function aesDecrypt($string, $key)
    {
        //将处理过的字串去除前两位，进行base64解码
        $string = base64_decode( $string );
        // 解密
        $key = substr(openssl_digest(openssl_digest($key, 'sha1', true), 'sha1', true), 0, 16);

        $decrypted = openssl_decrypt($string, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

        return $decrypted;
    }
    
    
    /**
	*	对应java服务DES加密
	*/
	public static function desEncrypt( $data , $key ){
		$cipher = 'DES-ECB';
		
		//加密
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$data = openssl_encrypt( json_encode($data) , $cipher, $key, true, $iv);
		//加密转16进制 转大写
		$data = strtoupper( bin2hex($data) );
		
		return $data;
	}
	
	/**
	*	对应java服务DES解密
	*/
	public static function desDecrypt( $data , $key ){
		$cipher = 'DES-ECB';
        //如果加密使用了转16进制，则返转回来
		$data = pack("H*",$data);
		//加密
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$data = openssl_decrypt( $data , $cipher, $key, true, $iv);
		
		return $data;
	}
    
}


//对接java，服务商做的AES加密通过SHA1PRNG算法
//$encrypt = phpEncrypt::aesEncrypt('123', 'channel');
//$decrypt = phpEncrypt::aesDecrypt($encrypt, 'channel');
//echo "加密后:".$encrypt."\n";
//echo  "解密：".$decrypt;

//对接java，服务商做的DES加密
//$encrypt = phpEncrypt::desEncrypt('123', 'channel');
//$decrypt = phpEncrypt::desDecrypt($encrypt, 'channel');
//echo "加密后:".$encrypt."\n";
//echo  "解密：".$decrypt;