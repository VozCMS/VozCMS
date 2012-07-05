<?php
global $utils;
$utils->del_session('member');
header("Location: ".the_site_url(false));
?>