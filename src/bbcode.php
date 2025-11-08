<?php
function parseBBCode($text)
{
	$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

	// Bold / Italic
	$text = preg_replace('/\[b\](.*?)\[\/b\]/is', '<strong>$1</strong>', $text);
	$text = preg_replace('/\[i\](.*?)\[\/i\]/is', '<em>$1</em>', $text);

	// Links
	$text = preg_replace('/\[url=(.*?)\](.*?)\[\/url\]/is', '<a href="$1" target="_blank" rel="nofollow">$2</a>', $text);

	// Images (will only work with uploaded image filenames we allow)
	$text = preg_replace('/\[img\](.*?)\[\/img\]/is', '<img src="uploads/images/$1" class="post-image">', $text);

	// Line breaks
	return nl2br($text);
}
