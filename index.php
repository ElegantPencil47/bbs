<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $time = date('Y-m-d H:i:s');
  $post = $time . ' - ' . $name . ': ' . $comment . "\n";
  file_put_contents('posts.txt', $post, FILE_APPEND);
  $title = $_POST['comment'] . '.php';
  copy('eclipse.php', $title); 
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
    <style>
         
        a {
          color: #fff;;
        }

        body {
          background-color: #000033;;
        }
        .neon{
          color: #fff;
          text-shadow:
            0 0 10px #ff5bff,
            0 0 20px #ff5bff,
            0 0 40px #ff5bff,
            0 0 80px #ff5bff,
            0 0 100px #ff5bff;
        }
              .neon_blue{
          color: #fff;
          text-shadow:
            0 0 10px #00ffff,
            0 0 20px #00ffff,
            0 0 40px #00ffff,
            0 0 80px #00ffff,
            0 0 100px #00ffff;
        }
                    .green_neon{
          color: #fff;
          text-shadow:
            0 0 10px #05ff05,
            0 0 20px #05ff05,
            0 0 40px #05ff05,
            0 0 80px #05ff05,
            0 0 100px #05ff05;
        }


    </style>
<body>
<h1 class="green_neon">LunarEclipse掲示板</h1>
<form action="" method="post">
  <label for="name" class="neon">名前:</label>
  <input type="text" name="name" id="name">
  <br>
  <label for="comment" class="neon_blue">コメント:</label>
  <textarea name="comment" id="comment"></textarea>
  <br>
  <input type="submit" value="投稿">
</form>
<?php
$posts = file_get_contents('posts.txt');
$posts = nl2br(htmlspecialchars($posts, ENT_QUOTES, 'UTF-8'));
?>
<div id="posts" class="green_neon">
<a href="<?= $title ?"><?= $comment ?></a><br>
</div>
</body>
</html>