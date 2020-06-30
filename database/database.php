<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

</body>
</html>
<?php
$name = $_POST['name'];
$phone = $_POST['phone'];
$comment = $_POST['comment'];

$servername = 'localhost';
$username = 'liuyang';
$password = 'Liuyang00';
$dbname = 'liu_db';


//创建数据库连接 （1）面向过程
//$conn = mysqli_connect($servername,$username,$password,$dbname);

//创建数据库连接 （2）面向对象
$conn = new mysqli($servername,$username,$password,$dbname);



//检测连接 (1) 面向过程
/*if(!$conn){
    die("Connection failed: " .mysqli_connect_error());
}else {
    echo "数据库连接成功。方式：面向过程<br>";
}*/

//检测连接 (2) 面向对象
if($conn -> connect_error){
    die("Connection failed:".$conn -> connect_error);
}else {
    echo "数据库连接成功。方式：面向对象<br>";
}

//创建数据库
//$createDb = "CREATE DATABASE liu_db";
//检查数据库是否创建成功 (1) 面向过程
/*if(mysqli_query($conn,$createDb)){
    echo "数据库创建成功！";
}else{
    echo "Error creating database: " . mysqli_error($conn);
}*/

//检查数据库是否创建成功 (2) 面向对象
/*if($conn -> query($createDb) === true){
    echo "数据库创建成功！";
}else{
    echo "Error creating database: " . $conn->error;
}*/


//创建表
/*$createTable = "CREATE TABLE members (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
姓名 VARCHAR(10) NOT NULL,
电话 VARCHAR(13) NOT NULL,
内容 VARCHAR(60) NOT NULL
)";*/

//检测数据表是否创建 （1） 面向过程
/*if(mysqli_query($conn,$createTable)){
    echo "数据表创建成功<br>";
}else {
    echo '数据表创建错误：' .mysqli_error($conn);
}*/
//检测数据表是否创建 （2） 面向对象
/*if($conn -> query($createTable)===true){
    echo "数据表创建成功<br>";
}else {
    echo '数据表创建错误：' .$conn -> error;
}*/



//sql语句
$sql = "INSERT INTO members (姓名,电话,内容)
VALUES ('".$name."',$phone,'".$comment."')";

//执行SQL语句 进行插值
$result = mysqli_query($conn,$sql);

//判断是否插入成功
if ($result) {
    echo "数据插入成功<br>";

}else {
    echo '错误'.$sql.mysqli_error($conn);
};
/*  取出值
 *  排序操作 SELECT * FROM table_name ORDER BY column_name(s) ASC|DESC
 *  ASC (ascending order) 升序
 *  DESC(Descending order) 降序
 * 拼音排序需转码再排序，若是用的就是gbk,那么无需转码
 */
$fecth = "SELECT * FROM members ORDER BY CONVERT(姓名 using gbk) DESC";

//面向过程方法
//$res = mysqli_query($conn,$fecth);

//面向对象方法
$res = $conn -> query($fecth);
?>
    <style>
        *{margin: 0;padding: 0}
        .table{width: 800px;margin: 0 auto;box-sizing: border-box;border: 1px solid #f74444;font-size: 0}
        .thead,.tbody{text-align: center;display:inline-block;height: 32px;line-height: 32px;font-size: 16px;margin: 0}
        .tbodyrow{
            width: 100%;
        }
        .thead{font-weight: bold}
        .id{width: 10%}
        .name{width: 20%}
        .phone{width: 30%}
        .comment{width:40%}

    </style>
<div class="table">
    <span class="id thead">ID</span>
    <span class="name thead">姓名</span>
    <span class="phone thead">电话</span>
    <span class="comment thead">留言</span>


<?php
//面向过程
if(mysqli_num_rows($res) > 0){
    //面向对象语句
    //if($res->num_rows > 0)

    //输出
    while($row = mysqli_fetch_array($res)){
        //面向对象语句
        //while($row = $res->fetch_array())
        echo <<<HTML
<div class="tbodyrow">
<span class="id tbody">{$row["id"]}</span>
<span class="name tbody">{$row["姓名"]}</span>
<span class="phone tbody">{$row["电话"]}</span>
<span class="comment tbody">{$row["内容"]}</span>
</div>
<br>
HTML;

    }
}else{
echo "输出错误";
}
?>
</div>

<?php
//修改更新数据
/*$update = "UPDATE members SET
姓名='牛二' , 内容='我叫牛二'
WHERE 姓名 = '张三' ";*/
//mysqli_query($conn,$update);
//$conn -> query($update);

//删除数据
//$delete = " DELETE FROM members WHERE 姓名 = '牛二' ";
//mysqli_query($conn,$delete);

//$conn -> query($delete);

//关闭数据库连接 （1） 面向过程
//mysqli_close($conn);

//关闭数据库连接 （2） 面向对象

$conn -> close();
?>