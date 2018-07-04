<?php
    /*
        파  일  명 : view.php
        기      능 : DB에 저장 된 값을 JSON으로 뿌려주는 페이지
        최초작성일 : 2018. 06. 26
        작　성　자 : 방명광
        
        패치노트
            2018-06-26
                - 코드 최적화
    */

    header('Content-Type: application/json; charset=UTF-8;');

    //include DAO class
    require_once "DAO.php";

    $dao = new DAO();

    $userName = $_POST['username'];  //사용자 아이디
    $passWord = $_POST['password'];  //사용자 비밀번호
    $realName = $_POST['real_name']; //사용자 이름
    $farmName = $_POST['farm_name']; //농장 이름
    $areaCode =-$_POST['area_code']; //지역 코드

    $query = sprintf("INSERT INTO `idin_user_tbl`(`username`, `password`, `real_name`, `farm_name`, `area_code`, `register_date`) VALUES (%s, %s, %s, %s, %s now())",
                 mysql_real_escape_string($userName),
                 mysql_real_escape_string($passWord),
                 mysql_real_escape_string($realName),
                 mysql_real_escape_string($farmName),
                 mysql_real_escape_string($areaCode));

    $result = mysql_query($query);

    //쿼리문이 성공적으로 실행되었는가?
    if(!$result) {
        die(json_encode(array('ok' => false, 'msg' => 'Failed to add data.')));
    }

    echo json_encode(array('ok' => true));
?>