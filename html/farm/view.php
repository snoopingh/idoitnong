<!--
참고
http://blog.naver.com/skawo32167/220495589498
http://blog.naver.com/blogpyh/220095293310
http://blog.naver.com/ivory82/40208193734
-->
<!DOCTYPE html>
<html>
<head>
<title>Test</title>
<meta charset = "UTF-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
<link rel="stylesheet" type="text/css" href="design.css" />
<script type = "text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type = "text/javascript">
    function printData(sAct, iPage)
    {
			$.ajax({
            type: "GET",
            url: "reqdata.php",
            data: { act: sAct, page: iPage },
            dataType: "json",
            success: function(responseData)
            {
                var result = eval(responseData);

                if(result.responseCode == "OK")
                {
                    if(sAct == "last")
                    {
                        $("#main").append("<div id = 'sub_main'><span id = 'mdh'>현재 온도 : " + responseData.data.temp + "℃<br /></span><span id = 'reqtime'>데이터가 기록된 시간 : " + responseData.data.regi_date + "</span></div>");
                    }
                    else if(sAct == "average")
                    {
                      var mData = result.data;
                      for(var i = 0; i < mData.length; i++)
                          $("#main").append("<div id = 'sub'><span id = 'mdh'>" + mData[i].mdh.replace("H", "시").replace(" ", "일 ").replace("-", "월 ") + "</span>(" + mData[i].rowNum + "건의 데이터가 수집됨)<hr />실내 평균온도&nbsp;A&nbsp;:&nbsp;" + mData[i].avg_dsit + "℃</div>");
                    }
                }
                else if(result.responseCode == "NG")
                {
                    $("#main").append(result.message);
                }
            },
            error: function(request, status, error)
            {
                alert(status);
            }
        });

      }
</script>
</head>
<body onload = "<?php if(!$_GET[act]) echo "printData('last'); printData('average');" ?>">
<div id = 'main'>
</div>
</body>
</html>
