<?php

// ВЕРСИЯ 2.

// Установочный файл.
// Создает все необходимые таблицы в базе данных, а также файл конфигурации config.php, для доступа к базе.
// Не работет, если файл config.php создан.

$InstallError = "<font color=gold>Используйте подсказки при наведении мышкой на выбранные параметры</font>";

require_once "db.php";

function hostname () {
    $host = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["SCRIPT_NAME"];
    $pos = strrpos ( $host, "/game/install.php" );
    return substr ( $host, 0, $pos+1 );
}

ob_start ();

// Проверить настройки вселенной.
function CheckParameters ()
{
    global $InstallError;

    return TRUE;
}

// Проверить, если файл конфигурации уже создан - редирект на главную страницу.
if (file_exists ("config.php"))
{
    echo "<html><head><meta http-equiv='refresh' content='0;url=index.php' /></head><body></body></html>";
    ob_end_flush ();
    exit ();
}

function gen_trivial_password ($len = 8)
{
    $r = '';
    for($i=0; $i<$len; $i++)
        $r .= chr(rand(0, 25) + ord('a'));
    return $r;
}

// Структура таблиц.
// -------------------------------------------------------------------------------------------------------------------------

$tab_uni = array (        // Вселенная
    'num'=>'INT PRIMARY KEY','speed'=>'FLOAT','fspeed'=>'FLOAT','galaxies'=>'INT','systems'=>'INT','maxusers'=>'INT','acs'=>'INT','fid'=>'INT','did'=>'INT','rapid'=>'INT','moons'=>'INT','defrepair'=>'INT','defrepair_delta'=>'INT','usercount'=>'INT','freeze'=>'INT',
    'news1'=>'TEXT', 'news2'=>'TEXT', 'news_until'=>'INT UNSIGNED', 'startdate'=>'INT UNSIGNED', 'battle_engine'=>'TEXT', 'special'=>'INT'
);

$tab_users = array (    // Пользователи
    'player_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'regdate'=>'INT UNSIGNED', 'ally_id'=>'INT', 'joindate'=>'INT UNSIGNED', 'allyrank'=>'INT', 'session'=>'CHAR(12)', 'private_session'=>'CHAR(32)', 'name'=>'CHAR(20)', 'oname'=>'CHAR(20)', 'name_changed'=>'INT', 'name_until'=>'INT UNSIGNED', 'password'=>'CHAR(32)', 'temp_pass'=>'CHAR(32)', 'pemail'=>'CHAR(50)', 'email'=>'CHAR(50)',
    'email_changed'=>'INT', 'email_until'=>'INT UNSIGNED', 'disable'=>'INT', 'disable_until'=>'INT UNSIGNED', 'vacation'=>'INT', 'vacation_until'=>'INT UNSIGNED', 'banned'=>'INT', 'banned_until'=>'INT UNSIGNED', 'noattack'=>'INT', 'noattack_until'=>'INT UNSIGNED',
    'lastlogin'=>'INT UNSIGNED', 'lastclick'=>'INT UNSIGNED', 'ip_addr'=>'CHAR(15)', 'validated'=>'INT', 'validatemd'=>'CHAR(32)', 'hplanetid'=>'INT', 'admin'=>'INT', 'sortby'=>'INT', 'sortorder'=>'INT',
    'skin'=>'CHAR(80)', 'useskin'=>'INT', 'deact_ip'=>'INT', 'maxspy'=>'INT', 'maxfleetmsg'=>'INT', 'lang'=>'CHAR(4)', 'aktplanet'=>'INT',
    'dm'=>'INT UNSIGNED', 'dmfree'=>'INT UNSIGNED', 'sniff'=>'INT', 'debug'=>'INT', 'redesign'=>'INT', 'trader'=>'INT', 'rate_m'=>'DOUBLE', 'rate_k'=>'DOUBLE', 'rate_d'=>'DOUBLE',
    'score1'=>'BIGINT', 'score2'=>'INT', 'score3'=>'INT', 'place1'=>'INT', 'place2'=>'INT', 'place3'=>'INT',
    'oldscore1'=>'BIGINT', 'oldscore2'=>'INT', 'oldscore3'=>'INT', 'oldplace1'=>'INT', 'oldplace2'=>'INT', 'oldplace3'=>'INT', 'scoredate'=>'INT UNSIGNED',
    'r106'=>'INT', 'r108'=>'INT', 'r109'=>'INT', 'r110'=>'INT', 'r111'=>'INT', 'r113'=>'INT', 'r114'=>'INT', 'r115'=>'INT', 'r117'=>'INT', 'r118'=>'INT', 'r120'=>'INT', 'r121'=>'INT', 'r122'=>'INT', 'r123'=>'INT', 'r124'=>'INT', 'r199'=>'INT'
);

$tab_planets = array (    // Планеты
    'planet_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'name'=>'CHAR(20)', 'type'=>'INT', 'g'=>'INT', 's'=>'INT', 'p'=>'INT', 'owner_id'=>'INT', 'diameter'=>'INT', 'temp'=>'INT', 'fields'=>'INT', 'maxfields'=>'INT', 'date'=>'INT UNSIGNED', 
    'b1'=>'INT', 'b2'=>'INT', 'b3'=>'INT', 'b4'=>'INT', 'b12'=>'INT', 'b14'=>'INT', 'b15'=>'INT', 'b21'=>'INT', 'b22'=>'INT', 'b23'=>'INT', 'b24'=>'INT', 'b31'=>'INT', 'b33'=>'INT', 'b34'=>'INT', 'b41'=>'INT', 'b42'=>'INT', 'b43'=>'INT', 'b44'=>'INT',
    'd401'=>'INT', 'd402'=>'INT', 'd403'=>'INT', 'd404'=>'INT', 'd405'=>'INT', 'd406'=>'INT', 'd407'=>'INT', 'd408'=>'INT', 'd502'=>'INT', 'd503'=>'INT',
    'f202'=>'INT', 'f203'=>'INT', 'f204'=>'INT', 'f205'=>'INT', 'f206'=>'INT', 'f207'=>'INT', 'f208'=>'INT', 'f209'=>'INT', 'f210'=>'INT', 'f211'=>'INT', 'f212'=>'INT', 'f213'=>'INT', 'f214'=>'INT', 'f215'=>'INT',
    'm'=>'DOUBLE', 'k'=>'DOUBLE', 'd'=>'DOUBLE', 'mprod'=>'DOUBLE', 'kprod'=>'DOUBLE', 'dprod'=>'DOUBLE', 'sprod'=>'DOUBLE', 'fprod'=>'DOUBLE', 'ssprod'=>'DOUBLE', 'lastpeek'=>'INT UNSIGNED', 'lastakt'=>'INT UNSIGNED', 'gate_until'=>'INT UNSIGNED', 'remove'=>'INT UNSIGNED'
);

$tab_ally = array (    // Альянсы
    'ally_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'tag'=>'TEXT', 'name'=>'TEXT', 'owner_id'=>'INT', 'homepage'=>'TEXT', 'imglogo'=>'TEXT', 'open'=>'INT', 'insertapp'=>'INT', 'exttext'=>'TEXT', 'inttext'=>'TEXT', 'apptext'=>'TEXT', 'nextrank'=>'INT', 'old_tag'=>'TEXT', 'old_name'=>'TEXT', 'tag_until'=>'INT UNSIGNED', 'name_until'=>'INT UNSIGNED',
    'score1'=>'BIGINT UNSIGNED', 'score2'=>'INT UNSIGNED', 'score3'=>'INT UNSIGNED', 'place1'=>'INT', 'place2'=>'INT', 'place3'=>'INT',
    'oldscore1'=>'BIGINT UNSIGNED', 'oldscore2'=>'INT UNSIGNED', 'oldscore3'=>'INT UNSIGNED', 'oldplace1'=>'INT', 'oldplace2'=>'INT', 'oldplace3'=>'INT', 'scoredate'=>'INT UNSIGNED'
);

$tab_allyranks = array (    // Ранги в альянсе
    'rank_id'=>'INT', 'ally_id'=>'INT', 'name'=>'TEXT', 'rights'=>'INT'
);

$tab_allyapps = array (    // Заявки в альянс
    'app_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'ally_id'=>'INT', 'player_id'=>'INT', 'text'=>'TEXT', 'date'=>'INT UNSIGNED'
);

$tab_buddy = array (    // Друзья
    'buddy_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'request_from'=>'INT', 'request_to'=>'INT', 'text'=>'TEXT', 'accepted'=>'INT'
);

$tab_messages = array (    // Сообщения
    'msg_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'pm'=>'INT', 'msgfrom'=>'TEXT', 'subj'=>'TEXT', 'text'=>'TEXT', 'shown'=>'INT', 'date'=>'INT UNSIGNED'
);

$tab_notes = array (    // Заметки
    'note_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'subj'=>'TEXT', 'text'=>'TEXT', 'textsize'=>'INT', 'prio'=>'INT', 'date'=>'INT UNSIGNED'
);

$tab_errors = array (    // Ошибки
    'error_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'ip'=>'TEXT', 'agent'=>'TEXT', 'url'=>'TEXT', 'text'=>'TEXT', 'date'=>'INT UNSIGNED'
);

$tab_debug = array (    // Отладочные сообщения
    'error_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'ip'=>'TEXT', 'agent'=>'TEXT', 'url'=>'TEXT', 'text'=>'TEXT', 'date'=>'INT UNSIGNED'
);

$tab_browse = array (    // История переходов
    'log_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'url'=>'TEXT', 'method'=>'TEXT', 'getdata'=>'TEXT', 'postdata'=>'TEXT', 'date'=>'INT UNSIGNED'
);

$tab_queue = array (    // Очередь событий
    'task_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'type'=>'CHAR(20)', 'sub_id'=>'INT', 'obj_id'=>'INT', 'level'=>'INT', 'start'=>'INT UNSIGNED', 'end'=>'INT UNSIGNED', 'prio'=>'INT'
);

$tab_fleet = array (    // Флот
    'fleet_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'union_id'=>'INT', 'm'=>'DOUBLE', 'k'=>'DOUBLE', 'd'=>'DOUBLE', 'fuel'=>'INT', 'mission'=>'INT', 'start_planet'=>'INT', 'target_planet'=>'INT', 'flight_time'=>'INT', 'deploy_time'=>'INT',
    'ipm_amount'=>'INT', 'ipm_target'=>'INT', 'ship202'=>'INT', 'ship203'=>'INT', 'ship204'=>'INT', 'ship205'=>'INT', 'ship206'=>'INT', 'ship207'=>'INT', 'ship208'=>'INT', 'ship209'=>'INT', 'ship210'=>'INT', 'ship211'=>'INT', 'ship212'=>'INT', 'ship213'=>'INT', 'ship214'=>'INT', 'ship215'=>'INT'
);

$tab_union = array (    // САБы
    'union_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'fleet_id'=>'INT', 'target_player'=>'INT', 'name'=>'CHAR(20)', 'players'=>'TEXT'
);

$tab_battledata = array (    // Данные для боевого движка (deprecated)
    'battle_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'source'=>'TEXT', 'result'=>'TEXT'
);

$tab_fleetlogs = array (    // Логи полётов
    'log_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'target_id'=>'INT', 'union_id'=>'INT', 'pm'=>'DOUBLE', 'pk'=>'DOUBLE', 'pd'=>'DOUBLE', 'm'=>'DOUBLE', 'k'=>'DOUBLE', 'd'=>'DOUBLE', 'fuel'=>'INT', 'mission'=>'INT', 'flight_time'=>'INT', 'deploy_time'=>'INT', 'start'=>'INT UNSIGNED', 'end'=>'INT UNSIGNED',
    'origin_g'=>'INT', 'origin_s'=>'INT', 'origin_p'=>'INT', 'origin_type'=>'INT', 'target_g'=>'INT', 'target_s'=>'INT', 'target_p'=>'INT', 'target_type'=>'INT', 
    'ipm_amount'=>'INT', 'ipm_target'=>'INT', 'ship202'=>'INT', 'ship203'=>'INT', 'ship204'=>'INT', 'ship205'=>'INT', 'ship206'=>'INT', 'ship207'=>'INT', 'ship208'=>'INT', 'ship209'=>'INT', 'ship210'=>'INT', 'ship211'=>'INT', 'ship212'=>'INT', 'ship213'=>'INT', 'ship214'=>'INT', 'ship215'=>'INT'
);

$tab_iplogs = array (    // Логи IP
    'log_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'ip'=>'CHAR(16)', 'user_id'=>'INT', 'reg'=>'INT', 'date'=>'INT UNSIGNED'
);

$tab_pranger = array (    // Столб позора
    'ban_id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'admin_name'=>'CHAR(20)', 'user_name'=>'CHAR(20)', 'admin_id'=>'INT', 'user_id'=>'INT', 'ban_when'=>'INT UNSIGNED', 'ban_until'=>'INT UNSIGNED', 'reason'=>'TEXT'
);

$tab_exptab = array (    // Настройки экспедиции
    'chance_success'=>'INT', 'depleted_min'=>'INT', 'depleted_med'=>'INT', 'depleted_max'=>'INT', 'chance_depleted_min'=>'INT', 'chance_depleted_med'=>'INT', 'chance_depleted_max'=>'INT',
    'chance_alien'=>'INT', 'chance_pirates'=>'INT', 'chance_dm'=>'INT', 'chance_lost'=>'INT', 'chance_delay'=>'INT', 'chance_accel'=>'INT', 'chance_res'=>'INT', 'chance_fleet'=>'INT'
);

$tab_template = array (    // Стандартные флоты
    'id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'name'=>'CHAR(30)', 'date'=>'INT UNSIGNED',
    'ship202'=>'INT', 'ship203'=>'INT', 'ship204'=>'INT', 'ship205'=>'INT', 'ship206'=>'INT', 'ship207'=>'INT', 'ship208'=>'INT', 'ship209'=>'INT', 'ship210'=>'INT', 'ship211'=>'INT', 'ship212'=>'INT', 'ship213'=>'INT', 'ship214'=>'INT', 'ship215'=>'INT',
);

$tab_botvars = array (    // Переменные бота
    'id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'var'=>'TEXT', 'value'=>'TEXT'
);

$tab_userlogs = array (    // Логи действий пользователей (и операторов). Срабатывают когда юзер что-то нажимает
    'id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'owner_id'=>'INT', 'date'=>'INT UNSIGNED', 'type'=>'TEXT', 'text'=>'TEXT',
);

$tabs = array (
    'uni' => &$tab_uni,
    'users' => &$tab_users,
    'planets' => &$tab_planets,
    'ally' => &$tab_ally,
    'allyranks' => &$tab_allyranks,
    'allyapps' => &$tab_allyapps,
    'buddy' => &$tab_buddy,
    'messages' => &$tab_messages,
    'notes' => &$tab_notes,
    'errors' => &$tab_errors,
    'debug' => &$tab_debug,
    'browse' => &$tab_browse,
    'queue' => &$tab_queue,
    'fleet' => &$tab_fleet,
    'union' => &$tab_union,
    'battledata' => &$tab_battledata,
    'fleetlogs' => &$tab_fleetlogs,
    'iplogs' => &$tab_iplogs,
    'pranger' => &$tab_pranger,
    'exptab' => &$tab_exptab,
    'template' => &$tab_template,
    'botvars' => &$tab_botvars,
    'userlogs' => &$tab_userlogs,
);

// -------------------------------------------------------------------------------------------------------------------------

// Сохранить настройки.
if ( key_exists("install", $_POST) && CheckParameters() )
{
    $now = time();

    //print_r ($_POST);

    // Удалить все таблицы и создать новые пустые.
    dbconnect ($_POST["db_host"], $_POST["db_user"], $_POST["db_pass"], $_POST["db_name"]);
    dbquery("SET NAMES 'utf8';");
    dbquery("SET CHARACTER SET 'utf8';");
    dbquery("SET SESSION collation_connection = 'utf8_general_ci';");

    foreach ( $tabs as $tabname => $tab )
    {
        $opt = " (";
        $first = true;
        foreach ( $tab as $row => $type ) 
        {
            if ( !$first ) $opt .= ", ";
            if ( $first ) $first = false;
            $opt .= $row . " " . $type;
        }
        $opt .= ")";

        $query = 'DROP TABLE IF EXISTS '.$_POST["db_prefix"].$tabname;
        dbquery ($query, TRUE);
        $query = 'CREATE TABLE '.$_POST["db_prefix"].$tabname.$opt." CHARACTER SET utf8 COLLATE utf8_general_ci";
        dbquery ($query, TRUE);
    }

    // Создать вселенную.
    $query = "INSERT INTO ".$_POST["db_prefix"]."uni SET ";
    $query .= "num = '".$_POST["uni_num"]."', ";
    $query .= "speed = '".$_POST["uni_speed"]."', ";
    $query .= "fspeed = '".$_POST["uni_fspeed"]."', ";
    $query .= "galaxies = '".$_POST["uni_galaxies"]."', ";
    $query .= "systems = '".$_POST["uni_systems"]."', ";
    $query .= "maxusers = '".$_POST["uni_maxusers"]."', ";
    $query .= "acs = '".$_POST["uni_acs"]."', ";
    $query .= "fid = '".$_POST["uni_fid"]."', ";
    $query .= "did = '".$_POST["uni_did"]."', ";
    $query .= "rapid = '".($_POST["uni_rapid"]==="on"?1:0)."', ";
    $query .= "moons = '".($_POST["uni_moons"]==="on"?1:0)."', ";
    $query .= "defrepair = '70', ";
    $query .= "defrepair_delta = '10', ";
    $query .= "usercount = '1', ";
    $query .= "freeze = '0', ";
    $query .= "news1 = '', ";
    $query .= "news2 = '', ";
    $query .= "news_until = '0', ";
    $query .= "startdate = '".$now."', ";
    $query .= "battle_engine = '".$_POST["uni_battle_engine"]."', ";
    $query .= "special = '".($_POST["uni_special"]==="on"?1:0)."' ";
    //echo "<br>$query<br>";
    dbquery ($query);

    // Создать технический аккаунт "space"
    $md = md5 ( gen_trivial_password() . $_POST['db_secret'] );
    $opt = " (";
    $user = array( 99999, $now, 0, 0, 0, "",  "", "space", "space", 0, 0, $md, "", "", "",
                        0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                        0, 0, "0.0.0.0", 1, "", 1, 2, 0, 0,
                        hostname() . "evolution/", 1, 1, 1, 3, 'en', 0,
                        0, 0, 0, 0, 0, 0, 0, 0, 0, 
                        0, 0, 0, 0, 0, 0,
                        0, 0, 0, 0, 0, 0, 0,
                        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 );
    foreach ($user as $i=>$entry)
    {
        if ($i != 0) $opt .= ", ";
        $opt .= "'".$user[$i]."'";
    }
    $opt .= ")";
    $query = "INSERT INTO ".$_POST["db_prefix"]."users VALUES".$opt;
    dbquery( $query);

    // Создать администраторский аккаунт (Legor).
    $md = md5 ($_POST['admin_pass'] . $_POST['db_secret']);
    $opt = " (";
    $user = array( 1, $now, 0, 0, 0, "",  "", "legor", "Legor", 0, 0, $md, "", $_POST['admin_email'], $_POST['admin_email'],
                        0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                        0, 0, "0.0.0.0", 1, "", 1, 2, 0, 0,
                        hostname() . "evolution/", 1, 1, 1, 3, 'en', 1,
                        1000000, 0, 0, 0, 0, 0, 0, 0, 0, 
                        0, 0, 0, 0, 0, 0,
                        0, 0, 0, 0, 0, 0, 0,
                        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 );
    foreach ($user as $i=>$entry)
    {
        if ($i != 0) $opt .= ", ";
        $opt .= "'".$user[$i]."'";
    }
    $opt .= ")";
    $query = "INSERT INTO ".$_POST["db_prefix"]."users VALUES".$opt;
    dbquery( $query);

    // Создать планету Arrakis [1:1:2] и луну Mond.
    $opt = " (";
    $planet = array( 1, "Arakis", 102, 1, 1, 2, 1, 12800, 40, 0, 163, $now,
                           0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
                           0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                           0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                           500, 500, 0, 1, 1, 1, 1, 1, 1, $now, $now, 0, 0 );
    foreach ($planet as $i=>$entry)
    {
        if ($i != 0) $opt .= ", ";
        $opt .= "'".$planet[$i]."'";
    }
    $opt .= ")";
    $query = "INSERT INTO ".$_POST["db_prefix"]."planets VALUES".$opt;
    dbquery( $query);
    $opt = " (";
    $planet = array( 2, "Mond", 0, 1, 1, 2, 1, 8944, 10, 0, 0, $now,
                           0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
                           0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                           0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                           0, 0, 0, 1, 1, 1, 1, 1, 1, $now, $now, 0, 0 );
    foreach ($planet as $i=>$entry)
    {
        if ($i != 0) $opt .= ", ";
        $opt .= "'".$planet[$i]."'";
    }
    $opt .= ")";
    $query = "INSERT INTO ".$_POST["db_prefix"]."planets VALUES".$opt;
    dbquery( $query);

    // Добавить параметры экспедиции.
    $opt = " (";
    $exptab = array ( 70, 25, 50, 75, 25, 50, 75, 95, 85, 70, 69, 63, 60, 25, 1 );
    foreach ($exptab as $i=>$entry)
    {
        if ($i != 0) $opt .= ", ";
        $opt .= "'".$exptab[$i]."'";
    }
    $opt .= ")";
    $query = "INSERT INTO ".$_POST["db_prefix"]."exptab VALUES".$opt;
    dbquery( $query);

    // Установить счетчики автоинкремента.
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."planets AUTO_INCREMENT = 10000;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."messages AUTO_INCREMENT = 10000;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."notes AUTO_INCREMENT = 1;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."buddy AUTO_INCREMENT = 1;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."ally AUTO_INCREMENT = 1;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."allyapps AUTO_INCREMENT = 10000;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."debug AUTO_INCREMENT = 10000;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."errors AUTO_INCREMENT = 10000;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."browse AUTO_INCREMENT = 1;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."fleet AUTO_INCREMENT = 10000;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."union AUTO_INCREMENT = 10000;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."queue AUTO_INCREMENT = 1;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."battledata AUTO_INCREMENT = 1;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."fleetlogs AUTO_INCREMENT = 1;" );
    dbquery ( "ALTER TABLE ".$_POST["db_prefix"]."iplogs AUTO_INCREMENT = 1;" );

    // Сохранить файл конфигурации.
    $file = fopen ("config.php", "wb");
    if ($file == FALSE) $InstallError = "Не удалось сохранить файл конфигурации.";
    else
    {
        fwrite ($file, "<?php\r\n");
        fwrite ($file, "// Создано автоматически НЕ ИЗМЕНЯТЬ!\r\n");
        fwrite ($file, "$"."StartPage=\"". $_POST["startpage"] ."\";\r\n");
        fwrite ($file, "$"."db_host=\"". $_POST["db_host"] ."\";\r\n");
        fwrite ($file, "$"."db_user=\"". $_POST["db_user"] ."\";\r\n");
        fwrite ($file, "$"."db_pass=\"". $_POST["db_pass"] ."\";\r\n");
        fwrite ($file, "$"."db_name=\"". $_POST["db_name"] ."\";\r\n");
        fwrite ($file, "$"."db_prefix=\"". $_POST["db_prefix"] ."\";\r\n");
        fwrite ($file, "$"."db_secret=\"". $_POST["db_secret"] ."\";\r\n");
        fwrite ($file, "?>");
        fclose ($file);
        $InstallError = "<font color=lime>Установка завершена. Файл конфигурации создан.</font>";
    }
}

?>

<html>
<head>
<meta http-equiv='content-type' content='text/html; charset=utf-8' />
<TITLE>Установка OGame</TITLE>
</head>

<body style='background:#000000 url(img/space_background.jpg) no-repeat fixed right top; color: #fff;'>

<style>
td.c { background-color: #334445; }
.button { border: 1px solid; color: white; background-color: #334445; }
.text { border: 1px solid; color: white; background-color: #334445; }
#install_form { background: url(img/page_bg.png); }
</style>

<center>
<form action='install.php' method='POST'>
<input type=hidden name='install' value='1'>

<img src='img/install.png'><br>

<font color=red><?=$InstallError?></font>

<table id='install_form'
<tr><td>&nbsp;</td></tr>
<tr><td>Стартовая страница</td><td><input type=text value='http://ogame.ru' class='text' name='startpage'></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan=2 class='c'>Настройки базы данных</td></tr>
<tr><td>Хост</td><td><input type=text value='localhost' class='text' name='db_host'></td></tr>
<tr><td>Пользователь</td><td><input type=text class='text' name='db_user'></td></tr>
<tr><td>Пароль</td><td><input type=password class='text'  name='db_pass'></td></tr>
<tr><td>Название БД</td><td><input type=text class='text' name='db_name'></td></tr>
<tr><td><a title='Чтобы было легко найти все таблицы этой вселенной, задайте им общий префикс'>Префикс таблиц</a></td><td><input type=text value='uni1_' class='text' name='db_prefix'></td></tr>
<tr><td><a title='Используется при генерации паролей и сессий'>Секретное слово</a></td><td><input type=text type=password class='text' name='db_secret'></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan=2 class='c'>Настройки вселенной</td></tr>
<tr><td><a title='Номер вселенной будет указан в заголовке окна и над главным меню в игре.'>Номер вселенной</a></td><td><input type=text value='1' class='text' name='uni_num'></td></tr>
<tr><td><a title='Ускорение игры влияет на скорость добычи ресурсов, длительность построек и проведение исследований, минимальную длительность Режима Отпуска.'>Ускорение</a></td><td><input type=text value='1' class='text' name='uni_speed'></td></tr>
<tr><td><a title='Ускорение флота влияет только на скорость летящих флотов'>Ускорение флота</a></td><td><input type=text value='1' class='text' name='uni_fspeed'></td></tr>
<tr><td>Количество галактик</td><td><input type=text value='9' class='text' name='uni_galaxies'></td></tr>
<tr><td>Количество систем</td><td><input type=text value='499' class='text' name='uni_systems'></td></tr>
<tr><td><a title='Максимальное количество аккаунтов. После достижения этого значения регистрация закрывается до тех пор, пока не освободится место.'>Максимум игроков</a></td><td><input type=text value='12500' class='text' name='uni_maxusers'></td></tr>
<tr><td><a title='Максимальное количество приглашенных игроков для Совместной атаки. Максимальное количство флотов в САБ вычисляется по формуле N*4, где N - количетсво участников. При N=0 САБ отключен.'>Участников САБ</a></td><td><input type=text value='4' class='text' name='uni_acs'></td></tr>
<tr><td><a title='Флот в Обломки. Указанное количество процентов флота выпадает в виде обломков. Если указано 0, то ФВО отключено.'>Обломки флота</a></td><td><input type=text value='30' class='text' name='uni_fid'></td></tr>
<tr><td><a title='Оборона в Обломки. Указанное количество процентов обороны выпадает в виде обломков. Если указано 0, то ОВО отключено.'>Обломки обороны</a></td><td><input type=text value='0' class='text' name='uni_did'></td></tr>
<tr><td><a title='Корабли получают возможность повторного выстрела'>Скорострел</a></td><td><input type=checkbox class='text' name='uni_rapid' CHECKED></td></tr>
<tr><td>Луны и Звезды Смерти</td><td><input type=checkbox class='text' name='uni_moons' CHECKED></td></tr>
<tr><td><a title='Перезапуск вселенной каждый день в 20:00. Игроку даётся 3 года игрового времени для мгновенного завершения любых заданий, кроме боевых.'>Специальная Вселенная</a></td><td><input type=checkbox class='text' name='uni_special' ></td></tr>
<tr><td>Путь к боевому движку</td><td><input type=text value='../cgi-bin/battle' class='text' name='uni_battle_engine'></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan=2 class='c'>Аккаунт администратора игры (Legor)</td></tr>
<tr><td>E-Mail</td><td><input type=text class='text' name='admin_email'></td></tr>
<tr><td>Пароль</td><td><input type=password class='text' name='admin_pass'></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan=2><center><input type=submit value='Инсталлировать' class='button'></center></td></tr>
</table>

</form>

</center>
</body>
</html>