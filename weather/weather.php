<!-- weather.php -->
<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Weather</title>
    <link rel="stylesheet" href="css/weather.css">
    <?php
        include 'php/load.php';
    ?>
    <script>
        var geo = <?php echo json_encode(getGeoData()) ?>;
        var now = <?php echo json_encode(getWeatherNowData()) ?>;
        var after = <?php echo json_encode(getWeatherAfterData()) ?>;
        var dust = <?php echo json_encode(getDustData()) ?>;

        var geo_name = '서울특별시';
        var geo_sub = '용산구';

        window.onload = function(){
            geo_name = localStorage.getItem('weather_geo_name') == null ? '서울특별시' : localStorage.getItem('weather_geo_name');
            geo_sub = localStorage.getItem('weather_geo_sub') == null ? '용산구' : localStorage.getItem('weather_geo_sub');
            var x = localStorage.getItem('weather_x') == null ? 60 : localStorage.getItem('weather_x');
            var y = localStorage.getItem('weather_y') == null ? 126 : localStorage.getItem('weather_y');

            initSelect();
            setWeatherNow(x, y);
        }

        function initSelect(){
            var geo_name_selector = document.getElementById('geo_name');
            for(var i = 0; i < geo.length; i++){
                var option = document.createElement('option');
                option.innerText = geo[i]['main'] + ' | ' + geo[i]['sub'];
                option.value = geo[i]['main'] + ' | ' + geo[i]['sub'];
                geo_name_selector.append(option);
            }

            selectSort(geo_name_selector);

            var now_selected = geo_name + ' | ' + geo_sub;
            for(var i = 0; i < geo_name_selector.length; i++){
                if(geo_name_selector.options[i].value == now_selected){
                    geo_name_selector.options[i].selected = true;
                    break;
                }
            }
        }
        function selectSort(boxIdObj, isValuesort){
            var obj, sArr, oArr, idx, op;

            if (typeof boxIdObj == 'string') obj = document.getElementById(boxIdObj);
            else obj = boxIdObj;

            if (obj.tagName.toLowerCase() != 'select') return false;
            if (typeof isValuesort == 'undefined') isValuesort = false;

            sArr = new Array(obj.options.length);
            oArr = new Array;

            for (idx = 0; idx < obj.options.length; idx++)
            {
                if (isValuesort) sArr[idx] = obj.options[idx].value;
                else sArr[idx] = obj.options[idx].text;

                oArr[sArr[idx]] = obj.options[idx];
            }
            sArr.sort();

            for (idx in sArr) obj.appendChild(oArr[sArr[idx]]);
        }
        function selectGeo(){
            var geo_name_selector = document.getElementById('geo_name');
            var selectedVal = geo_name_selector.options[geo_name_selector.selectedIndex].value;
            selectedVal = selectedVal.split(' | ');
            localStorage.setItem('weather_geo_name', selectedVal[0]);
            localStorage.setItem('weather_geo_sub', selectedVal[1]);

            for(var i = 0; i < geo.length; i++){
                if(geo[i]['main'] == selectedVal[0] && geo[i]['sub'] == selectedVal[1]){
                    localStorage.setItem('weather_x', geo[i]['x']);
                    localStorage.setItem('weather_y', geo[i]['y']);
                    setWeatherNow(geo[i]['x'], geo[i]['y']);
                    break;
                }
            }
        }

        function setWeatherNow(x, y){
            var weather_now;
            for(var i = 0; i < now.length; i++){
                if(now[i]['x'] == x && now[i]['y'] == y){
                    weather_now = now[i];
                    break;
                }
            }
            var now_img = document.getElementById('now_img');
            var now_detail = document.getElementById('now_detail');

            switch(weather_now['PTY']){
                case '0': now_img.src = '/weather/res/PTY_none.png'; break;
                case '1': now_img.src = '/weather/res/PTY_Rain.png'; break;
                case '2': now_img.src = '/weather/res/PTY_Sleet.png'; break;
                case '3': now_img.src = '/weather/res/PTY_Snow.png'; break;
                case '4': now_img.src = '/weather/res/PTY_Shower.png'; break;
                case '5': now_img.src = '/weather/res/PTY_Raindrop.png'; break;
                case '6': now_img.src = '/weather/res/PTY_Raindrop_Weak_Snow.png'; break;
                case '7': now_img.src = '/weather/res/PTY_Weak_Snow.png'; break;
            }
            
            now_detail.innerText
                = '온도 : ' + weather_now['T1H'] + '℃\n'
                + '습도 : ' + weather_now['REH'] + '%\n'
                + '풍량  : ' + weather_now['WSD'] + 'm/s\n'
                + '강수량 : ' + weather_now['RN1'] + 'mm';
        }
    </script>
</head>

<body>
    <div>
        <div id=now>
            <div id=now_img><img id=now_img src=''></div>
            <div id=now_detail></div>
        </div>
        <select id='geo_name' onchange='selectGeo()'>
        </select>
    </div>
</body>

</html>