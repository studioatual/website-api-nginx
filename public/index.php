<?php

session_start();

date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, 'pt_BR');

require __DIR__ . '/../bootstrap/app.php';

$app->run();
