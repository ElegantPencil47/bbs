<?php
  $file = $_SERVER['SCRIPT_FILENAME'];
  $file = str_replace(".php","",$file);
  $one = $file;
  $one = str_replace("/var/www/html/","",$one);
  $one = "<h1>" . $one . "</h1>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  if(empty($name)){
  $name = "@名無し";
  }
  $comment = $_POST['comment'];
  $time = date('Y-m-d H:i:s');
  $post = 'TEST<div class="post" style="display: flex; align-items: baseline; gap: 10px;">TEST<h3 class="green_neon">' . $comment . 'TEST</h3>TEST<p class="hai">' . $name . $time . 'TEST</p>'. "\n" . 'TEST</div>';

  file_put_contents($file . '.txt', $post, FILE_APPEND);
  header('Location: ' . $_SERVER['REQUEST_URI']); 
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>投稿　LunarEclipse</title>
</head>
    <style>

        .hai{
          color: #A9B2C3;
        }
        body {
          background-color: #000033;;
        }
        a{
          color: #fff;
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

        .post{
          position: relative;

          margin: 10px;
        }
        .post::before {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          border-bottom: 3px solid #fff;
          border-left: 3px solid #fff;
          filter:
            drop-shadow(-2px 2px 2px #37beb0)
            drop-shadow(-4px 4px 8px #37beb0)
            drop-shadow(-8px 8px 16px #37beb0)
            drop-shadow(-12px 12px 32px #37beb0);
          }
           
        
    </style>
<body class="neon_blue">
<a href = "/">ホームに戻る</a>
<?php

$posts = file_get_contents($file . '.txt');
$posts = nl2br(htmlspecialchars($posts, ENT_QUOTES, 'UTF-8'));
$one = str_replace("&lt;h1&gt","<h1>",$one);
$posts = str_replace("TEST&lt;/p","</p",$posts);
$posts = str_replace("TEST&lt;p","<p",$posts);
$posts = str_replace("TEST&lt;/h3","</h3",$posts);
$posts = str_replace("TEST&lt;h3","<h3",$posts);
$posts = str_replace("TEST&lt;/div","</div",$posts);
$posts = str_replace("TEST&lt;div","<div",$posts);
$posts = str_replace("&quot;","\"",$posts);
$posts = str_replace("&gt;",">",$posts);

?>
<div class="neon">
<?= $one ?>
</div>
<h3 class = "neon_blue">返信する</h3>
<form action="" method="post">
  <label for="name" class="neon">名前:</label>
  <input type="text" name="name" id="name">
  <br>
  <label for="comment" class="neon_blue">コメント:</label>
  <textarea name="comment" id="comment"></textarea>
  <br>
  <input type="submit" value="投稿">
</form>
<div id="posts">
  <?= $posts ?>
</div>
</body>
</html>