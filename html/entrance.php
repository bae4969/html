<!doctype html>
<html lang=ko>

<head>
    <meta charset='utf-8'>
    <title>template</title>
    <style>
    /************************************************************************/
        body {
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            background: #181A1B;
            color: #C3C3C3;
            list-style: none;
            font-family: "arial";
        }

        div#main {
            width: 100%;
            height: 100%;
            margin: 0px;
        }

        div#blank{
            width: 100%;
            height: 45%;
        }
        div#output{
            width: 100%;
            height: 10%;
            font-size: 10vh;
            text-align: center;
        }
    /************************************************************************/
    </style>
</head>

<body>
    <div id=blank>
    </div>
    <div id=output>
        <!-- <b>점검중 ...</b> -->
        <b>접속중 ...</b>
    </div>
    <div id=blank>
    </div>

    <script type="text/javascript">
        window.onload = function(){
            location.href = 'index';
        }
    </script>
</body>

</html>