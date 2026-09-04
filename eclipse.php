<?php

$alert = "<script type='text/javascript'>alert('本文書け');</script>";
$alert2 = "<script type='text/javascript'>alert('本文長すぎ');</script>";
  $file = $_SERVER['SCRIPT_FILENAME'];
  $file = str_replace(".php","",$file);
  $one = $file;
  $filename = fopen($file . ".txt", "r");
  $one = str_replace("/var/www/html/","",$one);
  $one = "<h1>" . $one . "</h1>";
if (($line = fgets($filename)) !== false) {
  $title = $line;
}
fclose($filename);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if($_POST['comment'] == ""){
echo $alert;
}elseif (strlen($_POST['comment']) > 350){
echo $alert2;
}else{
  $name = $_POST['name'];
  if(empty($name)){
  $name = "@名無し";
  }
  $comment = $_POST['comment'];
  $time = date('Y-m-d H:i:s');
  $post = 'TEST<div class="post" style="display: flex; align-items: baseline; gap: 10px;">TEST<h3 class="green_neon">' . $comment . 'TEST</h3>TEST<p class="hai">' . $name . $time . 'TEST</p>'. "\n" . 'TEST</div>';


$post = str_replace('https://m.youtube.com/watch?v=','https://www.youtube.com/watch?v=',$post);
$youtube = str_replace('https://www.youtube.com/watch?v=','<iframe width="560" height="315" src="https://www.youtube.com/embed/',$post);
$kazu = strpos($youtube, 'bed/');
$id = mb_substr($youtube, $kazu + 4, 11);

$youtube = insertStr2('<iframe width="560" height="315" src="https://www.youtube.com/embed/" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>', $id, 68);

if (str_contains($post, "https://www.youtube.com/watch?v=")) {
    $post = $post . $youtube;
} 


  file_put_contents($file . '.txt', $post . "\n", FILE_APPEND);
  header('Location: ' . $_SERVER['REQUEST_URI']); 
  exit;
}}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>投稿　LunarEclipse</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
    <style>
        .HSN{

        }
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


        .option{
          position: fixed;
          bottom: 50px;
          left: 50px; 
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


        .fai{
          position: relative;
          padding: 10px;
          margin: 30px;
          color: #ffa500;
          text-shadow:
            0 0 10px #ff0000,
            0 0 20px #ff0000,
            0 0 40px #ff0000,
            0 0 80px #ff0000,
            0 0 100px #ff0000;
        }
        .fai::before {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          border-bottom: 3px solid #ffa500;
          border-left: 3px solid #ffa500;
          filter:
            drop-shadow(-2px 2px 2px #ff0000)
            drop-shadow(-4px 4px 8px #ff0000)
            drop-shadow(-8px 8px 16px #ff0000)
            drop-shadow(-12px 12px 32px #ff0000);
          }
        .option{
          position: fixed;
          top: 50px;
          right: 50px; 
        }


        .post{
          position: relative;
          padding: 10px;
          margin: 30px;
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
        .option{
          position: fixed;
          top: 50px;
          right: 50px; 
        }
           
        
    </style>


<body class="neon_blue"><br>
<div class="option">
<i class="fa-solid fa-envelope"></i>
</div>


<a href = "/">
<div class="fai">
ホームに戻る
</div>
</a>
<?php


function insertStr2($text, $insert, $num){
    return preg_replace("/^.{0,$num}+\K/us", $insert, $text);
}

$posts = '';
$kesu = "$file.txt";
$kesu2 = "$file.php";
$nowtime = date("Ym");
if(file_exists($file . '.txt')){

$posts = file_get_contents($file . '.txt');

$youtube = '';
$last = date("Ym", filemtime($file . '.txt'));
if($last == $nowtime){

}else{
  unlink($kesu);
  unlink($kesu2);
  $posts = '';
}
}





$posts = nl2br(htmlspecialchars($posts, ENT_QUOTES, 'UTF-8'));


$one = str_replace("&lt;h1&gt","<h1>",$one);
$posts = str_replace("TEST&lt;/p","</p",$posts);
$posts = str_replace("&lt;iframe","<iframe",$posts);
$posts = str_replace("&lt;/iframe","</iframe",$posts);
$posts = str_replace("TEST&lt;p","<p",$posts);
$posts = str_replace("TEST&lt;/h3","</h3",$posts);
$posts = str_replace("TEST&lt;h3","<h3",$posts);
$posts = str_replace("TEST&lt;/div","</div",$posts);
$posts = str_replace("TEST&lt;div","<div",$posts);
$posts = str_replace("&quot;","\"",$posts);
$posts = str_replace("&gt;",">",$posts);
?>




<br>
<br>
<h1>
てすと
</h1>

<br>
<br>
<br>
<br>
<br>
<br>




<div class="option">
<h3 class = "neon_blue">返信する</h3>
<form action="" method="post">
  <label for="name" class="neon">名前:</label>
  <input type="text" name="name" id="name">
  <br>
  <label for="comment" class="neon_blue">コメント:</label>
  <textarea name="comment" id="comment"></textarea>
  <br>
  <input type="submit" value="返信する" class="HSN">
</form>
</div>








<div id="posts" class="green_neon">
  <?= $posts ?>
</div>









</body>
</html>