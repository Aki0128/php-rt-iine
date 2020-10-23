<?php
session_start();
require('dbconnect.php');

if (empty($_REQUEST['id'])) {
    header('Location: index.php');
    exit();
}

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="style.css" />
  <script src="https://kit.fontawesome.com/87bb931ed4.js" crossorigin="anonymous"></script>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
  <p>&laquo;<a href="index.php">一覧にもどる</a></p>

  <?php if ($post = $posts->fetch()): ?>
    <div class="msg">
    <img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>" />
    <p><?php print(htmlspecialchars($post['message'])); ?><span class="name">（<?php print(htmlspecialchars($post['name'])); ?> ）</span></p>
    <!-- いいねの処理 -->
    <?php
    $likes = $db->prepare('SELECT COUNT(*) AS cnt FROM likes WHERE member_id=? AND like_post_id=?');
    $likes->execute(array(
      $_SESSION['id'],
      $_REQUEST['id']));
    $like = $likes->fetch();
    ?>
    <!-- いいねボタン -->
    <?php if ($like['cnt'] == 0): ?>
    <a href="like.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"><i class="far fa-heart"></i></a>
    <?php else: ?>
    <a href="like.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"><i class="fas fa-heart"></i></a>
    <?php endif; ?>
    <!-- リツイートの処理 -->
    <?php
    $retweet = $db->prepare('SELECT COUNT(*) AS cnt FROM retweet WHERE member_id=? AND retweet_post_id=?');
    $retweet->execute(array(
      $_SESSION['id'],
      $_REQUEST['id']));
    $rt = $retweet->fetch();
    ?>
    <!-- リツイートボタン -->
    <?php if ($rt['cnt']== 0): ?>
    <a href="retweet.php?id=<?php print(htmlspecialchars($post['id'])); ?>"><i class="fas fa-retweet"></i></a>
    <?php else: ?>
    <a href="retweet.php?id=<?php print(htmlspecialchars($post['id'])); ?>" style="color: #fc9ce7"><i class="fas fa-retweet"></i></a>
    <?php endif; ?>
    <!-- リツイート件数表示の処理 -->
    <?php
    $retweet_count = $db->prepare('SELECT COUNT(*) AS cnt FROM retweet WHERE retweet_post_id=?');
    $retweet_count->execute(array(
      $post['id']
    ));
    $rt_count = $retweet_count->fetch();
    if ($rt_count['cnt'] > 0) {
        echo $rt_count['cnt'];
    }
    ?>

    <p class="day"><?php print(htmlspecialchars($post['created'])); ?></p>
    </div>
  <?php else: ?>
    <p>その投稿は削除されたか、URLが間違えています</p>
  <?php endif; ?>
  </div>
</div>
</body>
</html>
