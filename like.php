<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];

    $likes = $db->prepare('SELECT COUNT(*) AS cnt FROM likes WHERE member_id=? AND like_post_id=?');
    $likes->execute(array(
      $_SESSION['id'],
      $id));
    $like = $likes->fetch();
    if ($like['cnt'] == 0) {
        $likes_on = $db->prepare('INSERT INTO likes SET member_id=?, like_post_id=?');
        $likes_on->execute(array(
          $_SESSION['id'],
          $id
        ));
    } else {
        $like_off = $db->prepare('DELETE FROM likes WHERE member_id=? AND like_post_id=?');
        $like_off->execute(array(
          $_SESSION['id'],
          $id
        ));
    }
}
  
  header('Location: index.php');
  exit();
