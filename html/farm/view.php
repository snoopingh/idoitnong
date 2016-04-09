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
    window.onload = function()
    {
			$.ajax({
            type: "GET",
            url: "reqdata.php",
            data: { act: "last"},
            dataType: "json",
            success: function(responseData)
            {
                var result = eval(responseData);
                
                if(result.responseCode == "OK")
                {
					$("#main").append("<div id = 'sub_main'><span id = 'mdh'>현재 온도 : " + responseData.data.temp + "℃<br /></span><span id = 'reqtime'>데이터가 기록된 시간 : " + responseData.data.regi_date + "</span></div>");
					
					/*
                    var mData = result.data;
                    
                    var dsitHLTemp = new Array(mData[0].avg_dsit, mData[0].avg_dsit);
                    var dhitHLTemp = new Array(mData[0].avg_dhit, mData[0].avg_dhit);
                    var temp;
                    
                    for(var i = 0; i < mData.length - 1; i++)
                    {
                        if (dsitHLTemp[0] < mData[i + 1].avg_dsit) dsitHLTemp[0] = mData[i + 1].avg_dsit;
                        if (dsitHLTemp[1] > mData[i + 1].avg_dsit) dsitHLTemp[1] = mData[i + 1].avg_dsit;
                        if (dhitHLTemp[0] < mData[i + 1].avg_dhit) dhitHLTemp[0] = mData[i + 1].avg_dhit;
                        if (dhitHLTemp[1] > mData[i + 1].avg_dhit) dhitHLTemp[1] = mData[i + 1].avg_dhit;
                    }
                    $("#main").append("<div id = 'sub_main'><span id = 'mdh'>12시간 동안의 실내 최고·최저 온도</span><br />실내 A 최고 : <font color = 'red'>" + dsitHLTemp[0] + "℃</font> | 실내 B 최고 : <font color = 'red'>" + dhitHLTemp[0] + "℃</font><br />실내 A 최저 : <font color = 'blue'>" + dsitHLTemp[1] + "℃</font> | 실내 B 최고 : <font color = 'blue'>" + dhitHLTemp[1] + "℃</font><br /><span id = 'reqtime'>데이터 요청시간 : " + result.requestTime + "</span></div>");
                    for(var i = 0; i < mData.length; i++)
                        $("#main").append("<div id = 'sub'><span id = 'mdh'>" + mData[i].mdh.replace("H", "시").replace(" ", "일 ").replace("-", "월 ") + "</span>(" + mData[i].rowNum + "건의 데이터가 수집됨)<hr />실내온도&nbsp;A&nbsp;:&nbsp;" + mData[i].avg_dsit + "℃ | 실내온도&nbsp;B&nbsp;:&nbsp;" + mData[i].avg_dhit + "℃<br />실외온도&nbsp;A&nbsp:&nbsp;" + mData[i].avg_dsot + "℃ | 실내습도&nbsp;B&nbsp;:&nbsp;" + mData[i].avg_dhih + "%</div>");
					*/
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
		
        $.ajax({
            type: "GET",
            url: "reqdata.php",
            data: { act: "average"},
            dataType: "json",
            success: function(responseData)
            {
                var result = eval(responseData);
                
                if(result.responseCode == "OK")
                {
					var mData = result.data;
					
                    for(var i = 0; i < mData.length; i++)
                        $("#main").append("<div id = 'sub'><span id = 'mdh'>" + mData[i].mdh.replace("H", "시").replace(" ", "일 ").replace("-", "월 ") + "</span>(" + mData[i].rowNum + "건의 데이터가 수집됨)<hr />실내 평균온도&nbsp;A&nbsp;:&nbsp;" + mData[i].avg_dsit + "℃</div>");
					
					/*
                    var mData = result.data;
                    
                    var dsitHLTemp = new Array(mData[0].avg_dsit, mData[0].avg_dsit);
                    var dhitHLTemp = new Array(mData[0].avg_dhit, mData[0].avg_dhit);
                    var temp;
                    
                    for(var i = 0; i < mData.length - 1; i++)
                    {
                        if (dsitHLTemp[0] < mData[i + 1].avg_dsit) dsitHLTemp[0] = mData[i + 1].avg_dsit;
                        if (dsitHLTemp[1] > mData[i + 1].avg_dsit) dsitHLTemp[1] = mData[i + 1].avg_dsit;
                        if (dhitHLTemp[0] < mData[i + 1].avg_dhit) dhitHLTemp[0] = mData[i + 1].avg_dhit;
                        if (dhitHLTemp[1] > mData[i + 1].avg_dhit) dhitHLTemp[1] = mData[i + 1].avg_dhit;
                    }
                    $("#main").append("<div id = 'sub_main'><span id = 'mdh'>12시간 동안의 실내 최고·최저 온도</span><br />실내 A 최고 : <font color = 'red'>" + dsitHLTemp[0] + "℃</font> | 실내 B 최고 : <font color = 'red'>" + dhitHLTemp[0] + "℃</font><br />실내 A 최저 : <font color = 'blue'>" + dsitHLTemp[1] + "℃</font> | 실내 B 최고 : <font color = 'blue'>" + dhitHLTemp[1] + "℃</font><br /><span id = 'reqtime'>데이터 요청시간 : " + result.requestTime + "</span></div>");
                    for(var i = 0; i < mData.length; i++)
                        $("#main").append("<div id = 'sub'><span id = 'mdh'>" + mData[i].mdh.replace("H", "시").replace(" ", "일 ").replace("-", "월 ") + "</span>(" + mData[i].rowNum + "건의 데이터가 수집됨)<hr />실내온도&nbsp;A&nbsp;:&nbsp;" + mData[i].avg_dsit + "℃ | 실내온도&nbsp;B&nbsp;:&nbsp;" + mData[i].avg_dhit + "℃<br />실외온도&nbsp;A&nbsp:&nbsp;" + mData[i].avg_dsot + "℃ | 실내습도&nbsp;B&nbsp;:&nbsp;" + mData[i].avg_dhih + "%</div>");
					*/
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
<body>
<div id = 'main'>
</div>
</body>
</html>