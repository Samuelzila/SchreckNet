<?php

require_once "db.php";

function getAllPosts()
{
	$db = getDB();
	return $db->query("SELECT p.id, p.title, p.body, u.username, p.created_at
		FROM posts p
		JOIN users u ON u.id = p.author_id
		ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
}

function createPost($authorId, $title, $body)
{

	$db = getDB();
	$stmt = $db->prepare("INSERT INTO posts (author_id, title, body, created_at) VALUES (?,?,?,datetime('now'))");

	return $stmt->execute([$authorId, $title, $body]);
}
