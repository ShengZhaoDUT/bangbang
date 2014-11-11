<?php
	session_start();
	/**
	 * state: 
	 * (1) successful
	 * (2) failed
	 * (3) username registered
	 * (4) nullpointer exception: username or password
	 * (5) welcome back
	*/
	$state = 2;
	print_r($_COOKIE);
	if(isset($_COOKIE['username']) && $_COOKIE['username'] == $_SESSION['username']) {
		$state = 5;
	}
	else {

		// 1. get username and password
		$username = $_POST['username'];
		$password = $_POST['password'];
		var_dump($username);
		if(!$username || !$password) {
			$state = 4;
		}
		//var_dump($username);
		//var_dump($password);
		// 1. get mysql connection
		include '../utility/PhpConnectMySQL.php';
		$con = new PhpConnectMySQL('localhost', 3306, 'root', 'zs', 'NEITUI');
		if(!$con) {
			trigger_error("Cannot connect mysql");
		}

		// 2. register logic
		$sql = "select username from USER where username=".'"'.$username.'"';
		$result = $con->selectAsOne($sql);
		if(!$result) {
			$sql = "insert into USER (username, password) values (".'"'.$username.'"'.",".'"'.$password.'"'.")";
			$affectNum = $con->modify($sql);
			if($affectNum) {
				$state = 1;	
				setcookie("username", $username, time()+3600);
				setcookie("PHPSESSID", session_id(), time()+3600);
				$_SESSION['username'] = $username;
			}
			else {
				$state = 2;
			}
		}
		else {
			$state = 3;
		}
	}

	$arr = array('state'=>$state);
	echo json_encode($arr);
?>