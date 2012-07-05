<?php
global $utils;
$member = $utils->get_session('member');
if(!isset($member) || $member->member_role != 1){
    header("Location: ".the_site_url(false).'admin/?p=login');
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title><?php lang('ADMINISTRATION'); ?></title>
    <link rel="stylesheet" href="<?php the_theme_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php the_theme_url(); ?>redactor/css/redactor.css">

    <?php 
    the_stylesheet();
    load_scripts_backend();
    ?>

</head>
<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="#"><?php lang('ADMINISTRATION'); ?></a>
                <div class="btn-group pull-right">
                    <a href="#" class="btn dropdown-toggle">
                        <i class="icon-user"></i>
                        <?php echo $member->member_username; ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo the_site_url(false) . 'admin/?p=members&edit=' . $member->member_id; ?>"><?php lang("PROFILE"); ?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo the_site_url(false) . 'admin/?p=logout'; ?>"><?php lang("SIGN_OUT"); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <!-- menu -->
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <?php
                        global $admin_menu, $utils;
                        $admin_menu = $utils->order_array_num($admin_menu, 'position');
                        $current_slug = (isset($_GET['p']) && !empty($_GET['p'])) ? $_GET['p'] : 'index';
                        $children_slug = (isset($_GET['plugin']) && !empty($_GET['plugin'])) ? $_GET['plugin'] : null;

                        foreach($admin_menu as $menu){
                            $classes = '';
                            if($current_slug == $menu['page_slug']){
                                $classes = 'class="active"';
                            } else if(isset($menu['children'])){
                                foreach ($menu['children'] as $child) {
                                    if($children_slug == $child['page_slug']){
                                        $classes = 'class="active"';
                                    }
                                }
                                if(empty($classes)){
                                    $classes = 'class="withchildren"';
                                }
                            }
                            $slug = $menu['page_slug'];
                            $menu_title = $menu['menu_title'];
                            $icon = $menu['icon'];
                            echo "<li $classes><a href=\"?p=$slug\"><i class=\"$icon\"></i> $menu_title</a>";
                            if(isset($menu['children'])){
                                echo '<ul class="child">';
                                foreach($menu['children'] as $child){
                                    $s = explode('=', $child['page_slug']);
                                    $classes = ($children_slug == end($s)) ? 'class="active"' : '' ;
                                    $slug = $child['page_slug'];
                                    $menu_title = $child['menu_title'];
                                    echo "<li $classes><a href=\"?p=$slug\">$menu_title</a>";
                                }
                                echo '</ul>';
                            }
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <!-- end menu -->
            <div class="span10">