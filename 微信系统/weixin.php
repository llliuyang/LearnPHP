<?php
$servername = "xxxx";
$username = "xxx";
$password = "xxx";
$dbname = "xxx";
class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() {
        echo "<tr>";
    }

    function endChildren() {
        echo "</tr>" . "\n";
    }
}

function delete($conn)
{
	$id = $_GET['id'];
	$status = $_GET['status'];
	$stmt = $conn->prepare("DELETE FROM weixin WHERE id = $id");
    $res = $stmt->execute();
    // 设置结果集为关联数组
    return json_encode($res);
}

function update($conn)
{
	$id = $_GET['id'];
	$status = $_GET['status'];
	$stmt = $conn->prepare("UPDATE weixin SET status = $status WHERE id = $id");
    $res = $stmt->execute();
    // 设置结果集为关联数组
    return json_encode($res);
}

function query($conn)
{
	$user = auth($conn, true);
	if(count($user)){
		if($user[0]['is_admin'] == 1){
			if(isset($_GET["key"])){
				$key = $_GET["key"];
				$stmt = $conn->prepare("SELECT * FROM weixin WHERE wxid LIKE '%".$key."%' OR type LIKE '%".$key."%' ORDER BY create_date DESC");
			}else{
				$stmt = $conn->prepare("SELECT * FROM weixin ORDER BY create_date DESC");
			}
		    $stmt->execute();

		    // 设置结果集为关联数组
		    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		    return json_encode($stmt->fetchAll());
		}else{

			$types = explode('|', $user[0]['type']);
			$strType = "";
			foreach ($types as $key => $value) {
				if($strType == ""){
					$strType .= " WHERE type = '".$value."'";
				}else{
					$strType .= " OR type = '".$value."'";
				}
			}

			if(isset($_GET["key"])){
				$keys = $_GET["key"];
				//if(strstr($user[0]['type'], $keys)){
					$stmt = $conn->prepare("SELECT * FROM weixin WHERE wxid LIKE '%".$keys."%' OR type LIKE '%".$keys."%' ORDER BY create_date DESC");
					$stmt->execute();
					// 设置结果集为关联数组
				    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
				    $arrs = [];
				    $temp = $stmt->fetchAll();
				    foreach ($temp as $key => $value) {
				    	if(strstr($user[0]['type'], $value['type'])){
				    		array_push($arrs, $value);
				    	}
				    }
				    return json_encode($arrs);
				//}
			}else{
				$stmt = $conn->prepare("SELECT * FROM weixin $strType ORDER BY create_date DESC");
				$stmt->execute();
				// 设置结果集为关联数组
			    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			    $arrs = [];
			    $temp = $stmt->fetchAll();
			    foreach ($temp as $key => $value) {
			    	if(strstr($user[0]['type'], $value['type'])){
			    		array_push($arrs, $value);
			    	}
			    }
			    return json_encode($arrs);
			}
		}
    }
}

function insert($conn)
{
	$user = auth($conn, true);
	if(count($user)){
		$wxid = isset($_GET["wxid"]) ? json_decode($_GET["wxid"],true) : "";
		$status = isset($_GET["status"]) ? $_GET["status"] : "";
		$type = isset($_GET["rowtype"]) ? $_GET["rowtype"] : "";
		if($user[0]['is_admin'] == 1){
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $time = time();
		    foreach ($wxid as $key => $value) {
		    	if($value){
			    	# code...
				    if($type){
				    	$sql = "INSERT INTO weixin (wxid, status, create_date,type) VALUES ('$value', $status, $time,'$type')";
				    }else{
				    	$sql = "INSERT INTO weixin (wxid, status, create_date) VALUES ('$value', $status, $time)";
				    }
				    $conn->exec($sql);
			    }
		    }
		    // 使用 exec() ，没有结果返回
		    return json_encode([
		    	"code" => 200,
		    	"message" => "添加成功"
		    ]);
	    }else{
	    	//是否可以添加标识的
	    	if(strstr($user[0]['type'], $type)){
	    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $time = time();
			    foreach ($wxid as $key => $value) {
			    	# code...
			    	if($value){
					    if($type){
					    	$sql = "INSERT INTO weixin (wxid, status, create_date,type) VALUES ('$value', $status, $time,'$type')";
					    }else{
					    	$sql = "INSERT INTO weixin (wxid, status, create_date) VALUES ('$value', $status, $time)";
					    }
					    $conn->exec($sql);
				    }
			    }
			    // 使用 exec() ，没有结果返回
			    return json_encode([
			    	"code" => 200,
			    	"message" => "添加成功"
			    ]);
	    	}else{
	    		return json_encode([
			    	"code" => 400,
			    	"message" => "添加失败，你不在该标识中"
			    ]);
	    	}
	    }
    }
}

function deleteUser($conn)
{
	$id = $_GET['id'];
	$stmt = $conn->prepare("DELETE FROM user WHERE id = $id");
    $res = $stmt->execute();
    // 设置结果集为关联数组
    echo json_encode($res);
}

function updateUser($conn)
{
	$id = $_GET['id'];
	$password = $_GET['new_password'];
	$type = $_GET['new_type'];
	$name = $_GET['name'];
	$stmt = $conn->prepare("UPDATE user SET password = '$password',type='$type',name='$name' WHERE id = $id");
    $res = $stmt->execute();
    // 设置结果集为关联数组
    echo json_encode($res);
    return;
}

function queryUsers($conn)
{

	$user = auth($conn, true);
	if(count($user)){
		if($user[0]['is_admin'] == 1){

			if(isset($_GET["key"])){
				$key = $_GET["key"];
				$stmt = $conn->prepare("SELECT * FROM user WHERE password LIKE '%".$key."%' OR type LIKE '%".$key."%' ORDER BY create_date DESC");
			}else{
				$stmt = $conn->prepare("SELECT * FROM user ORDER BY create_date DESC");
			}
		    $stmt->execute();
		    // 设置结果集为关联数组
		    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		    return json_encode($stmt->fetchAll());
		}
    }
}

//验证权限
function auth($conn,$is = false)
{
	if($is){
		$pwd = isset($_GET["pwd"]) ? $_GET["pwd"] : "";
		$stmt = $conn->prepare("SELECT * FROM user WHERE password = '$pwd'");
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}else{
		$pwd = isset($_GET["password"]) ? $_GET["password"] : "";
		$stmt = $conn->prepare("SELECT * FROM user WHERE password = '$pwd'");
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		return json_encode($stmt->fetchAll());
	}
}

function addUser($conn)
{
	$_GET["pwd"] = $_GET['add_user'];
	$user = auth($conn, true);
	if(count($user)){
		if($user[0]['is_admin'] == 1){
			$password = isset( $_GET['password']) ? $_GET['password'] : "";
			$name = isset( $_GET['name']) ? $_GET['name'] : "";
			$is = isset( $_GET['is']) ? $_GET['is'] : "0";
			$type = isset( $_GET['type']) ? $_GET['type'] : "";
			$time = time();

			if(!$password){
				return json_encode([
					"code" => 400,
					"message" => "密码不能为空"
				]);
			}
		    $sql = "INSERT INTO user (password, is_admin, type,create_date) VALUES ('$password', $is, '$type',$time)";
		    $conn->exec($sql);
		    return json_encode([
		    	"password" => $password
		    ]);
		}else{
			return json_encode([
				"code" => 400,
				"message" => "无权限增加用户"
			]);
		}
	}
}

function js($conn)
{
	$type = isset($_GET["rowtype"]) ? $_GET["rowtype"] : "";
	$stmt = $conn->prepare("SELECT * FROM weixin WHERE type = '$type' ORDER BY create_date DESC");
    $stmt->execute();

    // 设置结果集为关联数组
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $res = json_encode($stmt->fetchAll());
    $res = json_decode($res,true);
    $wxidLists = [];
    foreach ($res as $key => $value) {
    	# code...
    	if($value['status'] == 1){
    		array_push($wxidLists, $value['wxid']);
    	}
    }
    $resArr = json_encode($wxidLists);
	echo "var Arr =$resArr; var n = Math.round(Math.random() * (Arr.length - 1));";
}

try {

    //设置 PDO 错误模式，用于抛出异常
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $time = time();
    // $sql = "INSERT INTO weixin (wxid, status, create_date) VALUES ('hechongos', 1, $time)";
    // // 使用 exec() ，没有结果返回
    // $conn->exec($sql); update_user  delete_user
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if(isset($_GET["delete_user"])){
    	echo deleteUser($conn);
    	return;
    }
    if(isset($_GET["update_user"])){
    	echo updateUser($conn);
    	return;
    }
    if(isset($_GET["query_users"])){
    	echo queryUsers($conn);
    	return;
    }
    if(isset($_GET["add_user"])){
    	echo addUser($conn);
    	return;
    }
	if(isset($_GET["password"])){
    	echo auth($conn);
    	return;
    }
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if(isset($_GET["type"])){

	    if($_GET["type"] == "query"){
	    	echo query($conn);
	    	return;
	    }
	    if($_GET["type"] == "insert"){
	    	echo insert($conn);
	    	return;
	    }

	    if($_GET["type"] == "update"){
	    	echo update($conn);
	    	return;
	    }

	    if($_GET["type"] == "delete"){
	    	echo delete($conn);
	    	return;
	    }
	}else{
		js($conn);
    	return;
	}



    // foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    //     echo $v;
    // }
    //echo "新记录插入成功";
    return;
}
catch(PDOException $e)
{
	echo json_encode([
		"code" => 400
	]);
	return;
    echo $sql . "<br>" . $e->getMessage();
}
//echo "var Arr =['QQtn199','QQtn150']; var n = Math.round(Math.random() * (Arr.length - 1));";

//$conn = null;
?>
