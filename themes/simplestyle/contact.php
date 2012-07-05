<?php the_header(); ?>
<div id="content">
  <h1><?php the_title(); ?></h1>
  <?php the_content(); ?>
  <form action="#" method="post">
    <div class="form_settings">
      <p><span>Name</span><input class="contact" type="text" name="your_name" value="" /></p>
      <p><span>Email Address</span><input class="contact" type="text" name="your_email" value="" /></p>
      <p><span>Message</span><textarea class="contact textarea" rows="8" cols="50" name="your_enquiry"></textarea></p>
      <p style="padding-top: 15px"><span>&nbsp;</span><input class="submit" type="submit" name="contact_submitted" value="submit" /></p>
    </div>
  </form>
  <p><br /><br />NOTE: This contact form is only a demo, it does not work.</p>
</div>
<?php the_footer(); ?>