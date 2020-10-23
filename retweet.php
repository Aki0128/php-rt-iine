<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];

    $retweet = $db->prepare('SELECT COUNT(*) AS cnt FROM retweet WHERE member_id=? AND retweet_post_id=?');
    $retweet->execute(array(
      $_SESSION['id'],
      $id));
    $rt = $retweet->fetch();
    if ($rt['cnt'] == 0) {
        $retweet_on = $db->prepare('INSERT INTO retweet SET member_id=?, retweet_post_id=?, created=NOW()');
        $retweet_on->execute(array(
          $_SESSION['id'],
          $id
        ));
    } else {
        $retweet_off = $db->prepare('DELETE FROM retweet WHERE member_id=? AND retweet_post_id=?');
        $retweet_off->execute(array(
          $_SESSION['id'],
          $id
        ));
    }
}
  
  header('Location: index.php');
  exit();
