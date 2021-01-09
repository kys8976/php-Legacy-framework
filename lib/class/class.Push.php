<?php
/**
 * Account Push
 *
 */
include _CLASS_DIR."/class.UtilJSON.php";
class Push {
	private $objJSON;

    // https://console.developers.google.com/apis/credentials?project=whoisgroupware Server key (ip 등록)
    private $gcmAuth1 = "AIzaSyAA0ABljj3QFVPZZYW6R_Wc9bIK2YLMI9g";  // 기업용
    private $gcmAuth2 = "AIzaSyBmClSicGrbIKkeJ8mJc-0EudifKRft8lI";  // 근로자용

    private $apnsMode = "Dev"; // Dev / Real
    private $apnsDevHost  = "gateway.sandbox.push.apple.com";        // dev
    private $apnsRealHost = "gateway.push.apple.com"; // real
    private $apnsPort = 2195;
    private $apnsCertKey;   // cert key 경로

	function __construct($obj) {
		$this->objDBH = $obj;
        $this->objJSON = new Services_JSON();
        $this->apnsCertKey = _CONFIG_DIR.'/cert_key';
	}

    function send($reqData) {
        checkParam($reqData['member_code'], "member_code");
        checkParam($reqData['message'], "message");

        $arrMember = $this->objDBH->getRow("select code,os,mobile,token_id,type from member where code='".$reqData['member_code']."' and is_push='y' and status='y'");
        if ($arrMember['code']) {
            $reqData['message'] = str_replace("\\r\\n", "\n", $reqData['message']);
            switch($arrMember['os']) {
                case "android":
                $this->_send_android($arrMember['token_id'], $arrMember['type'], $reqData['message'], $reqData['job_code'], $reqData['type']);
                break;

                case "ios":
                $this->_send_ios($arrMember['token_id'], $arrMember['type'], $reqData['message'], $reqData['job_code'], $reqData['type']);
                break;
            }
            return "send completed";
        }
    }

    // android
    function _send_android($token_id, $type, $message, $job_code, $message_type) {
        $data = array(
            'registration_ids' => array($token_id),
            'data' => array('job_code' => $job_code, 'type' => $message_type, 'message' => $message)
        );

        $variable = "gcmAuth".$type;
        $headers = array(
            "Content-Type:application/json",
            "Authorization:key=".$this->{$variable}
        );

        $this->objJSON->encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->objJSON->encode($data));
        $result = curl_exec($ch);
        curl_close($ch);
    }

    // ios
    function _send_ios($token_id, $type, $message, $job_code, $message_type) {
        $variable = "apns".$this->apnsMode."Host";
        $host = $this->{$variable};
        $certKey = $this->apnsCertKey."/".strtolower($this->apnsMode).$type.".pem";

        $body = array();
        // $body['aps'] = array('alert' =>  $message, 'badge' => 0, 'sound' => 'default'); // 보낼 메세지
        $body['aps'] = array('alert' => $message); // 보낼 메세지
        $body['job_code'] = $job_code;
        $body['type'] = $message_type;

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certKey);

        $fp = @stream_socket_client('ssl://'.$host.':'.$this->apnsPort, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
        if (!$fp) {
            returnData(_API_FAIL, "Failed to connect $err $errstr");
        }

        $payload = $this->objJSON->encode($body);
        $msg = chr(0).pack("n",32).pack('H*',$token_id).pack("n",strlen($payload)).$payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);

        if (!$result) returnData(_API_FAIL, "Message not delivered");
    }
}
?>