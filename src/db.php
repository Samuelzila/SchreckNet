<?php
require_once __DIR__ . '/../config/config.php';

// Create data folder if it doesn't exist
if (!file_exists("../data")) {
	mkdir("../data", 0770);
}
// Create uploads folder if it doesn't exist
if (!file_exists("../public/uploads")) {
	mkdir("../public/uploads", 0770);
	mkdir("../public/uploads/images", 0770);
	mkdir("../public/uploads/attachments", 0770);
}

// create tables if they doesn't exist
$db = getDB();
$db->exec("CREATE TABLE IF NOT EXISTS users (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	username TEXT NOT NULL UNIQUE,
	password_hash TEXT NOT NULL,
	clan TEXT,
	affiliation TEXT,
	avatar TEXT DEFAULT '../public/assets/avatars/default.png',
	role TEXT NOT NULL DEFAULT 'spc',
	signature TEXT DEFAULT ''
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
	body TEXT,
	created_at DATETIME NOT NULL,
	FOREIGN KEY (thread_id) REFERENCES threads(id),
	FOREIGN KEY (author_id) REFERENCES users(id)
)");
$db->exec("CREATE TABLE IF NOT EXISTS attachments (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	post_id INTEGER,
	stored_as TEXT,
	original_name TEXT,
    	type TEXT,
	FOREIGN KEY(post_id) REFERENCES posts(id))
");
$db->exec("
	CREATE TABLE IF NOT EXISTS user_settings (
	user_id INTEGER,
	setting_name TEXT,
	setting_value TEXT,
	PRIMARY KEY (user_id, setting_name),
	FOREIGN KEY (user_id) REFERENCES users(id)
)");

// Create a default storyteller user if none exists
$stmt = $db->query("SELECT COUNT(*) FROM users WHERE role = 'storyteller'");
$adminCount = $stmt->fetchColumn();
if ($adminCount == 0) {
	$defaultAdminPassword = password_hash("changeme", PASSWORD_DEFAULT);
	$stmt = $db->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
	$stmt->execute(["storyteller", $defaultAdminPassword, "storyteller"]);
}

function getDB()
{
	static $db = null;
	if ($db === null) {
		$db = new PDO("sqlite:" . DB_PATH);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	return $db;
}
