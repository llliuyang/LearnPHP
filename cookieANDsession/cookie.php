<!doctype html>
<?php
setcookie('user','php',time()-3600);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
if(isset($_COOKIE['user'])){
    echo '欢迎您'.$_COOKIE['user'].'<br>';
}else{
    echo  '游客身份';
}

?>
</body>
</html>