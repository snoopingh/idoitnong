<?php
    header('Content-Type:application/json; charset=utf-8');
    include $_SERVER["DOCUMENT_ROOT"]."/res/dbcon.php";
	
    if(!$_GET['value'] || !$_GET['type'] || !$_GET['authkey']) die (json_encode(array('ok' => false, 'msg' => "인수가 부족합니다.")));
    if($authKey != $_GET['authkey']) die (json_encode(array('ok' => false, 'msg' => "인증키가 바르지 않습니다.")));

    $query  = "INSERT INTO `farm_taemen`(`type`, `value`, `date`)";
    $query .= " VALUES('".$_GET['type']."', '".$_GET['value']."', now())";

    mysql_query($query, $conn) or die (json_encode(array('ok' => false, 'msg' => "오류가 발생하였습니다.\n오류 코드 : ".mysql_errno())));
    echo json_encode(array('ok' => true));
?>