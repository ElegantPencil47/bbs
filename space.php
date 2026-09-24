<!DOCTYPE html>
<html lang="ja">
<head>

  <meta charset="UTF-8">
  <title>ジャンル:宇宙・天文</title>
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
          margin: 4px;
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
          filter:
            drop-shadow(-2px 2px 2px #ff0000)
            drop-shadow(-4px 4px 8px #ff0000)
            drop-shadow(-8px 8px 16px #ff0000)
            drop-shadow(-12px 12px 32px #ff0000);
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
        i{

font-size:39px; 

}
        img{
  position: relative;
  top: 3px;
border-radius: 5px;
  width: 35px; 
  height: 35px;
}
           
        
    </style>


<body>

<a href="https://lunareclipse.onrender.com"><h3 class="fai">戻る</h3></a><br><br>
<h1 class="neon_blue">トピック:宇宙・天文</h1>
<h2 class="green_neon">合言葉　138億年の浪漫、暗黒と幻想が織りなす世界</h2>
<?php
$posts = file_get_contents('space.txt');
$posts = nl2br(htmlspecialchars($posts, ENT_QUOTES, 'UTF-8'));
?>

<div id="posts" class="neon">
  <?= $posts ?>
</div>
</body>
</html>
