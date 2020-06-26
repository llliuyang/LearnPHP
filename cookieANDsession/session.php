<!doctype html>
<?php
session_start();
if(isset($_SESSION['view'])){
    $_SESSION['view'] += 1;
}else{
    $_SESSION['view'] = 1;
}

echo '浏览量：'.$_SESSION['view']
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

</body>
</html>