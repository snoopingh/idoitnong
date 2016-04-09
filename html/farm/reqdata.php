<?php
    /*
        파  일  명 : reqdata.php
        기      능 : (아두이농) DB저장 된 값 JSON으로 뿌려주기
        최초작성일 : 2015-10-01
        최종수정일 : 2015-10-07
        작  성  자 : 방명광
        
        패치노트
            2015-10-01
                - 기본적인 소스 작성 완료 (JSON으로 뿌려주기)
                - 코드 최적화
            2015-10-02
                - sql문 오류시 동작 불가능하게 설정
                - responseCode 데이터 추가
            2015-10-07
                - 코드 최적화
            
        참고자료
          - http://blog.naver.com/sin_01/70149819023
          - http://blog.naver.com/hongjae83/150079086453
          - http://blog.naver.com/pipecivil/220237006850
          - http://icooh.blog.me/220454156566
          - http://www.json.org/json-ko.html
    */
    
    header('Content-Type:application/json; charset=utf-8');
   
    include "dbcon.php";
    
    switch($_GET['act'])
    {
        case "last":
            $sql = "SELECT * FROM `farm` ORDER BY `farm`.`no` DESC LIMIT 1";
            $sqlResponse = mysql_query($sql) or die(json_encode(array('responseCode' => 'NG', 'message' => "데이터를 불러오는데 실패 하였습니다. 다시 시도 하여 주십시오.")));
            
            $jsonData['responseCode'] = 'OK';
            $jsonData['data'] = mysql_fetch_object($sqlResponse);
            $json = json_encode($jsonData);
            echo $json;
            
            break;
        case "average":
		    $sql="SELECT * FROM (SELECT DATE_FORMAT(regi_date, '%m-%d %HH') mdh, COUNT(*) rowNum, ROUND(SUM(temp) / COUNT(*),2) avg_dsit FROM  `farm` GROUP BY DATE_FORMAT(regi_date, '%Y%m%d%H') ORDER BY regi_date DESC limit 12) res ORDER BY res.mdh DESC;";
            //$sql="SELECT * FROM (SELECT DATE_FORMAT(regi_date, '%m-%d %HH') mdh, COUNT(*) rowNum, ROUND(SUM(ds18b20_in_temp) / COUNT(*),2) avg_dsit, ROUND(SUM(ds18b20_out_temp) / COUNT(*),2) avg_dsot, ROUND(SUM(dht22_in_temp) / COUNT(*),2) avg_dhit, ROUND(SUM(dht22_in_humi) / COUNT(*),2) avg_dhih FROM  `farm` GROUP BY DATE_FORMAT(regi_date, '%Y%m%d%H') ORDER BY regi_date DESC limit 12) res ORDER BY res.mdh DESC;";
            $sqlResponse = mysql_query($sql) or die(json_encode(array('responseCode' => 'NG', 'message' => "데이터를 불러오는데 실패 하였습니다. 다시 시도 하여 주십시오.")));            
            $jsonData['responseCode'] = 'OK';
            $jsonData['requestTime'] = date("Y-m-d H:i:s");
            for($i = 0; $row = mysql_fetch_object($sqlResponse); $i++) $jsonData['data'][$i] = $row;
            echo json_encode($jsonData);
            
            break;
        default:
            echo json_encode(array('responseCode' => 'NG', 'message' => "잘못 된 접근입니다. 다시 시도 하여 주십시오."));
    }
?>