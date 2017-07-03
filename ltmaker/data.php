<?php
  $app_id = '';
  $app_secret = '';
  $my_url = 'http://tyage.sakura.ne.jp/ltmaker/';

  $code = $_REQUEST["code"];
 
  //get user access_token
  $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
    . $app_id . '&redirect_uri=' . urlencode($my_url) 
    . '&client_secret=' . $app_secret 
    . '&code=' . $code;
  $access_token = file_get_contents($token_url);
 
  // get stream info
  $fql_query_url = 'https://graph.facebook.com/'
    . 
'/fql?q=select+message,likes,description+from+stream+where+source_id=me()+and+message!=""+order+by+likes.count+desc+limit+100'
    . '&' . $access_token;
  $stream_result = file_get_contents($fql_query_url);
  $stream = json_decode($stream_result, true);
  $stream = $stream['data'];

  // get user info
  $fql_query_url = 'https://graph.facebook.com/'
    . '/fql?q=select+username,name,pic_with_logo,work,education+from+user+where+uid=me()'
    . '&' . $access_token;
  $user_result = file_get_contents($fql_query_url);
  $user = json_decode($user_result, true);
  $user = $user['data'][0];


  // format data
  $meta = array(
    'title' => 'how to use'.$user['name'],
    'author' => array(
      $user['name']
    ),
    'template' => 'like_keynote',
    'all_effect' => 'fade',
    'footer' => 'time'
  );
  
  $slides = array();
  foreach ($stream as $post) {
    $slides[] = array(
      'type' => 'list',
      'h1' => 'recently',
      'text' => array(
        $post['message']
      )
    );
  }

  $data = array(
    'meta' => $meta,
    'slides' => $slides
  );
  echo json_encode($data);

