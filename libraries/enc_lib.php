<?php defined('BASEPATH') OR exit('No direct script access allowed');

class enc_lib
{
	private $cKey;
	private $CI;
	
	function __construct($config = array())
	{
		$this->CI =& get_instance();
	}

	function encode($cData)  
	{
		$this->cKey = $this->CI->config->item('encryption_key');
		$cResult = '';  
		
		for($i = 0; $i < strlen($cData); $i ++)
		{
			$cChar = substr($cData, $i, 1);
			$cKeyChar = substr($this->cKey, ($i % strlen($this->cKey)) - 1, 1);
			$cChar = chr(ord($cChar) + ord($cKeyChar));
			$cResult .= $cChar;
		}
		return $this->encode_base64($cResult);
	}

	function decode($cData)
	{
		$this->cKey = $this->CI->config->item('encryption_key');
		$cResult = '';
		$cData = $this->decode_base64($cData);
		
		for($i = 0; $i < strlen($cData); $i ++)
		{
			$cChar = substr($cData, $i, 1);
			$cKeyChar = substr($this->cKey, ($i % strlen($this->cKey)) - 1, 1);
			$cChar = chr(ord($cChar) - ord($cKeyChar));
			$cResult .= $cChar;
		}
		return $cResult;
	}

	private function encode_base64($cData)
	{
		$cBase64 = base64_encode($cData);
		
		return strtr($cBase64, '+/=', '-_.');
	}

	private function decode_base64($cData)
	{
		$cBase64 = strtr($cData, '-_.', '+/=');
		
		return base64_decode($cBase64);
	}
}