<?php

include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (!empty($_POST)) {	
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
	$username = md5($_SESSION['username']);
    $stmt = $pdo->prepare('INSERT INTO polls VALUES (NULL, ?, ?, ?)');
    $stmt->execute([$title, $desc, $username]);	
    $poll_id = $pdo->lastInsertId();	
    $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';
    foreach ($answers as $answer) {		
        if (empty($answer)) continue;		
        $stmt = $pdo->prepare('INSERT INTO poll_answers VALUES (NULL, ?, ?, 0)');
        $stmt->execute([$poll_id, $answer]);
    }	
    $msg = 'Stworzono pomyślnie!';
}
?>
