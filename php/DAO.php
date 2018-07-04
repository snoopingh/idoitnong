<?php
    /*
        파  일  명 : DAO.php
        기      능 : 데이터베이스 접근하여 데이처를 처리하는 함수를 모아놓은 클래스
        최초작성일 : 2018. 06. 26
        작　성　자 : 방명광

        패치노트
            2018-06-26
                - 기본적인 소스 작성
            2018-07-04
                - 삭제 된 checkUserName() 함수 추가
    */

    //DB에 연결
    require_once($_SERVER['DOCUMENT_ROOT'].'/res/dbcon.php');

    class DAO
    {
        //유저등급을 가져오는 함수
        public function getUserGrade($sensorType)
        {
            $query = sprintf("SELECT grade FROM `idin_sensor_type_tbl` WHERE `sensor_type`='%s'",
                         mysql_real_escape_string($sensorType));

            $result = mysql_query($query);

            return mysql_num_rows($result);
        }

        //아이디 중복여부 확인하는 함수
        public function checkUserName($userName)
        {
            $query = sprintf("SELECT id FROM `idin_user_tbl` WHERE `username`='%s'",
                         mysql_real_escape_string($userName));

            $result = mysql_query($query);

            return mysql_num_rows($result);
        }

        //휴대전화 번호 중복여부 확인하는 함수
        public function checkPhoneNumber($phoneNumber)
        {
            $query = sprintf("SELECT id FROM `idin_user_tbl` WHERE `phone_number`='%s'",
                         mysql_real_escape_string($phoneNumber));

            $result = mysql_query($query);

            return mysql_num_rows($result);
        }

        //인증키 생성 (생성규칙 : 아이디+현재시각)
        public function generateAuthKey($userName)
        {
            return strtolower(md5($userName.time()));
        }

        //회원정보를 DB에 기록하는 함수 
        public function inputUser($userName, $passWord, $realName, $farmName, $phoneNumber, $areaCode)
        {
            $query = sprintf("INSERT INTO `idin_user_tbl`(`username`, `password`, `real_name`, `farm_name`, `phone_number`, `area_code`, `auth_key`, `register_date`) VALUES ('%s', '%s', '%s', '%s', '%s', %s, '%s', now())",
                         mysql_real_escape_string($userName),
                         strtolower(md5(mysql_real_escape_string($passWord))),
                         mysql_real_escape_string($realName),
                         mysql_real_escape_string($farmName),
                         mysql_real_escape_string($phoneNumber),
                         mysql_real_escape_string($areaCode),
                         $this->generateAuthKey(mysql_real_escape_string($userName)));

            $result = mysql_query($query);

            if(!$result) {
                return false;
            }

            return true;
        }

        //인증키로 회원 존재여부와 회원 등급을 확인하는 함수
        public function isAllowedUser($authKey)
        {
            $query = sprintf("SELECT id FROM `idin_user_tbl` WHERE `auth_key`='%s' AND `grade`>0",
                         mysql_real_escape_string($authKey));

            $result = mysql_query($query);

            return mysql_num_rows($result);
        }

        //센서타입 존재여부를 확인하는 함수
        public function checkSensorType($sensorType)
        {
            $query = sprintf("SELECT id FROM `idin_sensor_type_tbl` WHERE `sensor_type`='%s'",
                         mysql_real_escape_string($sensorType));

            $result = mysql_query($query);

            return mysql_num_rows($result);
        }

        //같은 센서가 5초 이내에 등록되어 있는지 확인하는 함수 (너무 빠른 등록을 방지)
        public function checkAbuser($authKey, $sensorType)
        {
            $query = sprintf("SELECT COUNT(*) cnt FROM (SELECT * FROM `idin_sensor_tbl` WHERE user_id=(SELECT id FROM `idin_user_tbl` WHERE auth_key='%s') AND sensor_type_id=(SELECT id FROM `idin_sensor_type_tbl` WHERE `sensor_type`='%s')) ppap WHERE ppap.register_date > DATE_ADD(now(), INTERVAL -5 SECOND)",
                         mysql_real_escape_string($authKey),
                         mysql_real_escape_string($sensorType));
            $result = mysql_query($query);

            if(!$result) {
                return false;
            }

            $row = mysql_fetch_object($result);

            return $row->cnt;
        }

        //센서 값을 받아서 DB에 데이터 기록
        public function addSensorData($authKey, $sensorType, $sensorValue)
        {
            $query = sprintf("INSERT INTO `idin_sensor_tbl`(`user_id`, `sensor_type_id`, `sensor_value`, `register_date`) VALUES ((SELECT id FROM `idin_user_tbl` WHERE `auth_key`='%s'), (SELECT id FROM `idin_sensor_type_tbl` WHERE `sensor_type`='%s'), %s, now())",
                         mysql_real_escape_string($authKey),
                         mysql_real_escape_string($sensorType),
                         mysql_real_escape_string($sensorValue));

            $result = mysql_query($query);

            //쿼리문이 성공적으로 실행되었는가?
            if(!$result) {
                die(json_encode(array('ok' => false, 'msg' => 'Failed to add data.')));
            }
        }
    }
?>