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
		header("Location: index.php");
		exit;
	}
}

function isAdmin()
{
	$user = currentUser();
	// The storyteller is always considered an admin.
	return $user && ($user["role"] === "admin" || $user["role"] === "storyteller");
}

function isStoryteller()
{
	$user = currentUser();
	return $user && $user["role"] === "storyteller";
}

function impersonate($userId)
{
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->execute([$userId]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user) $_SESSION["user"] = $user;
}

function createUser($username, $password, $role = "player", $clan = null, $affiliation = null)
{
	$db = getDB();
	$passwordHash = password_hash($password, PASSWORD_DEFAULT);
	$clanAvatarPath = getClanAvatarPath($clan);
	$stmt = $db->prepare("INSERT INTO users (username, password_hash, role, clan, affiliation, avatar) VALUES (?, ?, ?, ?, ?, ?)");
	$stmt->execute([$username, $passwordHash, $role, $clan, $affiliation, $clanAvatarPath]);
}
function getClanAvatarPath($clan)
{
	$clanAvatars = [
		'Banu Haqim' => 'SymbolClanBanuHaqimV5.webp',
		'Brujah' => 'SymbolClanBrujahV5.webp',
		'Gangrel' => 'SymbolClanGangrelV5.webp',
		'Giovanni' => 'SymbolGiovanniV5Classic.webp',
		'Lasombra' => 'LogoClanLasombraV5.webp',
		'Malkavian' => 'SymbolClanMalkavianV5.png',
		'Ministry' => 'SymbolMinistryV5.webp',
		'Nosferatu' => 'SymbolClanNosferatuV5.webp',
		'Ravnos' => 'SymbolClanRavnosV5.webp',
		'Toreador' => 'SymbolClanToreadorV5.webp',
		'Tremere' => 'SymbolClanTremereV5.webp',
		'Tzimisce' => 'LogoClanTzimisce.webp',
		'Ventrue' => 'SymbolClanVentrueV5.webp',
	];

	return "assets/avatars/" . ($clanAvatars[$clan] ?? 'default.png');
}
