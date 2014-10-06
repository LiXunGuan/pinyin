<?php
namespace Lixunguan;

class Pinyin{

	protected $handle = null;
	protected $pinyin = array();
	protected $config = array(
		'delimiter' => '', // 分隔符
	);


	public function __construct(){
		$this->handle = fopen( __DIR__ . '/py.dat', 'rb');
	}


	public static function make($str){
		$pinyin =  new static();
		return $pinyin->convert(iconv('UTF-8', 'GBK', $str));
	}


	public function convert($str){
		$len = strlen($str);
		for ($i=0; $i < $len; $i++) {
			if(ord($str[$i]) > 0x80){
				$this->pinyin[] = $this->getPinyin(substr($str, $i, 2));
				$i++;
			}else{
				$this->pinyin[] = $str[$i];
			}
		}
		return $this;
	}

	public function getPinyin($str){
		if(strlen($str) != 2) return false;

		$high = ord($str[0]) - 0x81;
		$low  = ord($str[1]) - 0x40;
		$off  = ($high<<8) + $low - ($high * 0x40);
		if ($off < 0) return false;
		fseek($this->handle, $off * 8, SEEK_SET);
		$ret = fread($this->handle, 8);
		$ret = unpack('a8py', $ret);
		return substr(trim($ret['py']),0,-1);//不要注音
	}

	/**
	 * 获取拼音首字母
	 * @param  array  $config
	 * @return string
	 */
	public function firstLetter($config = array()){
		if($config) $config = array_merge($this->config, $config);
		$letter = array();
		foreach ($this->pinyin as $key) {
			$letter[] = strtolower(substr($key,0,1));
		}
		return implode($config['delimiter'], $letter);
	}

	/**
	 * 获取完整的拼音
	 * @param  array  $config
	 * @return string
	 */
	public function full($config = array()){
		if($config) $config = array_merge($this->config, $config);
		return implode($config['delimiter'], $this->pinyin);
	}

	public function __destruct(){
		if($this->handle) fclose($this->handle);
		$this->handle = null;
	}
}