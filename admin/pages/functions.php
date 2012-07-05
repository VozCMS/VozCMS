<?php
$js_dir = THEME_FOLDER . 'js/';

// Register jQuery
register_script('jquery', $js_dir . 'jquery-1.7.2.min.js', '1.7.2');

// Register Bootstrap
register_script('bootstrap', $js_dir . 'bootstrap.js', '2.0.4');

// Register jQuery Plugins (in footer)
register_script('redactor', THEME_FOLDER . 'redactor/redactor.js', '7.6.4', true);
register_script('jquery-ui', $js_dir . 'jquery-ui-1.8.21.custom.min.js', '1.8.21', true);

// Register Bootstrap Plugins (in footer)
register_script('bootstrap-collapse', $js_dir . 'bootstrap-collapse.js', '2.0.4', true);
register_script('bootstrap-dropdown', $js_dir . 'bootstrap-dropdown.js', '2.0.4', true);

// Register javascript plugins and main scripts file.
register_script('main-scripts', $js_dir . 'scripts.js', '1.0', true);

// Queue the scripts
queue_script('jquery', true);
queue_script('redactor', true);
queue_script('jquery-ui', true);
queue_script('bootstrap', true);
queue_script('bootstrap-collapse', true);
queue_script('bootstrap-dropdown', true);
queue_script('main-scripts', true);

function valid_email($email){
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if(is_bool($atIndex) && !$atIndex){
      $isValid = false;
   } else {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64){
         // local part length exceeded
         $isValid = false;
      } else if ($domainLen < 1 || $domainLen > 255){
         // domain part length exceeded
         $isValid = false;
      } else if ($local[0] == '.' || $local[$localLen-1] == '.'){
         // local part starts or ends with '.'
         $isValid = false;
      } else if (preg_match('/\\.\\./', $local)){
         // local part has two consecutive dots
         $isValid = false;
      } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
         // character not valid in domain part
         $isValid = false;
      } else if (preg_match('/\\.\\./', $domain)){
         // domain part has two consecutive dots
         $isValid = false;
      } else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))){
         if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))){
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){
         $isValid = false;
      }
   }
   return $isValid;
}