<?php
    /*
        파  일  명 : add.php
        기      능 : 센서에서 받아온 값을 DB에 추가하는 페이지
        최초작성일 : 2018. 06. 21
        작　성　자 : 방명광
        
        패치노트
            2018-06-21
                - 기본적인 소스 작성
            2018-06-26
                - 코드 최적화
            2018-07-02
                - DAO로 기능을 다 옮김
    */

    //MIME 타입 변경
    header('Content-Type: application/json; charset=UTF-8;');

    require_once 'my_functions.php';
    require_once 'DAO.php';

    $dao = new DAO();

    $authKey     = $_GET['auth_key'];     //인증키
    $sensorType  = $_GET['sensor_type'];  //센서 타입 temp1, temp2···.
    $sensorValue = $_GET['sensor_value']; //센서 값 (정수형)

    //파라미터에 값이 안들어왔다면
    if(empty($authKey) || empty($sensorType) || empty($sensorValue)) {
        my_die('Parameter is empty.');
    }

    //등록 전에 인증키를 검사하여 권한이 있는지 조회한다
    if(!($dao->isAllowedUser($authKey))) {
        my_die('User not found.');
    }

    //센서타입이 존재하는지 찾는다.
    if(!($dao->checkSensorType($sensorType))) {
        my_die('Sensor type not found.');
    }

    //5초안에 등록 된 데이터가 있다면 등록처리 하지 않기
    if($dao->checkAbuser($authKey, $sensorType)) {
        my_die('Registration is too fast.');
    }

    //센서 값 기록
    if($dao->addSensorData($authKey, $sensorType, $sensorValue)) {
        my_die('Failed to add data.');
    }

    echo json_encode(array('ok' => true));
?>