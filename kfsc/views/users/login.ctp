<?
/*
 * $users : array(username => password)
*/

$usernames = array();
foreach($users as $username => $password) {
	$usernames[$username] = $username;
}
?>

<?= $form->create('User',array('action' => 'login','class' => 'justify')); ?>
<? $session->flash('auth'); ?>
<?= $form->input('username',array('label' => '学校名')); ?>
<?= $form->input('password',array('label' => 'パスワード')); ?>
<?= $form->end('ログイン') ?>
