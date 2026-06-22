<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $time = date('Y-m-d H:i:s');
  $post = $time . ' - ' . $name . ': ' . $comment . "\n";
  file_put_contents('posts.txt', $post, FILE_APPEND);
  header('Location: ' . $_SERVER['REQUEST_URI']);
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>掲示板</title>
</head>
<body>
<form action="" method="post">
  <label for="name">名前:</label>
  <input type="text" name="name" id="name">
  <br>
  <label for="comment">コメント:</label>
  <textarea name="comment" id="comment"></textarea>
  <br>
  <input type="submit" value="投稿">
</form>
<?php
$posts = file_get_contents('posts.txt');
$posts = nl2br(htmlspecialchars($posts, ENT_QUOTES, 'UTF-8'));
?>
<div id="posts">
  <?= $posts ?>
</div>
</body>
</html>