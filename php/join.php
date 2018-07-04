<?php
    /*
        파  일  명 : join.php
        기      능 : 회원가입 처리하는 페이지
        최초작성일 : 2018. 06. 26
        작　성　자 : 방명광

        패치노트
            2018-06-26
                - 기본적인 소스 작성
            2018-06-27
                - 소스 코드 최적화
            2018-06-28
                - 각종 유효성검사 추가
            2018-07-04
                - 소스 코드 최적화
    */

    //MIME 타입 변경
    header('Content-Type: application/json; charset=UTF-8;');

    require_once 'my_functions.php';
    require_once 'DAO.php';

    $dao = new DAO();

    $userName    = $_POST['username'];     //사용자 아이디
    $passWord    = $_POST['password'];     //사용자 비밀번호
    $realName    = $_POST['real_name'];    //사용자 이름
    $farmName    = $_POST['farm_name'];    //농장 이름
    $phoneNumber = $_POST['phone_number']; //휴대전화 번호
    $areaCode    = $_POST['area_code'];    //지역 코드

    //아이디 형식 체크 (영소문자, 숫자 / 4-12자)
    if(!preg_match("/^[0-9a-z_-]{4,12}$/u", $userName)) {
        my_die('Invalid ID format.');
    }

    //아이디 중복여부 확인
    if($dao->checkUserName($userName)) {
        my_die('This ID already exists.');
    }

    //비밀번호 형식 체크  (영대·소문자, 숫자, 기호 / 6-24자)
    if(!preg_match("/^[0-9a-zA-Z~!@#$%^&*()-_=+\|`]{6,24}$/u", $passWord)) {
        my_die('Invalid Password format.');
    }

    //이름 형식 체크 (한글 / 2-6자)
    if(!preg_match("/^[가-힣]{2,6}$/u", $realName)) {
        my_die('Invalid Name format.');
    }

    //농장이름 형식 체크 (한글/영문/숫자 / 2-12자)
    if(!preg_match("/^[\w가-힣]{2,12}$/u", $farmName)) {
        my_die('Invalid FarmName format.');
    }

    //휴대전화 번호 형식 체크 (숫자 / 10-11자)
    if(!preg_match("/^\d{10,11}$/u", $phoneNumber)) {
        my_die('Invalid PhoneNumber format.');
    }

    //휴대전화 번호 중복여부 확인
    if($dao->checkPhoneNumber($phoneNumber)) {
        my_die('This PhoneNumber already exists.');
    }

    //회원 등록
    if(!$dao->inputUser($userName, $passWord, $realName, $farmName, $phoneNumber, $areaCode)) {
        my_die('Registration failed.');
    }

    //회원 등록 성공시
    echo json_encode(array('ok' => true));
?>