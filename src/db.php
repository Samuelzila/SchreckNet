<?php
require_once __DIR__ . '/../config/config.php';

// create table if it doesn't exist
function createTables()
{
	$db = new PDO("sqlite:" . DB_PATH);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$db->exec("CREATE TABLE IF NOT EXISTS users (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		username TEXT NOT NULL UNIQUE,
		password_hash TEXT NOT NULL,
		role TEXT NOT NULL DEFAULT 'spc'
	)");
	$db->exec("CREATE TABLE IF NOT EXISTS boards (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		name TEXT NOT NULL,
		description TEXT
	)");
	$db->exec("CREATE TABLE IF NOT EXISTS threads (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		board_id INTEGER NOT NULL,
		author_id INTEGER NOT NULL,
		title TEXT NOT NULL,
		created_at DATETIME NOT NULL,
		FOREIGN KEY (board_id) REFERENCES boards(id),
		FOREIGN KEY (author_id) REFERENCES users(id)
	)");
	$db->exec("CREATE TABLE IF NOT EXISTS posts (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		thread_id INTEGER NOT NULL,
		author_id INTEGER NOT NULL,
		body TEXT NOT NULL,
		created_at DATETIME NOT NULL,
		FOREIGN KEY (thread_id) REFERENCES threads(id),
		FOREIGN KEY (author_id) REFERENCES users(id)
	)");

	// Create a default storyteller user if none exists
	$stmt = $db->query("SELECT COUNT(*) FROM users WHERE role = 'storyteller'");
	$adminCount = $stmt->fetchColumn();
	if ($adminCount == 0) {
		$defaultAdminPassword = password_hash("changeme", PASSWORD_DEFAULT);
		$stmt = $db->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
		$stmt->execute(["storyteller", $defaultAdminPassword, "storyteller"]);
	}
}

function getDB()
{
	createTables();
	static $db = null;
	if ($db === null) {
		$db = new PDO("sqlite:" . DB_PATH);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	return $db;
}
