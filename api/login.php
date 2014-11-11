<?php
	session_start();
	// 1. get username and password
	$username = $_POST['username'];
	$password = $_POST['password'];
	var_dump($username);
	var_dump($password);
	$passwd = md5($password);
	var_dump($passwd);
	/*
	// 2. get mysql connection
	include '../utility/PhpConnectMySQL.php';
	$con = new PhpConnectMySQL('localhost', 3306, 'root', 'zs', 'NEITUI');
	if(!$con) {
		die('Could not connect mysql:');
	}
	// 3. make up sql statement
	$sql = "select username, password from USER where username=".$username;
	$result = $con.selectAsArray($sql);
	var_dump($result);*/
?>