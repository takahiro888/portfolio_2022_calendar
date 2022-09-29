<?php
//タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');

// 前月・次月リンクが押された場合は、GETパラメーターから年月を取得
if(isset($_GET['ym'])){
  $ym = $_GET['ym'];
}else{
  //今月の年月を表示
  $ym = date('Y-m');
}

//タイムスタンプを作成し、フォーマットをチェックする
$timestamp = strtotime($ym . '-01');
if ($timestamp === false){
  $ym = date('Y-m');
  $timestamp = strtotime($ym . '-01');
}

//今日の日付　フォーマット　例)2021-06-03
$today = date('Y-m-j');

//カレンダーのタイトルを作成　例)2021年6月
$html_title = date('Y年n月' , $timestamp);

//前月・次月の年月を取得
//方法1:mktimeを使う　mktime(hour,minute,second,month,day,year)
$prev = date('Y-m',mktime(0,0,0,date('m',$timestamp)-1, 1, date('Y',$timestamp)));
$next = date('Y-m',mktime(0,0,0,date('m',$timestamp)+1, 1, date('Y',$timestamp)));

//方法2:strtotimeを使う
// $prev = date('Y-m', strtotime('-1 month',$timestamp));
// $next = date('Y-m', strtotime('+1 month',$timestamp));

//該当月の日数を取得
$day_count = date('t',$timestamp);

// 1日が何曜日か　0:日　1:月　2:火　... 6:土
// 方法1:mktimeを使う
$youbi = date('w',mktime(0,0,0,date('m',$timestamp),1,date('Y',$timestamp)));
//　方法2
// $youbi = date('w',$timestamp);

//カレンダー作成の準備
$weeks = [];
$week = '';

//第1週:空のセルを追加
//例）1日が火曜日だった場合、日・月曜日の２つ分の空セルを追加する
$week .= str_repeat('<td></td>',$youbi);

for($day = 1; $day <= $day_count; $day++, $youbi++){
  //2021-06-03
  $date = $ym . '-' . $day;

  if ($today == $date){
    //今日の日付の場合は、class="today"をつける
    $week .= '<td class="today">' . $day;
  }else{
    $week .= '<td>' . $day;
  }
  $week .= '</td>';

  //週終わり、または、月終わりの場合
  if ($youbi % 7 == 6 || $day == $day_count){
    if($day === $day_count){
      //月の最終日の場合、空セルを追加
      //例)最終日が水曜日の場合、木・金・土曜日の空セルを追加
      $week .= str_repeat('<td></td>' , 6 - $youbi % 7);
    }

    //weeks配列にtrと$weekを追加する
    $weeks[] = '<tr>' .$week .'</tr>';

    //weekをリセット
    $week ='';
  }
  
}
?>





<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
  <style>
  .container {
    font-family: 'Noto Sans JP', sans-serif;
  }

  a {
    text-decoration: none;
  }

  th {
    height: 30px;
    text-align: center;
  }

  td {
    height: 100px;
  }

  .today {
    background-color: orange !important;
  }

  th:nth-of-type(1),
  td:nth-of-type(1) {
    color: red;
  }

  th:nth-of-type(7),
  td:nth-of-type(7) {
    color: blue;
  }
  </style>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHPカレンダー</title>
</head>

<body>
  <div class="container">
    <h3 class="mb-5"><a href="?ym=<?php echo $prev; ?>">&lt;</a><?php echo $html_title; ?><a href="?ym=<?php echo $next; ?>">&gt;</a></h3>
    <table class="table table-bordered">
      <tr>
        <th>日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th>土</th>
      </tr>
      <?php
      foreach($weeks as $week){
        echo $week;
      }
      ?>
    </table>
  </div>
</body>

</html>