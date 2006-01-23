<?php

require('Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = '../templates';
$smarty->compile_dir = '../compile';
$smarty->cache_dir = '../cache';
$smarty->config_dir = '../configs';

$smarty->assign('name', 'Stan');
$smarty->display('index.tpl');

?>

