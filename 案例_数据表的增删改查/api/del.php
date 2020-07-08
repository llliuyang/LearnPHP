<?php
require 'server.php';
$id = $_POST['id'];

if ($conn -> connect_error){
    echo "数据库连接错误！";
}else{
    $del = "DELETE FROM members WHERE id = $id";
    $res = $conn ->query($del);
    if($res){
        echo "删除成功！";
    }else{
        echo $conn -> error;
    }
}

$conn ->close();