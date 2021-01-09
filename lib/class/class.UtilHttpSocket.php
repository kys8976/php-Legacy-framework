<?
/**
*
* http socket connection class
* Copyright (c) 2009 Whois Co., System Dept.
*
* @param	int $port : port number
* @param	string $path : url
* @return	void
* @access	public
*/
class UtilHttpSocket {
	var $fp;
	var $host;
	var $port;
	var $path;
	var $variable;

	function __construct($mode) {
		$this->port = 80;
		$this->path = "/";
		$variable = array();

        list($type,$menu) = explode(".",$mode);
        $arrMyConfig = getCFG("APIConfig");
        $arrInfo = $arrMyConfig[$type];

		$this->path = $arrInfo[$menu."_url"];
        if ($this->path == "") {
			putJSMessage("Invalid mode!! [".$mode."]");
            exit;
		}
		$this->host = $arrInfo['host'];
		$this->variable['id']		= $arrInfo['id'];		// 아이디
		$this->variable['password']	= $arrInfo['password'];	// 비밀번호
	}

	function showMessage($message) {
		echo $message;
		exit;
	}

	function setParam($arrReq) {
        foreach($arrReq as $key => $val) {
			$this->variable[$key] = $val;
		}
	}

	function setPort($port) {
		$this->port = $port;
	}

	function open() {
        $this->fp = fsockopen($this->host,$this->port);
		if(!$this->fp) {
			$this->showMessage("Connection Error!");
		}
	}

	function close() {
		fclose($this->fp);
	}

	function getMethod() {
		if($this->variable) {
			$parameter = "?";
			while (list($key, $val) = each($this->variable)) {
				$parameter .= trim($key)."=".urlencode(trim($val))."&";
			}
			$parameter = substr($parameter,0,-1);
		}
		$query  = "GET $this->path$parameter HTTP/1.0\r\n";
		$query .= "Host: $this->host\r\n";
		$query .= "User-agent: PHP/class http 0.1\r\n";
		$query .= "\r\n";
		fputs($this->fp,$query);
		return $query;
	}

	function postMethod() {
        if($this->variable) {
			$parameter = "\r\n";
			while (list($key, $val) = each($this->variable)) {
				$parameter .= trim($key)."=".urlencode(trim($val))."&";
			}
			$parameter = substr($parameter,0,-1);
			$parameter .= "\r\n";
		}
        $query = "POST $this->path HTTP/1.0\r\n";
		$query .= "Host: $this->host\r\n";
		$query .= "Content-type: application/x-www-form-urlencoded\r\n";
		$query .= "Content-length: ".strlen($parameter)."\r\n";
        if($this->variable) $query .= $parameter;
		$query .= "\r\n";
        fputs($this->fp,$query);
		return $query;
	}

	function getHeader($method="get") {
		$this->open();
        $buffer = '';
		if($method == "get") $this->getMethod();
		else if($method == "post") $this->postMethod();

		while(trim(fgets($this->fp,1024)) != "") {
			$buffer .= fgets($this->fp,1024);
		}
		$this->close();
		return $buffer;
	}

	function send($method="post") {
		$this->open();
        $buffer = '';
		if($method == "get") $this->getMethod();
		else if($method == "post") $this->postMethod();

		while(trim(fgets($this->fp,1024)) != "");
		while(!feof($this->fp)) {
			$buffer .= fgets($this->fp,1024);
		}
		$this->close();
        return $this->parserResult($buffer);
	}

	function parserResult($data) {
		return json_decode($data,true);
	}
}
?>