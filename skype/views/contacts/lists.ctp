<?= $form->create() ?>
<?= $form->input('Contact.password', array('label' => 'パスワード')) ?>
<?= $form->end('送信') ?>

<? if (!empty($contacts)): ?>
	<pre><? h(var_dump($contacts)) ?></pre>
<? endif ?>