<?php
require 'server.php';
//header("Content-type:application/json");
$id = $_POST['id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

if($conn -> connect_error){
    echo "数据库连接错误！";
}else{

    $insert = "INSERT INTO members(id,姓名,电话,内容)
               VALUES ($id,'$name',$phone,'$address')";

    $res = mysqli_query($conn,$insert);

    if($res){
        $select = "SELECT * FROM members WHERE id = $id";
        $selectres = mysqli_query($conn,$select);

        if($selectres -> num_rows >0){
            $row = $selectres->fetch_assoc();
            echo $row['id'].$row['姓名'].$row['电话'].$row['内容'];
        }


    }

}

$conn -> close();