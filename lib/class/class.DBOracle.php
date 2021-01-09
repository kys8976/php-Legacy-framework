<?php


class DBError {
	function error($title, $sql = "", $err="") {
		echo "
		<fieldset style='border:1 solid hotpink; width:300; padding:15'>
			<legend><font color=red size=2><b>버그리포트</b></font></legend><br>
			<font color=red size=2>
				죄송합니다. 페이지 작업중입니다.<br>
				조속한 시일내에 복구하도록 하겠습니다.
			</font>
		</fieldset><br><br>
		<table width=600>
			<tr><td><font color='red'><b>$title</b></font></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td><font color='red'><b>query</b></font></td></tr>
			<tr><td><pre>$sql</pre></td></tr>
			<tr><td><font color='red'><b>oci error</b></font><br></td></tr>";
			if (is_array($err)) {
				echo "
					<tr><td><table width='100%' border='0' cellpadding='0' cellspacing='0'>";
				foreach ($err as $key=>$value) {
					$value = str_replace("<", "&lt;", $value);
					$value = str_replace(">", "&gt;", $value);
					echo "<tr><td valign='top'><pre>$key</pre></td><td valign='top'><pre>$value</pre></td></tr>";
				}
				echo "</table></td></tr>";
			} else {
				echo "
					<tr><td>". $err ."</td></tr>";
			}
		echo "
		</table>";
		exit;
	}
}

class DB extends DBError {
	var $DBName;
	var $DBUser;
	var $DBPassword;
	var $DBHost;
	var $dbh;
	var $stmt;
	var $sql;

	// 오라클 DB 접속
	function __construct($DBName='',$DBUser='',$DBPassword='',$DBHost='localhost',$DBPort='1521') {
		$DB = "( DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = $DBHost)(PORT = $DBPort)) ) (CONNECT_DATA = (SID = $DBName)) )";
		$this->dbh = OCILogOn($DBUser, $DBPassword, $DB, 'KO16MSWIN949');

		if (!$this->dbh) {
			$this->error("DB Connect Fail");
		}
	}

	// 쿼리
	function query($sql, $execute = true) {
		$this->stmt = @OCIParse($this->dbh, trim($sql));
		if(!$this->stmt) { $this->error("DB Parse Fail", $sql);}

		if ($execute) {
			$err = @OCIExecute($this->stmt);
			if (!$err) {
				$this->error("DB Execute Fail", $sql);
			}
		}
		$this->sql = $sql;
	}

	function bindByName($ph_name, &$variable, $length = -1) {
		$err = OCIBindByName($this->stmt, ':'. $ph_name, $variable, $length);
		if (!$err) {
			$this->error("DB bindByName Fail", $this->sql ."<br>ph_name : $ph_name", OCIError());
		} else {
		//	print $ph_name ." : ". $variable ."<br>";
		}
	}

	function bindArrayByName($ph_name, &$var_array, $max_table_length, $length = -1, $type = sqlT_CHR) {
		$err = oci_bind_array_by_name($this->stmt, ':'. $ph_name, $var_array, $max_table_length, $length, $type);
		if (!$err) {
			$this->error("DB bindArrayByName Fail", $this->sql ."<br>ph_name : $ph_name", OCIError());
		} else {
		//	print $ph_name ."<br>";
		}
	}

	function execute() {
		$err = @OCIExecute($this->stmt);
		if (!$err) {
			$this->error("DB Execute Fail", $this->sql, OCIError($this->stmt));
		}
	}
	// 패치 (row 단위로 가져온다)
	function fetch() {
		if(@OCIFetchinto($this->stmt, &$value, OCI_ASSOC+OCI_RETURN_NULLS )) {
			return $value;
		}
		else {

		}{
			@OCIFreeStatement($this->stmt);
			return false;
		}
	}

	function closeDB() {		// DB를 닫는다.
		return @OCILogoff($this->dbh);
	}

	function numCols() {		// 적용된 칼럼의 개수를 구한다.
		return OCINumCols($this->stmt);
	}

	function columnName($Offset) {	// 칼럼의 이름을 구한다.
		return OCIColumnName($this->stmt,$Offset);
	}

	function rowCount()	{	// Row의 개수를 구한다. (Fetch하는 중에 뽑는다.)
		return OCINumRows($this->stmt);
	}

	function result() {		//
		return @OCIResult($this->stmt);
	}

	function sql() {
		echo $this->sql."<br>";
	}

	function free()	{		// 사용한 자원을 해제 한다.
		@OCIFreeStatement($this->stmt);
	}

	/*
	function SeekSet($Num)
	{
		OCI_SEEK_SET($this->stmt,$Num);
	}

	function SeekCur($Num)
	{
		OCI_SEEK_CUR($this->stmt,$Num);
	}

	function SeekEdn($Num)
	{
		OCI_SEEK_END($this->stmt,$Num);
	}
	*/
}
?>