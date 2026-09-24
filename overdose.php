<!DOCTYPE html>
<html lang="ja">
<head>

  <meta charset="UTF-8">
  <title>ジャンル:オーバードーズ</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<style>
        .bana{
          background-color: #000000;
        }

        .hai{
          color: #A9B2C3;
        }
         
        a {
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

        .option{
          display: flex;
          position: fixed;
          top: 150px;
          right: 50px; 
          z-index: 9999; 
        }
        .user{
display: flex;

}
        .fai{
          color: #ffa500;
          text-shadow:
            0 0 10px #ff0000,
            0 0 20px #ff0000,
            0 0 40px #ff0000,
            0 0 80px #ff0000,
            0 0 100px #ff0000;
        }
        .waku{
          position: relative;
          padding: 20px;
          margin: 30px;
          border:5px solid #fff;
          filter:
            drop-shadow(0 0 2px #37beb0)
            drop-shadow(0 0 8px #37beb0)
            drop-shadow(0 0 12px #37beb0)

        }
        .fais{
          position: relative;
          padding: 20px;
          margin: 30px;
          border:5px solid #ffa500;
          filter:
            drop-shadow(0 0 2px #ff0000)
            drop-shadow(0 0 8px #ff0000)
            drop-shadow(0 0 12px #ff0000)

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

    </style>


<body>

<a href="https://lunareclipse.onrender.com"><h3 class="fai">戻る</h3></a><br><br>
<h1 class="neon_blue">トピック:オーバードーズ</h1>
<h2 class="green_neon">合言葉　合法的に飛ぼう</h2>
<h2 class="neon">⚠当SNSを使用しての薬物の売買等は禁止です</h2>
<?php
$posts = file_get_contents('overdose.txt');
$posts = nl2br(htmlspecialchars($posts, ENT_QUOTES, 'UTF-8'));
?>

<div id="posts" class="neon_blue">
  <?= $posts ?>
</div>
</body>
</html>
