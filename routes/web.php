<?php

require_once __DIR__ . "/web/admin.php";

$app->get('[/]', 'Web.HomeController:index')->setName('home');
