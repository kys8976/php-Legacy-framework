<?php
include("../lib/config/config.php");
include_once _CLASS_DIR."/class.UtilFile.php";

if ($_FILES["upload"]["size"] > 0) {
    $objFile = new UtilFile();

    $file_path = $objFile->getFilePath();
    $file_ext = strtolower(substr(strrchr($_FILES["upload"]["name"],"."),1));
    $file_name = getMicrotime().".".$file_ext;
    $dn_file = _USER_DIR.$file_path."/".$file_name;
    //php 파일업로드하는 부분
    if($file_ext=="jpg" or $file_ext=="gif" or $file_ext =="png"){
        if(move_uploaded_file($_FILES['upload']['tmp_name'], $dn_file)) {
            $upload_file = _USER_URL.$file_path."/".$file_name;
        }
    }else{
        echo "<script type='text/javascript'>alert('jpg,gif,png파일만 업로드가능합니다.');</script>;";
    }

} else {
    exit;
}

echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(".$_GET['CKEditorFuncNum'].", '".$upload_file."');</script>";
?>