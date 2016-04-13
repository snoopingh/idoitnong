<!DOCTYPE html>
<html>
  <head>
    <title>아두이농</title>
    <meta property="og:site_name" content="영농 관리 모니터">
    <meta property="og:title" content="영농 관리 모니터">
    <meta name="description" content="아두이농">
    <meta property="og:description" content="아두이농">
    <meta property="og:image" content="https://avatars0.githubusercontent.com/u/10598644?v=3&s=460">
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0, width=device-width">
    <link rel="stylesheet" href="animate.css">
    <link rel="stylesheet" href="design.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
  </head>
  <body>
    <div id="main">
      <div class="last"></div>
      <div class="chartTrend">
        <canvas id="myChart"></canvas>
      </div>
      <div class="average"></div>
    </div>
    <script>
    function drawChart(mData) {
      var ctx = $("#myChart").get(0).getContext("2d");
      var myNewChart = new Chart(ctx);
      Chart.defaults.global.responsive = true;
      var newlabels = [];
      var newdatas = [];
      for (var i = mData.length-1 ; i > -1; i--) {
        mData[i].mdh = mData[i].mdh.split("일 ")[1];
        newlabels.push(mData[i].mdh);
        newdatas.push(mData[i].avg_dsit);
      }

      //$(".average").append("<div class = 'sub'><span class = 'mdh'>" + mData[i].mdh + "</span>(" + mData[i].rowNum + "건의 데이터가 수집됨)<hr />실내 평균온도&nbsp;A&nbsp;:&nbsp;" + mData[i].avg_dsit + "℃</div>");
      var data = {
        labels: newlabels,
        datasets: [{
            label: "Temperature Data",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: newdatas
        }]
      };
      var options = {
        showScale: true,
        scaleOverride: true,
        scaleSteps: 1, //분산의 절반정도로 설정
        scaleStepWidth: 40, //온도최대
        scaleStartValue: 0, //최솟값 - 5~10 정도로 설정


        scaleShowGridLines : true,
        scaleGridLineColor : "rgba(0,0,0,.05)",
        scaleGridLineWidth : 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve : true,
        bezierCurveTension : 0.4,
        pointDot : true,
        pointDotRadius : 4,
        pointDotStrokeWidth : 1,
        pointHitDetectionRadius : 20,
        datasetStroke : true,
        datasetStrokeWidth : 2,
        datasetFill : true,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
      };
      var myLineChart = new Chart(ctx).Line(data, options);
    }
    $( document ).ready(function() {
      printData('last');
      printData('average');
    });
    function printData(sAct, iPage = 1) {
      $.ajax({
        type: "GET",
        url: "reqdata.php",
        data: { act: sAct, page: iPage },
        dataType: "json",
        success: function(result) {
            if(result.responseCode == "OK") {
                if(sAct == "last") {
                    $('<div/>', {
                      class: 'sub_main',
                    }).appendTo('.last');
                    $('<span/>', {
                      class: 'mdh',
                      text: '현재 온도 : ' + result.data.temp  + '℃'
                    }).appendTo('.sub_main').append('<br>');
                    $('<span/>', {
                      class: 'reqtime',
                      text: '기준 시각 : ' + result.data.regi_date
                    }).appendTo('.sub_main');
                }
                else if(sAct == "average") {
                  var mData = result.data;
                  for(var i = 0; i < mData.length; i++) {
                      mData[i].mdh = mData[i].mdh.replace("H", "시").replace(" ", "일 ").replace("-", "월 ");
                      $(".average").append("<div class = 'sub'><span class = 'mdh'>" + mData[i].mdh + "</span>(" + mData[i].rowNum + "건의 데이터가 수집됨)<hr />실내 평균온도&nbsp;A&nbsp;:&nbsp;" + mData[i].avg_dsit + "℃</div>");
                  }
                  drawChart(mData);
                }
            }
            else if(result.responseCode == "NG") {
                $("#main").append(result.message);
            }
        },
        error: function(request, status, error) {
            alert(status);
        }
    });
  }
  </script>
</body>
</html>
