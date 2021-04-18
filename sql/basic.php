<?php
    function userCheck($id, $pw){
        include "sqlcon.php";

        $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
        if(mysqli_connect_error()) return array(0, 4);

        $sql_query = 'select * from user_list where id="'.$id.'" and pw="'.$pw.'"';
        $result = mysqli_query($conn, $sql_query);

        if($row = mysqli_fetch_array($result))
            return array(1, $row["level"]);
        else
            return array(0, 4);
    }

    function loadClassList($id, $pw){
        include "sqlcon.php";

        $user = userCheck($id, $pw);

        $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
        if(mysqli_connect_error()) return array();

        $sql_query = 'select * from class_list where level >= '.$user[1];
        $result = mysqli_query($conn, $sql_query);
        mysqli_close($conn);

        $classList = array();
        while($row = mysqli_fetch_array($result))
            $classList[] = $row[0];
            
        return $classList;
    }
?>
