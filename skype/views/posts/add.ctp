<?= $form->create() ?>
<ul id='warning'>
	<li>「Skype × Skype（スカイプ×スカイプ）」は、スカイプでの通話・チャット・会議にスカイプ友達を募集する掲示板であり、出会いを目的とする場所ではありません。</li>
	<li>連続投稿、もしくは前回の投稿から60秒以内の投稿はできません。</li>
</ul>

<?= $form->error('ip') ?>
<?= $form->input('Post.parent_id', array('type' => 'hidden')) ?>
<? if (empty($this->data['Post']['parent_id'])): ?>
	<?= $form->input('Post.title', array('label' => 'タイトル')) ?>
<? endif ?>
<?= $form->input('Post.username', array('label' => '名前')) ?>
<?= $form->input('Post.body', array('label' => '本文', 'rows' => 6)) ?>
<?= $form->input('Post.password', array('label' => '編集用パスワード')) ?>
<?= $form->end('投稿') ?>