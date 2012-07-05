<?php global $lang; ?>
<!DOCTYPE HTML>
<html>

<head>
  <title><?php the_meta_title(); ?></title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link rel="stylesheet" type="text/css" href="<?php the_theme_url(); ?>style/style.css" />
  <?php the_head(); ?>
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="<?php the_site_url(); ?>"><?php the_site_name(); ?></a></h1>
          <h2>Simple. Contemporary. Website Template.</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <?php 
          $pages = get_pages();
          foreach($pages as $page){
            $url = the_site_url(false).$page->post_slug;
            $url .= empty($page->post_slug) ? '' : '/';
            $klass = (the_ID(false) == $page->post_id)? 'class="selected"' : '';
            echo '<li '.$klass.'><a href="'.$url.'">'.$page->post_title.'</a></li>';
          }
          ?>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div class="sidebar">
        <h1><?php echo $lang['LATESTNEWS']; ?></h1>
        <h4>New Website Launched</h4>
        <h5>January 1st, 2010</h5>
        <p>2010 sees the redesign of our website. Take a look around and let us know what you think.<br /><a href="#">Read more</a></p>
        <h1>Useful Links</h1>
        <ul>
          <li><a href="#">link 1</a></li>
          <li><a href="#">link 2</a></li>
          <li><a href="#">link 3</a></li>
          <li><a href="#">link 4</a></li>
        </ul>
        <h1>Search</h1>
        <form method="post" action="#" id="search_form">
          <p>
            <input class="search" type="text" name="search_field" value="Enter keywords....." />
            <input name="search" type="image" style="border: 0; margin: 0 0 -9px 5px;" src="<?php the_theme_url(); ?>style/search.png" alt="Search" title="Search" />
          </p>
        </form>
      </div>