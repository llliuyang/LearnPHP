<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>表单信息</title>
    <style>
        table{
            width: 60%;
            margin: 0 auto;
        }
        th,td{
            height:30px;
            line-height: 30px;
            text-align: center;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>姓名</th>
            <th>标题</th>
            <th>内容</th>
        </tr>
    </thead>

    <tbody>
       <tr>
           <td><?php echo $_GET['name'] ?></td>
           <td><?php echo $_GET['title'] ?></td>
           <td><?php echo $_GET['text'] ?></td>
       </tr>
    </tbody>
</table>
</body>
</html>


