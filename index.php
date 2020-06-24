<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
    $nameErr = $titleErr = $textErr = '*';
    $name = $title = $text = '';


    if($_SERVER["REQUEST_METHOD"]=='GET'){
        if(empty($_GET["name"])){
             $nameErr = "姓名是必需字段";

        }else{
            $name = test($_GET["name"]);
            $regName = preg_match("/[a-zA-Z]{3,10}|[\u4e00-\u9fa5]{2,4}/",'$name');
            if(!$regName){
                $nameErr = '2-4字中文名或3-10字符英文名';
            }else{
                $nameErr = "当前格式正确";
            }
        }

        if (empty($_GET["title"])){
            $titleErr = '标题是必需字段';
        }else{
           $title = test($_GET['title']);
           echo strlen($title);
           if(strlen($title) >60 || strlen($title) <15){
               $titleErr = '标题字数在5-20之间';
           }
        }

        if (empty($_GET["text"])){
            $textErr = '必须要输入内容哦';
        }else{
            $text = test($_GET['text']);
        }
    }

function test($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

?>
<form action="format.php" id="form" method="get">
    <fieldset>
        <legend>留言板</legend>
        <div class="name-label">
        <label for="name">姓名 </label>
        <input type="text" id="name" name="name" value="<?php echo $name ?>">
        </div>
        <span class="nameErr"><?php echo $nameErr ?></span>

        <div class="title-label">
        <label for="title">标题</label>
        <input type="text" id = "title" name="title" value="<?php echo $title ?>">

        </div>
        <span class="titleErr"><?php echo $titleErr ?></span>

        <textarea id="text" placeholder="发表点您的看法吧..." name="text"><?php echo $text ?></textarea>
        <span class="textErr"><?php echo $textErr ?></span>

    </fieldset>
    <button type="submit" id = "submit">提交留言</button>

</form>

</body>
</html>