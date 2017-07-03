<aside class='side'>
	<nav>
		<?= $form->create('Pages',array('action' => 'files','default' => false)); ?>
		<?= $form->input('base',array('value' => $base,'label' => 'ディレクトリ名')); ?>
		<?= $form->end('移動'); ?>
		
		<ul id='dirs'>
		</ul>
		<a href='#' id='createDir'>+ディレクトリ追加</a>
		
		<hr />
		
		<ul id='files'>
		</ul>
		<a href='#' id='createFile'>+ファイル追加</a>
	</nav>
</aside>

<section style='float:left;' id='pageForms'>
	<?= $form->create('Pages',array('action' => 'edit')); ?>
	<?= $form->hidden('base'); ?>
	<?= $form->hidden('name'); ?>
	<?= $form->input('newName',array('label' => 'ファイル名')); ?>
	<?= $form->textarea('source',array('rows' => 15,'cols' => 60)); ?>
	<?= $form->end('編集'); ?>

	<?= $form->create('Pages',array('action' => 'delete')); ?>
	<?= $form->hidden('base'); ?>
	<?= $form->hidden('name'); ?>
	<?= $form->end('削除'); ?>
</section>

<?= $javascript->link('page',false); ?>