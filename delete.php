<?php

  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "Musisz się zalogować!";
  	header('location: /phpoll/registration/login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: /phpoll/registration/login.php");	
  }

include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_GET['id'])) {
		
    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);

	$stmt = $pdo->prepare('SELECT createdBy FROM polls WHERE id = ?');
    $stmt->execute([$_GET['id']]);
	$poll_user = $stmt->fetchColumn();	

    if (!$poll) {
        die ('Ankieta z takim ID nie istnieje!');
    }
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes' and $poll_user != md5($_SESSION['username'])) {
			$msg = 'Nie możesz usunąć ankiety utworzonej przez innego użytkownika!';
        } else if ($_GET['confirm'] == 'yes' and $poll_user == md5($_SESSION['username'])){
			$stmt = $pdo->prepare('DELETE FROM polls WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $stmt = $pdo->prepare('DELETE FROM poll_answers WHERE poll_id = ?');
            $stmt->execute([$_GET['id']]);
			$stmt = $pdo->prepare('DELETE FROM user_answers WHERE poll_id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Pomyślnie usunąłeś ankietę!';
		} else {
            header('Location: index.php');
            exit;
        }
    }
} else {
    die ('Brak określonego ID!');
}
?>
