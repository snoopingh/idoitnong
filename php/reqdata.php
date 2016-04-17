<?php
    /*
        파　일　명 : reqdata.php
        기　　　능 : (아두이농) DB저장 된 값 JSON으로 뿌려주기
        최초작성일 : 2015-10-01
        최종수정일 : 2016-04-09
        작　성　자 : 방명광

        패치노트
            2015-10-01
                - 기본적인 소스 작성 완료 (JSON으로 뿌려주기)
                - 코드 최적화
            2015-10-02
                - sql문 오류시 동작 불가능하게 설정
                - responseCode 데이터 추가
            2015-10-07
                - 코드 최적화
            2016-04-09
                - 전체보기 기능 추가

        참고자료
          - http://blog.naver.com/sin_01/70149819023
          - http://blog.naver.com/hongjae83/150079086453
          - http://blog.naver.com/pipecivil/220237006850
          - http://icooh.blog.me/220454156566
          - http://www.json.org/json-ko.html
    */

    header('Content-Type:application/json; charset=utf-8');

    include "./dbcon.php";

    switch($_GET['act'])
    {
        case "last":
            $sql = "SELECT * FROM `farm` ORDER BY `farm`.`no` DESC LIMIT 1";
            $sqlResponse = mysql_query($sql, $conn) or die(json_encode(array('responseCode' => 'NG', 'message' => "데이터를 불러오는데 실패 하였습니다. 다시 시도 하여 주십시오.")));

            $jsonData['responseCode'] = 'OK';
            $jsonData['data'] = mysql_fetch_object($sqlResponse);
            echo json_encode($jsonData);

            break;
//        case "hour":

//            break;

        case "minute":
            $sql = "SELECT * FROM (SELECT DATE_FORMAT(regi_date, '%H:%i') mdh, COUNT(*) rowNum, ROUND(SUM(temp) / COUNT(*),2) avg_temp FROM  `farm`  WHERE time_ymd = ".$_GET['ymd']." AND time_his LIKE '".$_GET['h']."%' GROUP BY DATE_FORMAT(regi_date, '%Y%m%d%H%i')  ORDER BY regi_date DESC limit 60) res ORDER BY res.mdh DESC;";
            $sqlResponse = mysql_query($sql, $conn) or die(json_encode(array('responseCode' => 'NG', 'message' => "데이터를 불러오는데 실패 하였습니다. 다시 시도 하여 주십시오.")));
            $jsonData['responseCode'] = 'OK';
            $jsonData['requestTime'] = date("Y-m-d H:i:s");
            for($i = 0; $row = mysql_fetch_object($sqlResponse); $i++) $jsonData['data'][$i] = $row;
            echo json_encode($jsonData);

            break;
        default:
            echo json_encode(array('responseCode' => 'NG', 'message' => "잘못 된 접근입니다. 다시 시도 하여 주십시오."));
    }
?>
