<?php
/**
 * BizProduct Class
 *
 */
include _CLASS_DIR."/class.UtilFile.php";
class BizProduct extends UtilFile {
	private $objDBH;
	private $arrQuery = array();
    private $tableCategory = 'category';
    private $tableProduct = 'product';
    private $strFileName = array();
    private $strFileName2 = array();
	private $strProductCode;

	function __construct($obj) {
		$this->objDBH = $obj;
	}

	/************************* 카테고리 *************************/
    // order code 가져오기
    function getOrderCode($table) {
        $arrReturn = $this->objDBH->getRow("select max(order_code)+1 as order_code from ".$table);
        if(empty($arrReturn['order_code'])) { $arrReturn['order_code'] = 1;}
        return $arrReturn['order_code'];
    }

    // 정보 가져오기
    function infoCategory($reqData) {
        $arrReturn = $this->objDBH->getRow("select * from ".$this->tableCategory." where code='".$reqData['code']."'");
        return $arrReturn;
    }

    function infoCategoryName($reqData) {
        $arrReturn = $this->objDBH->getRow("select title from ".$this->tableCategory." where category_code='".$reqData['category_code']."'");
        return $arrReturn['title'];
    }

    // 리스트 가져오기
    function listCategory($reqData) {
        $category_code = !empty($reqData['category_code']) ? $reqData['category_code'] : "";
        $category_length = strlen($category_code);
        $category_length += 2;
        $where = " where category_code like '".$category_code."%' and length(category_code)=".$category_length;

        $arrReturn['list_query'] = "select code,category_code,order_code,title,status from ".$this->tableCategory.$where." order by order_code";
        return $arrReturn;
    }

	// 카테고리 등록
	function insertCategory($reqData) {
		$order_code = $this->getOrderCode($this->tableCategory);

		// 순서대로 코드 찾아주기
		$category_length = strlen($reqData['category_code']);
		$category_length += 2;
		$current_category_code = $reqData['category_code']."10";

        $arrData = $this->objDBH->getRows("select category_code from ".$this->tableCategory." where length(category_code)='".$category_length."' and category_code like '".$reqData['category_code']."%' order by category_code");
        if (!empty($arrData['list'])) {
            foreach($arrData['list'] as $key => $val) {
                if($current_category_code != $val['category_code']) {
                    break;
                }
                $current_category_code++;
            }
        }
        if($current_category_code == 100) {
            putJSMessage("항목개수는 90개를 초과할수 없습니다.");
            exit;
        }

         $arrParam = array (
            'category_code' => $current_category_code,
            'order_code'    => $order_code,
            'title'         => $reqData['title'],
            'status'        => $reqData['status']
        );
        $this->objDBH->insert($this->tableCategory, $arrParam);
        $code = $this->objDBH->getLastId();
		return $code;
	}

	// 카테고리 수정
	function updateCategory($reqData) {
        $arrParam = array (
            'title'         => $reqData['title'],
            'status'        => $reqData['status']
        );
        $arrWhere = array(
            'code' => $reqData['code']
        );
        $this->objDBH->update($this->tableCategory, $arrParam, $arrWhere);
	}

	// 카테고리 삭제
	function deleteCategory($reqData) {
        // 하위 카테고리 존재 여부 체크
        $arrData = $this->objDBH->getRows("select category_code,title from ".$this->tableCategory." where code in (".$reqData['code'].")");
        if (!empty($arrData['list'])) {
            foreach($arrData['list'] as $key => $val) {
                $category_length = strlen($val['category_code']);
                $category_length += 2;
                $count = $this->objDBH->getNumRows("select * from ".$this->tableCategory." where category_code like '".$val['category_code']."%' and length(category_code) = ".$category_length);
                if ($count > 0) {
                    putJSMessage("[".$val['title']."] 하위 카테고리가 존재하므로 삭제가 불가능합니다.\\n\\n하위 카테고리를 먼저 삭제 하세요.","dialog_list_reload");
                    exit;
                }
            }
        }

        $query = "delete from ".$this->tableCategory." where code in (".$reqData['code'].")";
		$this->objDBH->query($query);
	}

	/************************* 상품 *************************/
	// 파일체크
	function _checkFile() {
		$this->setFiles();
		for($i=1; $i<=11; $i++) {
			if ($_FILES['file'.$i]['name']) {	// 파일첨부시
				$arrResultFile = $this->checkFile("Img","file".$i);
				if($arrResultFile['status'] == "FAIL") {
					putJSMessage("[".$arrResultFile['message']."]","back");
					exit;
				}
				else {
					$this->strFileName[$i] = strtolower($_FILES['file'.$i]['name']);
				}
			}
		}
        // 제품사양서
        if ($_FILES['file12']['name']) {	// 파일첨부시
            $arrResultFile = $this->checkFile("File","file12");
            if($arrResultFile['status'] == "FAIL") {
                putJSMessage("[".$arrResultFile['message']."]","back");
                exit;
            }
            else {
                $arrFileName = explode(".", $_FILES['file12']['name']);
		        $file_ext = strtolower(array_pop($arrFileName));
                $this->strFileName2 = '12.'.$file_ext;
            }
        }
	}

	// 파일첨부
	function _uploadFile() {
		if (count($this->strFileName) > 0) {
			foreach($this->strFileName as $key => $val) {
				$this->uploadFile("file".$key,_USER_DIR."/product/".$this->strProductCode."_".$key);
			}
		}
        // 제품사양서
        if (!empty($this->strFileName2)) {
            $this->uploadFile("file12",_USER_DIR."/product/".$this->strProductCode."_".$this->strFileName2);
            $arrParam = array (
                'file_name' => $this->strProductCode.'_'.$this->strFileName2,
            );
            $arrWhere = array(
                'code' => $this->strProductCode
            );
            $this->objDBH->update($this->tableProduct, $arrParam, $arrWhere);
		}
	}

    // 정보 가져오기
    function infoProduct($reqData) {
        $arrReturn = $this->objDBH->getRow("select * from ".$this->tableProduct." where code='".$reqData['code']."'");
        // 파일표출
        for($i=1; $i<=11; $i++) {
            if(file_exists(_USER_DIR."/product/".$reqData['code']."_".$i)) {
                $arrFile[$i] = _USER_URL."/product/".$reqData['code']."_".$i."?dummy=".getDummy();
            }
        }
        if (!empty($arrFile)) {
            $arrReturn['files'] = $arrFile;
        }
        if (!file_exists(_USER_DIR."/product/".$arrReturn['file_name'])) {
            $arrReturn['file_name'] = NULL;
        }

        // 카테고리명 구하기
        $loop = strlen($arrReturn['category_code']);
        $strCategoryDepth = "";
        for($i=2; $i<=$loop; $i=$i+2) {
            $tmp_category_code = substr($arrReturn['category_code'],0,$i);
            $arrCategory = $this->objDBH->getRow("select category_code,title from category where category_code='".$tmp_category_code."'");
            $strCategoryDepth .= " > ".$arrCategory['title'];
        }
        $arrReturn['category_depth'] = $strCategoryDepth;

        return $arrReturn;
    }

    // 리스트 가져오기 : list_query 값으로 넘기면 parent 단에서 displayDataList() 실행
    function listProduct($reqData) {
        $add_where = '';
        if(!empty($reqData['category_code'])) $add_where .= " and category_code like '".$reqData['category_code']."%'";
        if(!empty($reqData['status'])) $add_where .= " and status = '".$reqData['status']."'";
        if(!empty($reqData['keyword'])) {
            $add_where .= " and ".$reqData['field']." like '%".$reqData['keyword']."%'";
        }

        $arrReturn['list_query'] = "select code,order_code,title,title_sub,status,date_format(reg_date, '%Y-%m-%d') as reg_date from ".$this->tableProduct." where 1".$add_where." order by order_code desc";
        return $arrReturn;
    }

    // 상품 등록
	function insertProduct($reqData) {
        $order_code = $this->getOrderCode($this->tableProduct);
        $arrParam = array (
            'category_code' => $reqData['category_code'],
            'order_code'    => $order_code,
            'title'         => $reqData['title'],
            'type'          => $reqData['type'],
            'title_sub'     => $reqData['title_sub'],
            'mark'          => $reqData['mark'],
            'certificate_number' => $reqData['certificate_number'],
            'content'       => $reqData['content'],
            'status'        => $reqData['status'],
            'reg_date'      => "now()"
        );
        $this->objDBH->insert($this->tableProduct, $arrParam);
        $code = $this->objDBH->getLastId();
        $this->strProductCode = $code;

        return $code;
	}

	// 상품 수정
	function updateProduct($reqData) {
        $arrParam = array (
            'title'         => $reqData['title'],
            'type'          => $reqData['type'],
            'title_sub'     => $reqData['title_sub'],
            'mark'          => $reqData['mark'],
            'certificate_number' => $reqData['certificate_number'],
            'content'       => $reqData['content'],
            'status'        => $reqData['status'],
        );
        $arrWhere = array(
            'code' => $reqData['code']
        );
        $this->objDBH->update($this->tableProduct, $arrParam, $arrWhere);
        $this->strProductCode = $reqData['code'];
	}

	// 상품 삭제
	function deleteProduct($reqData) {
		$query = "delete from ".$this->tableProduct." where code in (".$reqData['code'].")";
		$this->objDBH->query($query);

        // code 값 배열로 넘어오는 부분 삭제 하기
		// 파일 삭제
		$arrCode = split(",",$code);
		foreach($arrCode as $key => $val) {
			for($i=1; $i<=5; $i++) {
				$file_full_name = _USER_DIR."/product/".$val."_".$i;
				if($val and file_exists($file_full_name)) {
					$arrResultFile = $this->deleteFile($file_full_name);
					if($arrResultFile['status'] == "FAIL") {
						putJSMessage($arrResultFile['message'],"close");
					}
				}
			}
		}
	}
}
?>
