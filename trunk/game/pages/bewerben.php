<?php

// Подача заявки в альянс.

if (CheckSession ( $_GET['session'] ) == FALSE) die ();
if ( key_exists ('cp', $_GET)) SelectPlanet ($GlobalUser['player_id'], $_GET['cp']);
$now = time();
UpdateQueue ( $now );
$aktplanet = GetPlanet ( $GlobalUser['aktplanet'] );
ProdResources ( $GlobalUser['aktplanet'], $aktplanet['lastpeek'], $now );
UpdatePlanetActivity ( $aktplanet['planet_id'] );
UpdateLastClick ( $GlobalUser['player_id'] );
$session = $_GET['session'];

PageHeader ("bewerben");

if ( ! $GlobalUser['validated'] ) Error ( "Эта функция возможна только после активации учетной записи игрока." );

$ally_id = $_GET['allyid'];
$ally = LoadAlly ($ally_id);

// Загрузить образец заявки.
$template = "";
if ( $_POST['weiter'] === "Образец" || $ally['insertapp'])
{
    $template = $ally['apptext'];
    if ($template === "") $template = "Управление альянса не предоставило образца";
}

// Отправить заявление
if ( $_POST['weiter'] === "Отправить" )
{
    AddApplication ( $ally['ally_id'], $GlobalUser['player_id'], $_POST['text'] );

?>
<!-- CONTENT AREA -->
<div id='content'>
<center>
<h1>Регистрироваться</h1>
<table width=519>
<form action="index.php?page=allianzen&session=<?=$session;?>" method=POST>
<tr><th colspan=2>Ваше заявление сохранено. Вы получите ответ в случае принятия или отклонения.</th></tr>
<tr><th colspan=2><input type=submit value="OK"></th></tr>
</table></form></center><br><br><br><br>
</center>
</div>
<!-- END CONTENT AREA -->
<?php
    PageFooter ();
    ob_end_flush ();
    die();
}

?>

<!-- CONTENT AREA -->
<div id='content'>
<center>
<h1>Регистрироваться</h1>
<table width=519>
<form action="index.php?page=bewerben&session=<?=$session;?>&allyid=<?=$ally_id;?>" method=POST>
<tr><td class=c colspan=2>Заявка в альянс [<?=$ally['tag'];?>] написать</td></tr>
<tr><th>Сообщение (<span id="cntChars">0</span> / 6000 символов)</th><th><textarea name="text" cols=40 rows=10 onkeyup="javascript:cntchar(6000)"><?=$template;?></textarea></th></tr>
<tr><th>Маленькая помощь</th><th><input type=submit name="weiter" value="Образец"></th></tr>
<tr><th colspan=2><input type=submit name="weiter" value="Отправить"></th></tr>
</table></form></center><br><br><br><br>
</center>
</div>
<!-- END CONTENT AREA -->

<?php
PageFooter ();
ob_end_flush ();
?>