<?php
  $app_id = '';
  $app_secret = '';
  $my_url = 'http://tyage.sakura.ne.jp/ltmaker/';

  $code = $_REQUEST["code"];
 
 //auth user
 if(empty($code)) {
    $dialog_url = 'https://www.facebook.com/dialog/oauth?client_id=' 
    . $app_id . '&redirect_uri=' . urlencode($my_url) 
    . '&scope=read_stream,read_insights,user_education_history,user_work_history';
    echo("<script>top.location.href='" . $dialog_url . "'</script>");
    exit();
  }

$dialog_url = $my_url.'jsolide/index.html?data='.$my_url.'data.php?code='.$code;
echo("<script>top.location.href='" . $dialog_url . "'</script>");
