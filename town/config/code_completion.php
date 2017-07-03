<?php
// stop page loading
 exit;

// reference helpers so Eclipse provides code completion
 $ajax = new AjaxHelper();
 $cache = new CacheHelper();
 $form = new FormHelper();
 $tag['name'] = new HtmlHelper();
 $javascript = new JavascriptHelper();
 $number = new NumberHelper();
 $session = new SessionHelper();
 $text = new TextHelper();
 $time = new TimeHelper();
 ?>