<?php
/**
 * Mysql DB Class
 *
 */
class DB {
    private $objDB = null;
    public $arrQuery = array();

    public function dbError($error, $query='') {
        if (@_API == 'y') {
            returnData(_API_FAIL, "오류가 발생하여 실행이 중단되었습니다.");
        }
        else {
            $arrMessage[] = '['.date('Y-m-d H:i:s').']';
            $arrMessage[] = 'Query : '.htmlentities($query);
            $arrMessage[] = 'Error : '.$error;
            $message = implode('<br>', $arrMessage);
        }

        if(defined('_ADMIN_EMAIL')) {
            $headers[] = 'MIME-Version: 1.0\r\n';
            $headers[] = 'Content-type: text/html; charset=UTF-8\r\n';
            $headers[] = 'To: Administraor <'._ADMIN_EMAIL.'>\r\n';
            $headers[] = 'From: '._HOME_URL.' <system@'._LOGIN_URL.'>\r\n';

            mail(_ADMIN_EMAIL, 'Database Error', $message, implode('\r\n',$headers));
        }

        if(defined('_DISPLAY_DEBUG') && _DISPLAY_DEBUG) {
            echo $message;
        }
    }

    public function __construct($DBHost=_DB_IP, $DBUser=_DB_USER, $DBPassword=_DB_PASSWORD, $DBName=_DB_NAME) {
        /*
        mb_internal_encoding( 'UTF-8' );
        mb_regex_encoding( 'UTF-8' );
        */
        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            $this->objDB = new mysqli($DBHost, $DBUser, $DBPassword, $DBName);
            $this->objDB->set_charset( "utf8" );
        } catch (Exception $error) {
            $this->dbError($error);
        }
    }

    public function __destruct() {
        if($this->objDB) {
            $this->objDB->close();
        }
    }

    public function escape($data) {
         if(!is_array($data)) {
             $data = $this->objDB->real_escape_string($data);
         }
         else {
             $data = array_map(array($this, 'escape'), $data);
         }
         return $data;
     }

    public function query($query) {
        $objResult = $this->objDB->query($query);
        if($this->objDB->error) {
            $this->dbError($this->objDB->error, $query);
            return false;
        }
        else {
            $this->arrQuery[] = $query;
            return $objResult;
        }
    }

    // return 1 row
    public function getRow($query) {
        $objResult = $this->query($query);

        if($objResult) {
            $arrReturn = $objResult->fetch_assoc();
            return $arrReturn;
        }
    }

    // return multi rows
    public function getRows($query) {
        $objResult = $this->query($query);

        if($objResult) {
            $arrResult['total'] = $objResult->num_rows;
            $arrList = null;
            while($arrTmp = $objResult->fetch_assoc()) {
                $arrList[] = $arrTmp;
            }
            $arrResult['list'] = $arrList;
            return $arrResult;
        }
    }

    // return rows count
    public function getNumRows($query) {
        $objResult = $this->query($query);
        if($objResult) {
            return $objResult->num_rows;
        }
    }

    // insert
    public function insert($table, $arrParam = array()) {
        if(empty($arrParam)) {
            return false;
        }

        $query = "insert into ". $table;
        $arrField = array();
        $arrValue = array();
        foreach($arrParam as $field => $value) {
            $arrField[] = $field;
            $arrValue[] = $value == 'now()' ? $value : "'".$value."'";
        }
        $fields = '('.implode(',', $arrField).')';
        $values = '('.implode(',', $arrValue).')';

        $query .= $fields .' values '. $values;
        $objResult = $this->query($query);

        if($objResult) {
            return true;
        }
    }

    // table, set variables, where, limit
    public function update($table, $arrParam=array(), $arrWhere=array(), $limit='') {
        if(empty($arrParam)) {
            return false;
        }
        $query = "update ".$table." set ";
        foreach($arrParam as $field => $value) {
            $arrSetData[] = $value == 'now()' ? "`$field`=now()" : "`$field`='$value'";
        }
        $query .= implode(', ', $arrSetData);

        if(!empty($arrWhere)) {
            foreach($arrWhere as $field => $value) {
                $arrClause[] = "$field='$value'";
            }
            $query .= ' where '.implode(' and ', $arrClause);
        }

        if(!empty($limit)) {
            $query .= ' limit '. $limit;
        }

        $objResult = $this->query($query);

        if($objResult) {
            return true;
        }
    }

    // table, where, limit
    public function delete($table, $arrWhere = array(), $limit='') {
        if(empty($arrWhere)) {
            return false;
        }

        $query = "delete from ".$table;
        foreach($arrWhere as $field => $value) {
            $arrClause[] = "$field = '$value'";
        }
        $query .= " where ".implode(' and ', $arrClause);

        if(!empty($limit)) {
            $query .= " limit ". $limit;
        }

        $objResult = $this->query($query);

        if($objResult) {
            return true;
        }
    }

    // check exist table
    public function existTable($name) {
         $objResult = $this->query("select 1 from $name");
         if($objResult !== false) {
             if($objResult->num_rows > 0) {
                 return true;
             }
             else {
                return false;
             }
         }
         else {
            return false;
         }
    }

    // $id = $this->objDB->getLastId();
    public function getSQL() {
        return end($this->arrQuery);
    }

    // get last id by insert
    // $this->objDB->insert('users_table', $user);
    // $id = $this->objDB->getLastId();
    public function getLastId() {
        return $this->objDB->insert_id;
    }

    // get affected count by insert
    // $this->objDB->insert('users_table', $user);
    // $id = $this->objDB->affected();
    public function getAffected() {
        return $this->objDB->affected_rows;
    }
}