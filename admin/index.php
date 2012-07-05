<?php
session_start();

define('BASE_PATH', dirname(dirname(realpath(__FILE__))));

global $admin_panel;
$admin_panel = true;

require_once(BASE_PATH . '/includes/system/init.php');