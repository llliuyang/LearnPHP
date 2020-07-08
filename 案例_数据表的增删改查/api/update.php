<?php
require 'server.php';
$id = $_POST['id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

if ($conn -> connect_error){
    echo "数据库连接错误！";
}else{
    $update = "UPDATE members SET
    id =$id,姓名 ='$name',电话=$phone,内容='$address'
        WHERE id=$id";
    $res = $conn ->query($update);
    if($res){
        echo "更新成功！";
    }else{
        echo $conn -> error;
    }
}

$conn ->close();