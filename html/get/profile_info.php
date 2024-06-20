<?php

include '/var/www/php/sql_functions.php';


echo json_encode(array("weekly_visitors" => GetWeeklyVisitors()));
