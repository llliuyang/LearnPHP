<?php
$server = 'localhost';
$username = 'liuyang';
$password = 'liuyang00';
$db_name = 'liu_db';
$data = array();
$json = '';

$conn = new mysqli($server,$username,$password,$db_name);

if($conn -> connect_error){
    echo "数据库连接错误！";
}else{
    $search = "select * from members";
    $result = $conn->query($search);
    if($result -> num_rows >0){
        while($row = $result -> fetch_assoc()){

           return  json_encode($row,JSON_UNESCAPED_UNICODE);

        }

return;
    }

}