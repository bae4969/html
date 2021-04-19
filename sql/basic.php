<?php
    function userCheck($id, $pw){
        include "sqlcon.php";

        if($id == 'null' || $pw == 'null')
            return array("valid"=>0, "level"=>4, "user_index"=>0);

        $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
        $sql_query = 'select * from user_list where id="'.$id.'" and pw="'.$pw.'"';
        $result = mysqli_query($conn, $sql_query);


        if($row = mysqli_fetch_array($result))
            if($row["state"] == 0)
                return array("valid"=>1, "level"=>$row["level"], "user_index"=>$row["user_index"]);

        return array("valid"=>0, "level"=>4, "user_index"=>0);
    }

    function loadClassList($id, $pw){
        include "sqlcon.php";

        $user = userCheck($id, $pw);

        $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
        $sql_query = 'select class_index, name from class_list where read_level >= '.$user["level"].' order by class_index asc';
        $result = mysqli_query($conn, $sql_query);
        mysqli_close($conn);

        $classList = array();
        while($row = mysqli_fetch_array($result))
            $classList[] = $row;
            
        return $classList;
    }

    function loadMainContentList($id, $pw, $class_index = null){
        include "sqlcon.php";

        $user = userCheck($id, $pw);

        $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
        $sql_query = 'select content_index, date, title, thumbnail from contents where read_level >= '.$user["level"];
        if($class_index) $sql_query .= ' and class_index = '.$class_index;
        $sql_query .= ' order by content_index desc limit 10';
        $result = mysqli_query($conn, $sql_query);
        mysqli_close($conn);

        $MainContentList = array();
        while($row = mysqli_fetch_array($result))
            $MainContentList[] = $row;
            
        return $MainContentList;
    }

    function loadDetailContentList($id, $pw, $class_index){
        include "sqlcon.php";

        $user = userCheck($id, $pw);

        $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
        $sql_query = 'select date, title, content from contents where content_index = '
            .$class_index.' and read_level >= '.$user["level"];
        $result = mysqli_query($conn, $sql_query);
        mysqli_close($conn);

        if($row = mysqli_fetch_array($result))
            return $row;
            
        return null;
    }
?>
