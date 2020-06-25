<?php

$allowExts = array('jpg','jpeg','gif','png');
$fenge = explode('.',$_FILES['file']['name']);
$exts = end($fenge);

if(in_array($exts,$allowExts)){


if ($_FILES["file"]["error"] > 0) {
    echo $_FILES["file"]["error"] . '<br> ';
} else {
    echo '文件名:' . $_FILES["file"]["name"] . '<br>' .
        '文件类型：' . $_FILES["file"]["type"] . '<br>' .
        "文件临时存储的名字: " . $_FILES["file"]["tmp_name"] . '<br>' .
        '文件大小：' . ($_FILES["file"]["size"] / 1024) . 'kbyte<br>';


    if (file_exists("cunchu/" . $_FILES["file"]["name"])) {
        echo $_FILES["file"]["name"] . '文件已存在<br>';
    } else {
        move_uploaded_file($_FILES["file"]["tmp_name"], 'cunchu/' . $_FILES["file"]["name"]);


    }


}

//$file = fopen("cunchu/" . $_FILES["file"]["name"], 'r') or exit('无法打开文件');
//while (!feof($file)) {
//    echo fgets($file) . '<br>';
//}
//
//fclose($file);

}else{
    echo  "错误的文件格式";
}