<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] +3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {
    if ($_POST['message'] !=='') {
        $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_post_id=?, created=NOW()');
        $message->execute(array(
      $member['id'],
      $_POST['message'],
      $_POST['reply_post_id']
    ));

        header('Location: index.php');
        exit();
    }
}

$page =$_REQUEST['page'];
if ($page == '') {
    $page = 1;
}
$page = max($page, 1);

$counts = $db->query('SELECT(SELECT COUNT(*) FROM posts)+(SELECT COUNT(*) FROM retweet) AS cnt');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 10);
$page = min($page, $maxPage);

$start = ($page -1) * 10;

$posts= $db->prepare('SELECT posts.*, members.name, members.picture, 0 AS rt_flag FROM posts, members WHERE posts.member_id=members.id 
UNION ALL 
SELECT posts.id, posts.message, posts.member_id, null AS reply_post_id, retweet.created, null AS modified, members.name, members.picture, 1 AS rt_flag FROM retweet, posts, members WHERE posts.id=retweet.retweet_post_id AND members.id=posts.member_id 
ORDER BY created DESC LIMIT ?,10');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

if (isset($_REQUEST['res'])) {
    // 返信の処理
    $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
    $response->execute(array($_REQUEST['res']));

    $table = $response->fetch();
    $message = '@' . $table['name'] . ' ' . $table['message'];
}

// htmlspecialcharsのショートカット
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}

// 本文内のURLにリンクを設定
function makeLink($value)
{
    return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>', $value);
}
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
  	<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <form action="" method="post">
      <dl>
        <dt><?php print(h($member['name'], ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt>
        <dd>
          <textarea name="message" cols="50" rows="5"><?php print(h($message, ENT_QUOTES)); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php print(h($_REQUEST['res'], ENT_QUOTES)); ?>" />
        </dd>
      </dl>
      <div>
        <p>
          <input type="submit" value="投稿する" />
        </p>
      </div>
    </form>

<?php foreach ($posts as $post): ?>
    <div class="msg">
    <!-- リツイートした人を表示する処理 -->
    <?php
    $retweet_name = $db->prepare('SELECT * FROM members, retweet WHERE members.id=retweet.member_id AND retweet_post_id=? AND retweet.created=?');
    $retweet_name->execute(array(
      $post['id'],
      $post['created']));
    $rt_name = $retweet_name->fetch();
    ?>
    
    <?php if ($post['rt_flag'] == 1): ?>
    <p><?php print($rt_name['name']) ?>さんがリツイート</p>
    <?php endif; ?>
  
    <img src="member_picture/<?php print(h($post['picture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(h($post['name'], ENT_QUOTES)); ?>" />
    <p><?php echo makeLink(h($post['message'], ENT_QUOTES)); ?><span class="name">（<?php print(h($post['name'], ENT_QUOTES)); ?>）</span>[<a href="index.php?res=<?php print(h($post['id'], ENT_QUOTES)); ?>">Re</a>]</p>
    <!-- いいねの処理 -->
    <?php
    $likes = $db->prepare('SELECT COUNT(*) AS cnt FROM likes WHERE member_id=? AND like_post_id=?');
    $likes->execute(array(
      $_SESSION['id'],
      $post['id']));
      $like = $likes->fetch();
      ?>
    <!-- いいねボタン -->
    <?php if ($like['cnt'] == 0): ?>
    <a href="like.php?id=<?php print(h($post['id'], ENT_QUOTES)); ?>"><i class="far fa-heart"></i></a>
    <?php else: ?>
    <a href="like.php?id=<?php print(h($post['id'], ENT_QUOTES)); ?>"><i class="fas fa-heart"></i></a>
    <?php endif; ?>
      <!-- リツイートの処理 -->
      <?php
      $retweet = $db->prepare('SELECT COUNT(*) AS cnt FROM retweet WHERE member_id=? AND retweet_post_id=?');
      $retweet->execute(array(
        $_SESSION['id'],
        $post['id']));
      $rt = $retweet->fetch();
      ?>
    <!-- リツイートボタン -->
    <?php if ($rt['cnt'] == 0): ?>
    <a href="retweet.php?id=<?php print(h($post['id'])); ?>"><i class="fas fa-retweet"></i></a>
    <?php else: ?>
    <a href="retweet.php?id=<?php print(h($post['id'])); ?>" style="color: #fc9ce7"><i class="fas fa-retweet"></i></a>
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
    
    <p class="day"><a href="view.php?id=<?php print(h($post['id'])); ?>"><?php print(h($post['created'], ENT_QUOTES)); ?></a>

    <?php if ($post['reply_post_id'] > 0): ?>
<a href="view.php?id=<?php print(h($post['reply_post_id'], ENT_QUOTES)); ?>">
返信元のメッセージ</a>
<?php endif; ?>

<?php if ($_SESSION['id'] == $post['member_id']): ?>
[<a href="delete.php?id=<?php print(h($post['id'])); ?>"
style="color: #F33;">削除</a>]
<?php endif; ?>
    </p>
    </div>
<?php endforeach; ?>

<ul class="paging">
<?php if ($page > 1): ?>
  <li><a href="index.php?page=<?php print($page-1); ?>">前のページへ</a></li>
<?php else: ?>
  <li>前のページへ</li>
<?php endif; ?>

<?php if ($page < $maxPage): ?>
  <li><a href="index.php?page=<?php print($page+1); ?>">次のページへ</a></li>
<?php else: ?>
  <li>次のページへ</li>
<?php endif; ?>
</ul>
  </div>
</div>
</body>
</html>
