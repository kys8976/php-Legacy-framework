<?php
/**
 * Page Class
 *
 */
 class Page {
	private $arrPageInfo;
	private $strLayout;
    private $strLayoutTpl;
	private $strFile;
    private $strFileTpl;
	private $arrScriptFile;
	private $arrScript;
	private $isContinue;
	public $objDBH;
    protected $objClass;
	protected $reqData;
	protected $arrReturnData;
	private $arrQuery;
    public  $arrShowCount;
    public  $arrListData;

	function __construct() {
        $this->objDBH = new DB();
        $rawData = $_POST ? $_POST : $_GET;
        $this->reqData = $this->objDBH->escape($rawData);   // real_escape_string 처리
	}

	function initPage($info) {
		$this->arrPageInfo = $info;
		$this->arrScriptFile = array();
		$this->arrScript = array();
		$this->isContinue = true;
	}

    // layout 파일 지정
	function setLayout($layout) {
		$this->strLayout = $layout;
	}

    // template 파일 수동 지정
	function setFile($file) {
		$this->strFile = $file;
	}

	function addScript($str) {
		$this->arrScript[] = $str;
	}

	function addScriptFile($src) {
		$this->arrScriptFile[] = $src;
	}

	function execute() {
		$this->initialize();
		$this->_initTemplate();
		$this->checkParam();
		$this->checkAuthority();
        if ($this->isContinue) $this->makeJavaScript();
		if ($this->isContinue) $this->process();
		if ($this->isContinue) $content = $this->setDisplay();
		$this->_renderTemplate($content);
	}

	function _initTemplate() {
		$directory  = $this->arrPageInfo['directory'];
        $menu = ($this->strLayout == "") ? $directory[0] : $this->strLayout;
        $file = $this->strFile ? $this->strFile : $this->arrPageInfo['file'];

        $mobile_folder = '';
        if ($directory[0] != 'admin' && $file != 'login_admin' && $directory[0] != 'common') {
            if (@$_SESSION['is_mobile'] == 'y') {   // mobile 접근일때
                $mobile_folder = 'mobile/';
            }
        }

        $this->strLayoutTpl = _TEMPLATE_ROOT."/layout/".$mobile_folder.getLayout($menu)."."._APPL_TEMPLATE_EXTENSION;
        $this->strFileTpl  = _TEMPLATE_ROOT."/".$mobile_folder.implode("/",$directory)."/".$file."."._APPL_TEMPLATE_EXTENSION;
	}

	function _renderTemplate($content = array()) {
        if (!empty($content)) {     // array data => 개별 변수 처리
            foreach($content as $key => $val) {
                ${$key} = $val;
            }
        }

        ob_start();                 // template 파일 buffer 저장
        if (is_readable($this->strFileTpl)) include ($this->strFileTpl);
        $content_for_layout = ob_get_contents();
        ob_end_clean();

        $javascript_for_layout = $this->_getJavaScript();

        include ($this->strLayoutTpl);
	}

	function setLog($content) {
        if (!empty($_GET[_APPL_VARIABLE])) {
            $tpf = $this->objDBH->escape($_GET[_APPL_VARIABLE]);   // real_escape_string 처리
        }
        else $tpf = $_SERVER['PHP_SELF'];

        if (is_array($this->objDBH->arrQuery)) $strQuery = addslashes(implode("\n",$this->objDBH->arrQuery));
        $account_code = getLoginCode() ? getLoginCode() : 1;    // default : member_code 사용

        $arrParam = array (
            'account_code'  => $account_code,
            'file_name'     => $tpf,
            'content'       => $content,
            'query'         => $strQuery,
            'ip'            => getenv("REMOTE_ADDR"),
            'reg_date'      => "now()"
        );
        $this->objDBH->insert('log', $arrParam);
	}

	function _getJavaScript() {
		$arrReturn = array();
        $arrReturn[] = "";
		for ($i=0, $max=count($this->arrScriptFile); $i<$max; $i++) {
			$arrReturn[] = "<script language='javascript' src='".$this->arrScriptFile[$i]."'></script>";
		}

		if (count($this->arrScript) > 0) {
			$arrReturn[] = "<script>".implode("\r\n", $this->arrScript);
			$arrReturn[] = "</script>";
		}
		return implode("\r\n", $arrReturn);
	}

    function loadClass($class_name) {
        $class_file = _CLASS_DIR."/class.".$class_name.".php";
        if (file_exists($class_file)) {
            include_once $class_file;
            $objClass = new $class_name($this->objDBH); // instantiate class
            return $objClass;
        }
        else return false;
    }

	function checkLevel($level) {
        if (getLoginId() == "" or getLoginLevel() > $level) {	// 해당 관리자가 아닐때
			putJSMessage("귀하는 본 사이트에 대한\\n\\n접근 접근 권한이 없습니다.","back");
			exit;
		}
	}

	function checkLogin($level='', $type='') {	// 로그인 여부 체크
        if (!isLogin()) {
            $return_url = urlencode($_SERVER['REQUEST_URI']);
            putJSMessage("", "replace", "?tpf=member/login".$type."&return_url=".$return_url);
		}
        if ($level) $this->checkLevel($level);
	}

	function checkAdmin() {
        // 1:관리자, 2:회원 => 2:부관리자일때는 1=>2로 변경
        $this->checkLogin(1, "_admin");
	}

	function getCurrentUrl($skip_variable='') {
		$current_url = "?"._APPL_VARIABLE."=".@$_GET[_APPL_VARIABLE];
		foreach($this->reqData as $key => $val) {
			if ($key != _APPL_VARIABLE and $key != "page" and $key != $skip_variable) $current_url .= "&".$key."=".@urlencode($val);
		}
		return $current_url;
	}
    // category depth 구하기
    function getCategoryDepth($table, $category_code='', $is_link="y", $length=_CATEGORY_LENGTH) {
        if ($category_code) {
            $loop = strlen($category_code);
            $strCategoryDepth = "";
            for($i=$length; $i<=$loop; $i=$i+$length) {
                $tmp_category_code = substr($category_code,0,$i);
                $arrCategory = $this->objDBH->getRow("select category_code,title from ".$table." where category_code='".$tmp_category_code."'");

                if ($is_link == "y") $strCategoryDepth .= " > <a href=".$this->getCurrentUrl('show_count')."&category_code=".$arrCategory['category_code'].">".$arrCategory['title']."</a>";
                else $strCategoryDepth .= " > ".$arrCategory['title'];
            }
            return $strCategoryDepth;
        }
    }
    function displayCount($link="") {
		$this->arrShowCount[0] = 10;
	}

    function displayDataList($query, $is_main='') {
        $this->arrListData = array();

        if(empty($this->reqData['page'])) $page = 1;    // 현재페이지
		else $page = $this->reqData['page'];
        $print_data_count = !checkAccessMobile() ? _DISPLAY_DATA_COUNT : _DISPLAY_DATA_COUNT_MOBILE;            // 한페이지 표출되는 data 개수
        if (!empty($this->reqData['print_data_count'])) $print_data_count = $this->reqData['print_data_count']; // 표출개수 지정
        $print_page_count = _DISPLAY_PAGE_COUNT;    // 표출되는 page 개수

		$total = $this->objDBH->getNumRows($query);
        $total_page = (int)(($total-1) / $print_data_count)+1;     // 총 페이지 수

        if($total != 0)	{
			$offset = ($page - 1) * $print_data_count;	// 현재페이지 이전까지의 데이타수
			$arrData = $this->objDBH->getRows($query." limit ".$offset.", ".$print_data_count);
            $arrData['total'] = $total;
            $arrData['start_number'] = $total-$offset;

            if ($is_main == 'y') {
                $arrPage[] = '<div class="board-paging">';
                $start_page = (int)(($page - 1) / $print_page_count) * $print_page_count + 1;
                if($total_page - $start_page >= $print_page_count) $end_page = $start_page + $print_page_count - 1;
                else $end_page = $total_page;
                if ($start_page != 1) $arrPage[] = '<a href="'.$this->getCurrentUrl().'" class="paging-btn-first"></a>';
                if($start_page - $print_page_count > 0) {
                    $prev_page = $start_page - 1;
                    $arrPage[] = '<a href="'.$this->getCurrentUrl().'&page='.$prev_page.'" class="paging-btn-prev"></a>';
                }
                $arrPage[] = '<ol>';
                for($i = $start_page; $i <= $end_page; $i++) {
                    $arrPage[] = '<li'; if($page == $i) { $arrPage[] = ' class="on"';} $arrPage[] = '><a href="'.$this->getCurrentUrl().'&page='.$i.'">'.$i.'</a></li>';
                }
                $arrPage[] = '</ol>';
                if($total_page - $start_page >= $print_page_count) $arrPage[] = '<a href="'.$this->getCurrentUrl().'&page='.$i.'" class="paging-btn-next"></a>';
                if ($total_page > $end_page) $arrPage[] = '<a href="'.$this->getCurrentUrl().'&page='.$total_page.'" class="paging-btn-last"></a>';
                $arrPage[] = '</div>';
            }
            else {
                $arrPage[] = '<ul class="pagination" style="margin:0;">';
                $start_page = (int)(($page - 1) / $print_page_count) * $print_page_count + 1;
                if($total_page - $start_page >= $print_page_count) $end_page = $start_page + $print_page_count - 1;
                else $end_page = $total_page;
                if ($start_page != 1) $arrPage[] = '<li class="prev"><a href="'.$this->getCurrentUrl().'">← First</a></li>';
                if($start_page - $print_page_count > 0) {
                    $prev_page = $start_page - 1;
                    $arrPage[] = '<li class="prev"><a href="'.$this->getCurrentUrl().'&page='.$prev_page.'">← Previous</a></li>';
                }
                for($i = $start_page; $i <= $end_page; $i++) {
                    if($page == $i) { $active_tag = ' class="active"';}
                    else { $active_tag = '';}
                    $arrPage[] = '<li'.$active_tag.'><a href="'.$this->getCurrentUrl().'&page='.$i.'">'.$i.'</a></li>';
                }
                if($total_page - $start_page >= $print_page_count) $arrPage[] = '<li class="next"><a href="'.$this->getCurrentUrl().'&page='.$i.'">Next → </a></li>';
                if ($total_page > $end_page) $arrPage[] = '<li><a href="'.$this->getCurrentUrl().'&page='.$total_page.'">Last → </a></li>';
                $arrPage[] = '</ul>';
            }
            $arrData['page'] = implode("\n", $arrPage);
            $arrData['page_count'] = $end_page;
		}
        else {
            $arrData['total'] = 0;
            $arrData['page'] = "";
            $arrData['page_count'] = 0;
        }
        return $arrData;
	}

    // 하위 method들 구현하기
	function displaySearch() {
		foreach($this->arrField as $key => $val) {
			$tpl_row = array();
			$tpl_row['FIELD'] = $key;
			$tpl_row['TITLE'] = $val;
			if ($this->reqData[field] == $key) $tpl_row['SELECT_TAG'] = "selected";
			else $tpl_row['SELECT_TAG'] = "";
			$tpl_list[] = $tpl_row;
		}
		$this->Tpl->setLoop("FIELD_OPTION",$tpl_list);
		$this->Tpl->setVar("KEYWORD",$this->reqData[keyword]);
	}

	function displaySelect($arrData,$variable,$link='') {	// Select(콤보)박스 출력 (배열변수,변수명)
        if ($link) $this->Tpl->setVar("ON_CHANGE_".strtoupper($variable),"OnChange=\"location.href='".$link."'+this.value\"");
		if (is_array($arrData)) {
			foreach($arrData as $key => $val) {
				$tpl_row = array();
				$tpl_row['COMP_VALUE'] = $key;
				$tpl_row['COMP_TITLE'] = $val;
                if ($this->reqData[$variable] == $key) $tpl_row['COMP_TAG'] = " selected";
				else $tpl_row['COMP_TAG'] = "";
				$tpl_list[] = $tpl_row;
			}
			$this->Tpl->setLoop("SELECT_".strtoupper($variable),$tpl_list);
		}
	}

	function displayRadio($arrData,$variable) {	// 라디오 버튼 출력 (배열변수,변수명)
        if (is_array($arrData)) {
			foreach($arrData as $key => $val) {
				$tpl_row = array();
				$tpl_row['COMP_VALUE'] = $key;
				$tpl_row['COMP_TITLE'] = $val;
				if ($this->reqData[$variable] == $key) $tpl_row['COMP_TAG'] = " checked";
				else $tpl_row['COMP_TAG'] = "";
				$tpl_list[] = $tpl_row;
			}
            $this->Tpl->setLoop("RADIO_".strtoupper($variable),$tpl_list);
		}
	}

	function displayCheckbox($arrData,$variable) {// 체크박스 출력 (배열변수,변수명)
		if (is_array($arrData)) {
			$arrDefault = split(",",$this->reqData[$variable]);
			foreach($arrData as $key => $val) {
				$tpl_row = array();
				$tpl_row['COMP_VALUE'] = $key;
				$tpl_row['COMP_TITLE'] = $val;
				if (in_array($key,$arrDefault)) $tpl_row['COMP_TAG'] = " checked";
				else $tpl_row['COMP_TAG'] = "";
				$tpl_list[] = $tpl_row;
			}
			$this->Tpl->setLoop("CHECKBOX_".strtoupper($variable),$tpl_list);
		}
	}

	function initialize() {}
	function checkParam() {}
	function checkAuthority() {}
	function makeJavaScript() {}
	function process() {}
	function setDisplay() {}
}
?>