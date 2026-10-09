<?php

$alert = "<script type='text/javascript'>alert('本文書け');</script>";
$alert2 = "<script type='text/javascript'>alert('本文長すぎ');</script>";
  $file = $_SERVER['SCRIPT_FILENAME'];
  $file = str_replace(".php","",$file);
  $one = $file;
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

    $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip = trim($ip_list[0]);
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$ip = str_replace(".","",$ip);
$ip = $ip + 6219476234;
$ip = str_replace("3","a",$ip);
$ip = str_replace("1","h",$ip);
$ip = str_replace("7","j",$ip);
$ip = str_replace("8","x",$ip);
$ip = str_replace("0","r",$ip);
$ip = "ID : " . $ip;
  $filename = fopen($file . ".txt", "r");
  $one = str_replace("/var/www/html/","",$one);
  $one = "TEST<h1>" . $one . "TEST</h1>";
if (($line = fgets($filename)) !== false) {
  $title = $line;
}
fclose($filename);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {




session_start();

$now = microtime(true);

if (
    isset($_SESSION['last_post_time']) &&
    ($now - $_SESSION['last_post_time']) < 5
) {
    exit('連投ヤメロ');
}

$_SESSION['last_post_time'] = $now;





if($_POST['comment'] == ""){
echo $alert;
}elseif (strlen($_POST['comment']) > 6750){
echo $alert2;
}else{
  $name = $_POST['name'];
  if(empty($name)){
  $name = "@名無し";
  }
  $comment = $_POST['comment'];
  $time = date('Y-m-d H:i:s');
  $post = 'TEST<div class="post" style="display: flex; align-items: baseline; gap: 10px;">TEST<p class="hai">' . $name . 'TEST</p>TEST<p>' . $ip . 'TEST</p>TEST<h3 class="green_neon">' . $comment . 'TEST</h3>TEST<p class="hai">' . $time . 'TEST</p>'. "\n" . 'TEST</div>';


$post = str_replace('https://m.youtube.com/watch?v=','https://www.youtube.com/watch?v=',$post);
$youtube = str_replace('https://www.youtube.com/watch?v=','<iframe width="560" height="315" src="https://www.youtube.com/embed/',$post);
$kazu = strpos($youtube, 'bed/');
$id = mb_substr($youtube, $kazu + 4, 11);
$image_path = "";
$youtube = insertStr2('<iframe width="560" height="315" src="https://www.youtube.com/embed/" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>', $id, 68);

if (str_contains($post, "https://www.youtube.com/watch?v=")) {
    $post = $post . $youtube;
} 
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  if (is_uploaded_file($_FILES['image']['tmp_name'])) {
    $image_path = 'images/' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
	
 }}

$post = 'TEST<div class="post" style="display: flex; align-items: baseline; gap: 10px;">TEST<p class="hai">' . $name . 'TEST</p>TEST<p>' . $ip . 'TEST</p>TEST<h3 class="green_neon">' . $comment . 'TEST</h3>TEST<br>TEST<img class="gazou "src="' . $image_path . '">TEST<p class="hai">' . $time . 'TEST</p>TEST</div>';
 

  file_put_contents($file . '.txt', $post . "\n", FILE_APPEND);
  header('Location: ' . $_SERVER['REQUEST_URI']); 
  exit;
}}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>投稿 LunarEclipse</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
    <style>
        .bana{
          background-color: black;
			color: #fff;
        }

        .hai{
          color: #A9B2C3;
        }
         
        a {
          color: #fff;
        }

        body {
          background-color: #fff;
        }
        .moji{
          color: black;
        }

		.siro{
color: #fff;
			
		}
        .sns{

font-size:39px; 
}

        .nico{
  position: relative;
  top: 3px;
border-radius: 5px;
  width: 35px; 
  height: 35px;
}
				.gazou{
			height: 280px;
		}

        .option{
          display: flex;
          position: fixed;
          bottom: 30px;
          z-index: 9999; 

					  background-color: white;

          z-index: 9999; 
          padding: 20px;
          margin: 30px;
          border:5px solid black;
        }
        .user{
display: flex;

}
        .fai{
          color: black;

        }
        .waku{
          position: relative;
          padding: 20px;
          margin: 30px;
          border:5px solid black;


        }
        .fais{
          position: relative;
          padding: 20px;
          margin: 30px;
          border:5px solid #ffa500;


        }
	.sample_menu_outer {
		margin: 5rem auto;
		}
	.sample_menu {
		width: 500px;
		padding: 10px 20px;
		}
	.sample_menu_parent {
		cursor: pointer;
		}
	.sample_menu_parent::before {
		content: '▼';
		display: inline-block;
		transform: rotate(-360deg);
		transition: .9s;
		}
		
	.sample_menu_parent.active::before {
		transform: rotate(0deg);
		}
	.sample_menu_child {

		height: 0;
		opacity: 0;
		visibility: hidden;
		transition: .4s;
		}
	.sample_menu_child.active {
		height: 2rem;
		opacity: 1;
		visibility: visible;
		}
        .hd{
                display: flex;
               background-color: #000000;
         }
        .migi{

text-align:right
}
.janrugotoninaraberuyatu{
  display: flex;      
  gap: 20px;           
  align-items: center;
}
    </style>


<body><br>
<div class="option">
<i class="fa-solid fa-envelope"></i>
</div>


<a href = "/">
<div class="moji">
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
$posts = str_replace("TEST&lt;/h1","</h1",$posts);
$posts = str_replace("TEST&lt;h1","<h1",$posts);
$posts = str_replace("TEST&lt;/a","</a",$posts);
$posts = str_replace("TEST&lt;a","<a",$posts);
$posts = str_replace("TEST&lt;/i","</i",$posts);
$posts = str_replace("TEST&lt;i","<i",$posts);
$posts = str_replace("TEST&lt;/hr","</hr",$posts);
$posts = str_replace("TEST&lt;hr","<hr",$posts);
$posts = str_replace("TEST&lt;/br","</br",$posts);
$posts = str_replace("TEST&lt;br","<br",$posts);
$posts = str_replace("TEST&lt;img","<img",$posts);
$posts = str_replace("TEST&lt;/div","</div",$posts);
$posts = str_replace("TEST&lt;div","<div",$posts);
$posts = str_replace("&quot;","\"",$posts);
$posts = str_replace("&gt;",">",$posts);
?>










<div class="option">
<h3>返信する</h3>

	<form action="" method="post" enctype="multipart/form-data" id="form" class="yoko">
  <label for="name">名前:</label>
  <input type="text" name="name" id="name">
  <br>
  <label for="comment">コメント:</label>
  <textarea name="comment" id="comment"></textarea><br>
    <label for="image">画像:</label>
  <input type="file" name="image" id="image">
		<br>
  <input type="submit" value="返信する" class="HSN">
</form>
</div>








<div id="posts">
  <?= $posts ?>
</div>





<div class="bana" class="waku"><br><a href="https://lunareclipse.onrender.com"><p class="siro">トップに戻る</p></a><a href="https://lunareclipse.onrender.com/admin.html"><p class="siro">管理人のプロフィール</p></a><a href=""><p class="siro">お問い合わせ</p></a><a href=""><p class="siro">LunarEclipseについて</p></a><div>
<a href = "https://x.com/ElegantPencil47" class="sns"><i class="fa-brands fa-square-x-twitter" width="18" height="19"></i></a>
<a href = "" class="sns"><i class="fa-brands fa-square-instagram" width="18" height="19"></i></a>
<a href = "https://www.youtube.com/@%E6%A2%85%E3%81%AE%E3%83%AC%E3%83%A2%E3%83%B3%E6%BC%AC%E3%81%91" class="sns"><i class="fa-brands fa-square-youtube" width="18" height="19"></i></a>
<a href = "https://www.nicovideo.jp/user/139548104?ref=thumb_nicopedia&transit_from=blogparts_user"><img src = "nico_icon.png" width="14" height="15" class="nico"></a>
</div><br><p class="neon_blue" style="text-align:center">©Probably around 2026. lunareclipse.onrender.com Unauthorized reproduction is permitted.</p>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></div>



</body>
</html>
