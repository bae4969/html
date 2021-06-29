<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <title>template</title>
    <style>
    /************************************************************************/
        img#mainTitle {
            cursor: pointer;
        }

        div#topLeft:hover, div#topRight:hover, div#topWrite:hover, div.category:hover {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        body {
            min-width: 600px;
            margin: 0;
            padding: 0;
            background: #181A1B;
            color: #C3C3C3;
            list-style: none;
            font-family: "arial";
        }

        div#main {
            width: 70%;
            margin: 0px auto;
            padding: 20px;
            padding-top: 10px;
        }

        div#output1{
            position: absolute;
            top: 40%;
            left: 41%;
            font-size: 3em;
            display: block;
        }
        div#output2{
            position: absolute;
            top: 48%;
            left: 37%;
            font-size: 3em;
            display: block;
        }
    /************************************************************************/
    </style>
</head>

<body>
    <div id=output1>
        시스템 점검중...
    </div>
    <div id=output2>
        System Maintenance...
    </div>
</body>

</html>