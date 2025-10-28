<?php
$db = new PDO('sqlite:data/schrecknet.db');
$db->exec("
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT,
  password_hash TEXT,
  role TEXT
);
CREATE TABLE posts (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  author_id INTEGER,
  title TEXT,
  body TEXT,
  created_at TEXT
);
");
$pw = password_hash('changeme', PASSWORD_DEFAULT);
$db->exec("INSERT INTO users (username, password_hash, role) VALUES ('admin', '$pw', 'admin')");
echo 'Database initialized. Login with admin / changeme';
