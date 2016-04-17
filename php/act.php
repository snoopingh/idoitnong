<?php
    /*
        파　일　명 : act.php
        기　　　능 : (아두이농) 아두이노에서 값을 받아 DB에 주기적으로 기록을 요청하는 페이지 입니다.
        최초작성일 : 2015-10-01
        최종수정일 : 2016-04-09
        작　성　자 : 방명광

        패치노트
            2015-10-01
                - 기본적인 소스 작성 완료
            2016-04-14
                - 주석 추가 (건드릴 것이 없음 아두이노 소스를 바꿀 수가 없어서..)
    */

    include "./dbcon.php";

    $data['time_ymd'] = date('ymd');
    $data['time_his'] = date('His');
    $data['temp'] = $_GET['temp'];

    $query  = "INSERT INTO `farm`(`time_ymd`, `time_his`, `temp`, `regi_date`)";
    $query .= " VALUES('".$data['time_ymd']."','".$data['time_his']."','".$data['temp']."', now())";

    @mysql_query($query, $conn);
?>
