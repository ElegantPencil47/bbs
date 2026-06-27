<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $time = date('Y-m-d H:i:s');
  $post = 'TEST<div style="display: flex; align-items: baseline; gap: 10px;">TEST<a href="' . $comment . '.php"' . '>TEST<h2>' . $comment . 'TEST</h2>TEST</a>TEST<p class="hai">@user_testTEST</p>TEST</div>TEST<hr style="border: 2px solid #A9B2C3;">';

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
  <title>LunarEclipse</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
    <style>
        .hai{
          color: #A9B2C3;
        }
         
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

        .option{
          position: fixed;
          top: 50px;
          right: 50px; 
        }



        .waku{
          position: relative;
          padding: 10px;
          margin: 30px;
        }
        .waku::before {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          border:5px solid #fff;
          filter:
            drop-shadow(0 0 2px #37beb0)
            drop-shadow(0 0 8px #37beb0)
            drop-shadow(0 0 16px #37beb0)
            drop-shadow(0 0 32px #37beb0);
          }

    </style>
<body class="neon_blue"><br>
<div class="option">

<i class="fa-solid fa-envelope"></i>

</div>
<h1 class="neon_blue">LunarEclipse</h1>
<fieldset class="waku">
  <legend><h3 class="neon">今日の月齢</h3></legend>
<div class="tuki">

<div id="moon-age-result" class="green_neon">計算中...</div>
    <div id="moon-phase-message" class="green_neon"></div> 
    <img src="" width="50" id="target">
  

    <script>

        function calculateMoonAge(date = new Date()) {
            const knownNewMoon = new Date('1970-01-07T20:35:00Z');
            const lunarCycle = 29.530588853;
            const daysSinceKnownNewMoon = (date.getTime() - knownNewMoon.getTime()) / (1000 * 60 * 60 * 24);
            let moonAge = daysSinceKnownNewMoon % lunarCycle;
            if (moonAge < 0) {
                moonAge += lunarCycle;
            }
            return moonAge; // toFixed(2)を外し、数値（小数点付き）として返すように変更
        }

        // 結果をウェブサイトに表示し、条件分岐を行う関数
        function displayMoonAge() {
            const currentMoonAgeValue = calculateMoonAge(); // 数値として取得
            const currentMoonAgeFormatted = currentMoonAgeValue.toFixed(2); // 表示用に整形
            let target = document.querySelector("#target");
            
            document.getElementById('moon-age-result').textContent = `${currentMoonAgeFormatted} 日`;

            // *** ここからがご質問の条件分岐です ***
            const messageArea = document.getElementById('moon-phase-message');

            if (currentMoonAgeValue > 0 && currentMoonAgeValue < 1) {
                messageArea.textContent = "新月";
                target.src = "{{ url_for('static', filename='1.png') }}";
            } else if (currentMoonAgeValue > 1 && currentMoonAgeValue < 2) {
                messageArea.textContent = "二日月";
                target.src = "{{ url_for('static', filename='2.png') }}";
            } else if (currentMoonAgeValue > 2 && currentMoonAgeValue < 3) {
                messageArea.textContent = "三日月";
                target.src = "{{ url_for('static', filename='3.png') }}";
            } else if (currentMoonAgeValue > 3 && currentMoonAgeValue < 4) {
                messageArea.textContent = "四日月";
                target.src = "{{ url_for('static', filename='4.png') }}";
            } else if (currentMoonAgeValue > 4 && currentMoonAgeValue < 5) {
                messageArea.textContent = "五日月";
                target.src = "{{ url_for('static', filename='5.png') }}";
            } else if (currentMoonAgeValue > 5 && currentMoonAgeValue < 6) {
                messageArea.textContent = "六日月";
                target.src = "{{ url_for('static', filename='6.png') }}";
            } else if (currentMoonAgeValue > 6 && currentMoonAgeValue < 7) {
                messageArea.textContent = "上弦の月";
                target.src = "{{ url_for('static', filename='7.png') }}";
            } else if (currentMoonAgeValue > 7 && currentMoonAgeValue < 8) {
                messageArea.textContent = "八日月";
                target.src = "{{ url_for('static', filename='8.png') }}";
            } else if (currentMoonAgeValue > 8 && currentMoonAgeValue < 9) {
                messageArea.textContent = "九日月";
                target.src = "{{ url_for('static', filename='9.png') }}";
            } else if (currentMoonAgeValue > 9 && currentMoonAgeValue < 10) {
                messageArea.textContent = "十日夜の月";
                target.src = "{{ url_for('static', filename='10.png') }}";
            } else if (currentMoonAgeValue > 10 && currentMoonAgeValue < 11) {
                messageArea.textContent = "十一日の月";
                target.src = "{{ url_for('static', filename='11.png') }}";
            } else if (currentMoonAgeValue > 11 && currentMoonAgeValue < 12) {
                messageArea.textContent = "十二日の月";
                target.src = "{{ url_for('static', filename='12.png') }}";
            } else if (currentMoonAgeValue > 12 && currentMoonAgeValue < 13) {
                messageArea.textContent = "十三夜";
                target.src = "{{ url_for('static', filename='13.png') }}";
            } else if (currentMoonAgeValue > 13 && currentMoonAgeValue < 14) {
                messageArea.textContent = "小望月";
                target.src = "{{ url_for('static', filename='14.png') }}";
            } else if (currentMoonAgeValue > 14 && currentMoonAgeValue < 15) {
                messageArea.textContent = "満月";
                target.src = "{{ url_for('static', filename='15.png') }}";
            } else if (currentMoonAgeValue > 15 && currentMoonAgeValue < 16) {
                messageArea.textContent = "十六夜";
                target.src = "{{ url_for('static', filename='16.png') }}";
            } else if (currentMoonAgeValue > 16 && currentMoonAgeValue < 17) {
                messageArea.textContent = "立待夜";
                target.src = "{{ url_for('static', filename='17.png') }}";
            } else if (currentMoonAgeValue > 17 && currentMoonAgeValue < 18) {
                messageArea.textContent = "居待月";
                target.src = "{{ url_for('static', filename='18.png') }}";
            } else if (currentMoonAgeValue > 18 && currentMoonAgeValue < 19) {
                messageArea.textContent = "臥待月";
                target.src = "{{ url_for('static', filename='19.png') }}";
            } else if (currentMoonAgeValue > 19 && currentMoonAgeValue < 20) {
                messageArea.textContent = "更待月";
                target.src = "{{ url_for('static', filename='20.png') }}";
            } else if (currentMoonAgeValue > 20 && currentMoonAgeValue < 21) {
                messageArea.textContent = "二十一夜";
                target.src = "{{ url_for('static', filename='21.png') }}";
            } else if (currentMoonAgeValue > 21 && currentMoonAgeValue < 22) {
                messageArea.textContent = "二十二夜";
                target.src = "{{ url_for('static', filename='22.png') }}";
            } else if (currentMoonAgeValue > 22 && currentMoonAgeValue < 23) {
                messageArea.textContent = "下弦の月";
                target.src = "{{ url_for('static', filename='23.png') }}";
            } else if (currentMoonAgeValue > 23 && currentMoonAgeValue < 24) {
                messageArea.textContent = "二十四夜";
                target.src = "{{ url_for('static', filename='24.png') }}";
            } else if (currentMoonAgeValue > 24 && currentMoonAgeValue < 25) {
                messageArea.textContent = "二十五夜";
                target.src = "{{ url_for('static', filename='25.png') }}";
            } else if (currentMoonAgeValue > 25 && currentMoonAgeValue < 26) {
                messageArea.textContent = "二十六夜";
                target.src = "{{ url_for('static', filename='26.png') }}";
            } else if (currentMoonAgeValue > 26 && currentMoonAgeValue < 27) {
                messageArea.textContent = "二十七夜";
                target.src = "{{ url_for('static', filename='27.png') }}";
            } else if (currentMoonAgeValue > 27 && currentMoonAgeValue < 28) {
                messageArea.textContent = "有明の月";
                target.src = "{{ url_for('static', filename='28.png') }}";
            } else if (currentMoonAgeValue > 28 && currentMoonAgeValue < 29) {
                messageArea.textContent = "二十九日月";
                target.src = "{{ url_for('static', filename='29.png') }}";
            } else if (currentMoonAgeValue > 29 && currentMoonAgeValue < 30) {
                messageArea.textContent = "三十日月";
                target.src = "{{ url_for('static', filename='30.png') }}";
            }

 
     
        }

        // ページ読み込み時に月齢を表示
        displayMoonAge();
    </script>
    
</div>



</fieldset>



<h3 class="neon">投稿する</h3>
<form action="" method="post">
  <label for="name" class="neon">名前:</label>
  <input type="text" name="name" id="name">
  <br>
  <label for="comment" class="neon_blue">コメント:</label>
  <textarea name="comment" id="comment"></textarea>
  <br>
  <input type="submit" value="投稿">
</form>
<h2 class="neon_blue">投稿一覧</h2>
<?php
$posts = file_get_contents('posts.txt');
$posts = nl2br(htmlspecialchars($posts, ENT_QUOTES, 'UTF-8'));
$posts = str_replace("TEST&lt;a","<a",$posts);
$posts = str_replace("TEST&lt;/a","</a",$posts);
$posts = str_replace("TEST&lt;h2","<h2",$posts);
$posts = str_replace("TEST&lt;hr","<hr",$posts);
$posts = str_replace("TEST&lt;/h2","</h2",$posts);
$posts = str_replace("TEST&lt;/p","</p",$posts);
$posts = str_replace("TEST&lt;p","<p",$posts);
$posts = str_replace("TEST&lt;/div","</div",$posts);
$posts = str_replace("TEST&lt;div","<div",$posts);
$posts = str_replace("&gt;",">",$posts);
$posts = str_replace("&quot;","\"",$posts);
?>

<div id="posts" class="green_neon">
  <?= $posts ?>
</div>
</body>
</html>