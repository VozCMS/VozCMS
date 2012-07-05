    </div>
    <div id="footer">
      <p>
		<?php 
		$pages = get_pages();
		$first = true;
		foreach($pages as $page){
			$url = the_site_url(false).$page->post_slug;
			$url .= empty($page->post_slug) ? '' : '/';
			if(!$first){
				echo ' | ';
			}
			echo '<a href="'.$url.'">'.$page->post_title.'</a>';
			$first = false;
		}
		?>
      </p>
      <p>Copyright &copy; simplestyle_5 | Powered by <?php global $core; echo $core['name_ver']; ?> | <a href="http://www.html5webtemplates.co.uk">design from HTML5webtemplates.co.uk</a></p>
    </div>
  </div>
  <?php the_foot(); ?>
</body>
</html>
