<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Covid</title>
    <link rel="stylesheet" href="css/index.css">
    <script>
        var initCount = 0;
        var total;
        var local;
        var age;

        window.onload = function(){
            initTotal();
            initLocal();
            initAge();
        }
        function initTotal(){
            var xhr = new XMLHttpRequest();
            var url = 'get/total';
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200){
                    var data = JSON.parse(this.responseText);
                    if(data['state'] == 0){
                        total = data['data'];
                        setTotal();
                    }
                }
            };
            xhr.send();

        }
        function initLocal(){
            var xhr = new XMLHttpRequest();
            var url = 'get/local';
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200){
                    var data = JSON.parse(this.responseText);
                    if(data['state'] == 0){
                        local = data['data'];
                        setLocal();
                    }
                }
            };
            xhr.send();
        }
        function initAge(){
            var xhr = new XMLHttpRequest();
            var url = 'get/age';
            xhr.open('GET', url);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200){
                    var data = JSON.parse(this.responseText);
                    if(data['state'] == 0){
                        age = data['data'];
                        setAge();
                    }
                }
            };
            xhr.send();
        }
        
        function setTotal(){
            var name = ['decideCnt', 'clearCnt', 'examCnt', 'deathCnt', 'careCnt', 'resutlNegCnt', 'accExamCnt', 'accDefRate']
            for(var i = 0; i < name.length; i++){
                if(i == 7)
                    document.getElementById(name[i]).innerHTML = (total[0][name[i]] * 1).toFixed(3) + '%';
                else
                    document.getElementById(name[i]).innerHTML = total[0][name[i]];
            }
        }
        function setLocal(){
            var selectType = document.getElementById('local_type');
            var type = selectType.options[selectType.selectedIndex].value
            for(var i = 0; i < local.length; i++){
                if(local[i]['name'] == 'Total') continue;
                document.getElementById(local[i]['name']).innerHTML = '&nbsp' + local[i][type] + '&nbsp';
            }
        }
        function setAge(){
            var selectType = document.getElementById('age_type');
            var type = selectType.options[selectType.selectedIndex].value

            var max_s = parseInt(age[9][type]) > parseInt(age[10][type]) ? parseInt(age[9][type]) : parseInt(age[10][type]);
            var max_a = 0;

            for(var i = 0; i < 9; i++)
                if(max_a < parseInt(age[i][type]))
                    max_a = parseInt(age[i][type]);
            
            document.getElementById('chart_bar9').style.width = (50 * parseInt(age[9][type]) / max_s) + '%';
            document.getElementById('chart_bar10').style.width = (50 * parseInt(age[10][type]) / max_s) + '%';
            for(var i = 0; i < 9; i++)
                document.getElementById('chart_bar' + i).style.width = (50 * parseInt(age[i][type]) / max_a) + '%';
            
            for(var i = 0; i < age.length; i++){
                document.getElementById('chart_value' + i).innerHTML = age[i][type];
                if(type.includes('Rate'))
                    document.getElementById('chart_value' + i).innerHTML += '%';
            }
        }
    </script>
</head>

<body>
    <div id=info0>
        <b>코로나 통계</b>
    </div>
    <hr id=hr0>
    <div id=total_contain>
        <div class='total row0 col0'>확진자</div><div id=decideCnt class='total total_value0 row0 col0'></div>
        <div class='total row0 col1'>치료중</div><div id=careCnt class='total total_value1 row0 col1'></div>
        <div class='total row1 col0'>격리해제</div><div id=clearCnt class='total total_value0 row1 col0'></div>
        <div class='total row1 col1'>사망자</div><div id=deathCnt class='total total_value1 row1 col1'></div>
        <div class='total row2 col0'>검사진행</div><div id=examCnt class='total total_value0 row2 col0'></div>
        <div class='total row2 col1'>음성</div><div id=resutlNegCnt class='total total_value1 row2 col1'></div>
        <div class='total row3 col0'>누적검사</div><div id=accExamCnt class='total total_value0 row3 col0'></div>
        <div class='total row3 col1'>확진률</div><div id=accDefRate class='total total_value1 row3 col1'></div>
    </div>
    <hr id=hr1>
    <div id=local_contain>
        <div id=info1>
            지역별 통계
        </div>
        <select id='local_type' onchange='setLocal()'>
            <option value="defCnt" selected>확진자 수</option>
            <option value="incDec">전일대비 증감 수</option>
            <option value="deathCnt">사망자 수</option>
            <option value="isolIngCnt">격리자 수</option>
            <option value="isolClearCnt">격리 해재 수</option>
            <option value="overFlowCnt">해외유입 수</option>
            <option value="qurRate">10만명당 발생</option>
        </select>
        <div id=map_img_contain><img id=map_img src='res/map.png'></div>
        <div id=Busan class=loc></div>
        <div id=Chungcheongbuk-do class=loc></div>
        <div id=Chungcheongnam-do class=loc></div>
        <div id=Daegu class=loc></div>
        <div id=Daejeon class=loc></div>
        <div id=Gangwon-do class=loc></div>
        <div id=Gwangju class=loc></div>
        <div id=Gyeongsangbuk-do class=loc></div>
        <div id=Gyeonggi-do class=loc></div>
        <div id=Gyeongsangnam-do class=loc></div>
        <div id=Incheon class=loc></div>
        <div id=Jeju class=loc></div>
        <div id=Jeollabuk-do class=loc></div>
        <div id=Jeollanam-do class=loc></div>
        <div id=Sejong class=loc></div>
        <div id=Seoul class=loc></div>
        <div id=Ulsan class=loc></div>
        <div id=Lazaretto_title>기타</div>
        <div id=Lazaretto class=loc></div>
    </div>
    <hr id=hr2>
    <div id=chart_contain>
        <div id=info2>
            나이, 성별 통계
        </div>
        <select id='age_type' onchange='setAge()'>
            <option value="confCase" selected>확진자 수</option>
            <option value="confCaseRate">확진률</option>
            <option value="death">사망자 수</option>
            <option value="deathRate">사망률</option>
            <option value="criticalRate">치명률</option>
        </select>
        <div class='chart chart0'>0 ~ 9</div><div id=chart_bar0 class='chart chart0 chart_bar'>&nbsp</div><div id=chart_value0 class='chart chart0 chart_value'></div>
        <div class='chart chart1'>10 ~ 19</div><div id=chart_bar1 class='chart chart1 chart_bar'>&nbsp</div><div id=chart_value1 class='chart chart1 chart_value'></div>
        <div class='chart chart2'>20 ~ 29</div><div id=chart_bar2 class='chart chart2 chart_bar'>&nbsp</div><div id=chart_value2 class='chart chart2 chart_value'></div>
        <div class='chart chart3'>30 ~ 39</div><div id=chart_bar3 class='chart chart3 chart_bar'>&nbsp</div><div id=chart_value3 class='chart chart3 chart_value'></div>
        <div class='chart chart4'>40 ~ 49</div><div id=chart_bar4 class='chart chart4 chart_bar'>&nbsp</div><div id=chart_value4 class='chart chart4 chart_value'></div>
        <div class='chart chart5'>50 ~ 59</div><div id=chart_bar5 class='chart chart5 chart_bar'>&nbsp</div><div id=chart_value5 class='chart chart5 chart_value'></div>
        <div class='chart chart6'>60 ~ 69</div><div id=chart_bar6 class='chart chart6 chart_bar'>&nbsp</div><div id=chart_value6 class='chart chart6 chart_value'></div>
        <div class='chart chart7'>70 ~ 79</div><div id=chart_bar7 class='chart chart7 chart_bar'>&nbsp</div><div id=chart_value7 class='chart chart7 chart_value'></div>
        <div class='chart chart8'>80 ~ 89</div><div id=chart_bar8 class='chart chart8 chart_bar'>&nbsp</div><div id=chart_value8 class='chart chart8 chart_value'></div>
        <div class='chart chart9'>여성</div><div id=chart_bar9 class='chart chart9 chart_bar'>&nbsp</div><div id=chart_value9 class='chart chart9 chart_value'></div>
        <div class='chart chart10'>남성</div><div id=chart_bar10 class='chart chart10 chart_bar'>&nbsp</div><div id=chart_value10 class='chart chart10 chart_value'></div>
    </div>
    <div id=info3>
        자료제공 : 보건복지부
    </div>
</body>

</html>
