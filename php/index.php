<!--
참고
http://blog.naver.com/skawo32167/220495589498
http://blog.naver.com/blogpyh/220095293310
http://blog.naver.com/ivory82/40208193734
-->
<!DOCTYPE html>
<html>
<head>
<title>아두이농</title>
<meta property="og:site_name" content="아두이농">
<meta property="og:title" content="아두이농">
<meta name="description" content="아두이농">
<meta property="og:description" content="아두이농">
<meta property="og:image" content="https://avatars0.githubusercontent.com/u/10598644?v=3&s=460">
<meta charset = "UTF-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
<link rel="stylesheet" type="text/css" href="./design.css" />
<script type = "text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type = "text/javascript">
    var MD = new MyDate();

    $( document ).ready(function() {
      printData('last');
      printData('minute');
    });

    function FillZero(s, num) {
        s = s.toString();
        while(s.length < num)
            s = '0' + s;
        return s;
    }

    function MyDate()
    {
        var _d = new Date();
        this.year = _d.getFullYear();
        this.month = _d.getMonth() + 1;
        this.date = _d.getDate();
        this.hours = _d.getHours();

        this.getYear   = function() { return this.year; }
        this.getMonth  = function() { return this.month; }
        this.getDate   = function() { return this.date; }
        this.getHours  = function() { return this.hours; }

        this.setYear   = function(y) { this.year  = y; }
        this.setMonth  = function(m) { this.month = m; }
        this.setDate   = function(d) { this.date  = d; }
        this.setHours  = function(h) { this.hours = h; }

        this.getConvYMD = function() { return FillZero(this.year - 2000, 2) + FillZero(this.month, 2) + FillZero(this.date, 2); }
        this.getConvH   = function() { return FillZero(this.hours, 2); }
    }

    function descHour() {
        if(MD.getHours() <= 0) {
            MD.setHours(23);
            MD.setDate(MD.getDate() - 1);
        } else {
            MD.setHours(MD.getHours() - 1);
        }

        $(".res").html("");
        printData('minute');
    }

    function ascHour() {
            if(MD.getHours() >= 23) {
                MD.setHours(0);
                MD.setDate(MD.getDate() + 1);
            } else {
                MD.setHours(MD.getHours() + 1);
            }

        $(".res").html("");
        printData('minute');
    }

    function printData(sAct)
    {
        $("#debug").html(MD.getConvYMD() + "/" +MD.getConvH());

        $.ajax({
        type: "GET",
        url: "./reqdata.php",
        data: { act: sAct, ymd: MD.getConvYMD(), h: MD.getConvH() },
        dataType: "json",
        success: function(responseData)
        {
            var result = eval(responseData);

            if(result.responseCode == "OK")
            {
                if(sAct == "last") {
                    $('<div/>', {
                      class: 'sub_main',
                    }).appendTo('.last');
                    $('<span/>', {
                      class: 'mdh',
                      text: '현재 온도 : ' + result.data.temp  + '℃'
                    }).appendTo('.sub_main').append('<br />');
                    $('<span/>', {
                      class: 'reqtime',
                      text: '기준 시각 : ' + result.data.regi_date
                    }).appendTo('.sub_main');
                }
                else if(sAct == "minute")
                {
                    var mData = result.data;
                    for(var i = 0; i < mData.length; i++) {
                    $('<div/>', {
                      class: 'sub',
                      }).appendTo('.res').append($('<span/>', {
                          class: 'mdh',
                          text: mData[i].mdh + " / " + mData[i].avg_temp  + '℃'
                      }));
                    }
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
<body>
<div id = 'main'>
    <span id = "debug"></span><input type = "button" onClick = "descHour();" value = "-1시간" /><input type = "button" onClick = "ascHour()" value = "+1시간" />
    <div class="last"></div>
    <div class="res"></div>
</div>
</body>
</html>
