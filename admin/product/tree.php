<?
class ThisPage extends Page {
	private $arrTreeTag;
	private $arrCategoryCode;
	private $arrCategoryTitle;
	private $strMenu;
    private $strCategory;

	function initialize() {
		$this->checkAdmin();
		$this->setLayout("blank");
		$this->strMenu = $this->reqData['menu'];

        switch($this->strMenu) {
            case "list":
            case "category":
            $this->strCategory = "category";
            break;

            case "home":
            $this->strCategory = "category_home";
            break;

            default:
            break;
        }

        // 카테고리 code, title
		$arrData = $this->objDBH->getRows("select code,category_code,title from ".$this->strCategory." order by code");
        if (!empty($arrData['list'])) {
            foreach($arrData['list'] as $key => $val) {
                $this->arrCategoryCode[$val['code']] = $val['category_code'];
                $this->arrCategoryTitle[$val['code']] = $val['title'];
            }
        }
	}

	function checkParam() {
	}

	function makeJavaScript() {
	}

	function getSpace($length) {
		$length -= _CATEGORY_LENGTH * 2;
        $space = "";
		for($i=0; $i<$length; $i++) {
			$space .= "&nbsp;";
		}
	}

	function getSubTree($code) {
		$category_length = strlen($this->arrCategoryCode[$code]);
		$category_length += 2;
		$arrData = $this->objDBH->getRows("select code from ".$this->strCategory." where category_code like '".$this->arrCategoryCode[$code]."%' and length(category_code)='".$category_length."' order by order_code");
        if ($arrData['total'] == 0) {	// 자식카테고리가 없을때
			$this->arrTreeTag .= $this->getSpace($category_length)."['".$this->arrCategoryTitle[$code]."', '?tpf=admin/product/".$this->strMenu."_sub&category_code=".$this->arrCategoryCode[$code]."'],\n";
		}
		else {				// 자식카테고리가 있을때
			$this->arrTreeTag .= $this->getSpace($category_length)."['".$this->arrCategoryTitle[$code]."', '?tpf=admin/product/".$this->strMenu."_sub&category_code=".$this->arrCategoryCode[$code]."',\n";
			foreach($arrData['list'] as $key => $val) {
				$this->getSubTree($val['code']);
			}
			$this->arrTreeTag .= $this->getSpace($category_length)."],\n";
		}
	}

	function process() {
		$arrCategory = $this->objDBH->getRows("select code from ".$this->strCategory." where length(category_code)='"._CATEGORY_LENGTH."' order by order_code");
        if (!empty($arrCategory['list']) > 0) {
            foreach($arrCategory['list'] as $key => $val) {
                $this->getSubTree($val['code']);
            }
        }
	}

	function setDisplay() {
		/*
		$tree_item_tag[] = "	['검색엔진', '?tpf=admin/product/category_sub&category_code=11',";
		$tree_item_tag[] = "		['국내', null,";
		$tree_item_tag[] = "			['DAUM', 'http://www.daum.net'],";
		$tree_item_tag[] = "			['Naver','http://www.naver.com'],";
		$tree_item_tag[] = "			['Empas','http://www.empas.com'],";
		$tree_item_tag[] = "		],";
		$tree_item_tag[] = "		['국외', null,";
		$tree_item_tag[] = "			['Yahoo', 'http://www.yahoo.com'],";
		$tree_item_tag[] = "			['Altavista','http://www.altavista.com'],";
		$tree_item_tag[] = "		],";
        $tree_item_tag[] = "	],";
        $tree_item_tag[] = "	['쇼핑몰', null,";
		$tree_item_tag[] = "		['LGeshop', 'http://www.lgeshop.com'],";
		$tree_item_tag[] = "		['롯데쇼핑','http://www.lotte.com'],";
        $tree_item_tag[] = "	],";
		*/

        $this->arrData['home_url'] = "?tpf=admin/product/".$this->strMenu."_sub";
        $this->arrData['tree_item_tag'] = $this->arrTreeTag;

        return $this->arrData;
	}
}
?>