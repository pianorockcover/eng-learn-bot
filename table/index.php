<?php
  include '../vendor/autoload.php';
  include '../config.php';

  $dbConfig = [
        'driver'    => 'mysql', 
        'host'      => $config['db']['host'],
        'database'  => $config['db']['name'],
        'username'  => $config['db']['user'],
        'password'  => $config['db']['password'],
        'charset'   => 'utf8',
    ];

  new \Pixie\Connection('mysql', $dbConfig, 'QB');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Footbal Table</title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto:400,700" rel="stylesheet">
  
     <!-- LESS -->
     <link rel="stylesheet/less" type="text/css" href="less/style.less?2" /> 
     <!-- LESS Compiler -->
     <script src="bower_components/less/dist/less.min.js" type="text/javascript"></script>
     <!-- CSS -->
     <!-- <link href="css/style.css" rel="stylesheet"> -->

  </head>
  <body>
    <div class="tables-container">
      <div class="title">Форма команд и очные встречи</div>
      <?php
        $event = \QB::table('football_bot_event')
                      ->where('event_id', $_GET['event_id'])
                      ->get()[0];

        $infoTables = json_decode($event->info_tables);
        $h2hTable = json_decode($event->head_to_head_table);
        $balls = json_decode($event->balls_statistics_table);
        $minutes = json_decode($event->minutes_table);
        $injured = json_decode($event->injured_table);
      ?>
      <div class="gradient-header">Последние игры: <?= $event->team_a ?></div>
      <table class="table-1" cellpadding="0" cellspacing="0">
        <?php foreach ($infoTables->team_a as $match): ?>
          <tr>
            <td><?=$match->date?></td>
            <!-- <td class="center flag-td">
              <img src="img/flag.png" alt="" class="flag">
            ??</td> -->
            <td class="abbr-td"><?=$match->competition?></td>
            <td class="
              <?php if ($match->team_1 == $event->team_a) echo 'grey-bg' ?>
              <?php if ($match->team_1 == $event->team_a && $match->result == 'win') echo 'bold-text' ?>
              "><?=$match->team_1?></td>
            <td class="
              <?php if ($match->team_2 == $event->team_a) echo 'grey-bg' ?>
              <?php if ($match->team_2 == $event->team_a && $match->result == 'win') echo 'bold-text' ?>
              "><?=$match->team_2?></td>
            <td class="scope-td"><?=$match->scope?></td>
            <td class="result-td">
              <div class="<?=$match->result?>">
                <?php if($match->result == 'win') {
                  echo "В";
                } else if($match->result == 'loss') {
                  echo "П";
                } else if($match->result == 'draw') {
                  echo "Н";
                }?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>

      <div class="gradient-header">Последние игры: <?= $event->team_b ?></div>
      <table class="table-1" cellpadding="0" cellspacing="0">
        <?php foreach ($infoTables->team_b as $match): ?>
          <tr>
            <td><?=$match->date?></td>
            <!-- <td class="center flag-td">
              <img src="img/flag.png" alt="" class="flag">
            ??</td> -->
            <td class="abbr-td"><?=$match->competition?></td>
            <td class="
              <?php if ($match->team_1 == $event->team_b) echo 'grey-bg' ?>
              <?php if ($match->team_1 == $event->team_b && $match->result == 'win') echo 'bold-text' ?>
              "><?=$match->team_1?></td>
            <td class="
              <?php if ($match->team_2 == $event->team_b) echo 'grey-bg' ?>
              <?php if ($match->team_2 == $event->team_b && $match->result == 'win') echo 'bold-text' ?>
              "><?=$match->team_2?></td>
            <td class="scope-td"><?=$match->scope?></td>
            <td class="result-td">
              <div class="<?=$match->result?>">
                <?php if($match->result == 'win') {
                  echo "В";
                } else if($match->result == 'loss') {
                  echo "П";
                } else if($match->result == 'draw') {
                  echo "Н";
                }?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>

      <?php if ($h2hTable): ?>
        <div class="gradient-header">Очные встречи: <?=$event->team_a?> – <?=$event->team_b?></div>
        <table class="table-1" cellpadding="0" cellspacing="0">
          <?php foreach ($h2hTable as $match): ?>
            <tr>
              <td><?=$match->date?></td>
              <!-- <td class="center flag-td"><img src="img/flag.png" alt="" class="flag"></td> -->
              <td class="abbr-td"><?=$match->competition?></td>
              <td class="
                <?php if ($match->winner == 'team_1') echo 'bold-text grey-bg' ?>
              "><?=$match->team_1?></td>
              <td class="
                <?php if ($match->winner == 'team_2') echo 'bold-text grey-bg' ?>
              "><?=$match->team_2?></td>
              <td class="scope-td"><?=$match->scope?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php endif; ?>

      <?php if ($balls): ?>
        <div class="title">Статистика по голам</div>
        <table class="table-1 common-statistics"  cellpadding="0" cellspacing="0">
          <thead>
            <tr>
              <th class="row-title">Общая статистика</th>
              <th colspan="3"><?= $event->team_a ?></th>
              <th colspan="3"><?= $event->team_b ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td class="cross"><b>Итого</b></td>
              <td><b>Дома</b></td>
              <td><b>В гостях</b></td>
              <td class="cross"><b>Итого</b></td>
              <td><b>Дома</b></td>
              <td><b>В гостях</b></td>
            </tr>
            <tr>
              <td>Забитые мячи</td>
              <td class="important-col"><b><?=$balls->balls->team_a->total?></b></td>
              <td><?=$balls->balls->team_a->home?></td>
              <td><?=$balls->balls->team_a->away?></td>
              <td class="important-col"><b><?=$balls->balls->team_b->total?></b></td>
              <td><?=$balls->balls->team_b->home?></td>
              <td><?=$balls->balls->team_b->away?></td>
            </tr>
            <tr>
              <td>Пропущенные мячи</td>
              <td class="important-col cross"><b><?=$balls->lostBalls->team_a->total?></b></td>
              <td><?=$balls->lostBalls->team_a->home?></td>
              <td><?=$balls->lostBalls->team_a->away?></td>
              <td class="important-col cross"><b><?=$balls->lostBalls->team_b->total?></b></td>
              <td><?=$balls->lostBalls->team_b->home?></td>
              <td><?=$balls->lostBalls->team_b->away?></td>
            </tr>
            <tr>
              <td>Ср. кол-во забитых мячей за матч</td>
              <td class="important-col"><b><?=$balls->middleBalls->team_a->total?></b></td>
              <td><?=$balls->middleBalls->team_a->home?></td>
              <td><?=$balls->middleBalls->team_a->away?></td>
              <td class="important-col"><b><?=$balls->middleBalls->team_b->total?></b></td>
              <td><?=$balls->middleBalls->team_b->home?></td>
              <td><?=$balls->middleBalls->team_b->away?></td>
            </tr>
            <tr>
              <td>Ср. кол-во пропущенных мячей за</td>
              <td class="important-col cross"><b><?=$balls->middleLostBalls->team_a->total?></b></td>
              <td><?=$balls->middleLostBalls->team_a->home?></td>
              <td><?=$balls->middleLostBalls->team_a->away?></td>
              <td class="important-col cross"><b><?=$balls->middleLostBalls->team_b->total?></b></td>
              <td><?=$balls->middleLostBalls->team_b->home?></td>
              <td><?=$balls->middleLostBalls->team_b->away?></td>
            </tr>
          </tbody>
        </table>
        
      <?php endif; ?>

      
      <?php if ($injured): ?>    
         <div class="title">Потери и травмированные</div>

          <!-- <div class="gradient-header center">Не сыграют</div> -->
          <table class="table-1 probability injured" cellpadding="0" cellspacing="0">
            <tr>
              <th><?= $event->team_a ?></th>
              <th><?= $event->team_b ?></th>
            </tr>
            <?php foreach($injured as $player): ?>
              <tr>
                <td><div class="name"><?=$player->team_a?></div></td>
                <td><div class="name"><?=$player->team_b?></div></td>
              </tr>
            <?php endforeach; ?>
          </table>
      <?php endif; ?>
      
      <?php if ($minutes): ?>
        
        <div class="title">Время забитых мячей</div>
        <table class="table-1 common-statistics balls" cellpadding="0" cellspacing="0">
          <thead>
            <th width="209">Голы и минуты</th>
            <th width="367"><?= $event->team_a ?></th>
            <th width="367"><?= $event->team_b ?></th>
          </thead>
          <tbody>
            <?php foreach ($minutes as $minute): ?>
              <tr>
                <td><b><?=$minute->legend?></b></td>
                <td class="to-left"><div data-fill="<?=$minute->team_a?>" class="bar"><?=$minute->team_a?></div></td>
                <td class="to-left"><div data-fill="<?=$minute->team_b?>" class="bar"><?=$minute->team_b?></div></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table> 
      <?php endif; ?>
    </div>

    <script src="bower_components/jquery/dist/jquery.min.js"></script>  
    <script src="js/script.js?5"></script>
  </body>
</html>