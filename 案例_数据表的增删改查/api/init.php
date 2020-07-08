<?php
require 'server.php';

if($conn -> connect_error){
    echo "数据库连接错误！";
}else{
    $search = "select * from members";
    $result = $conn->query($search);
    if($result -> num_rows >0){
        while($row = $result -> fetch_assoc()){
            $resary[] = $row;
        }
        echo json_encode($resary,JSON_UNESCAPED_UNICODE);

    }

}

$conn -> close();