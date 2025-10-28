<?php

require_once "db.php";

function authenticate($username, $password)
{
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
	$stmt->execute([$username]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user && password_verify($password, $user["password_hash"])) {
		$_SESSION["user"] = $user;
		return true;
	} else {
		return false;
	}
}

function isLoggedIn()
{
	return isset($_SESSION["user"]);
}

function currentUser()
{
	return $_SESSION["user"] ?? null;
}

function requireLogin()
{
	if (!isLoggedIn()) {
		header("Location: login.php");
		exit;
	}
}

function isAdmin()
{
	$user = currentUser();
	return $user && $user["role"] === "admin";
}

function impersonate($userId)
{
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->execute([$userId]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user) $_SESSION["user"] = $user;
}
