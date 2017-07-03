<p>持ち金:<?= $money; ?>円</p>
<p>残額：<?= $bank; ?>円</p>
<br />

<p><?= $html->link('入出金明細','/banks/view/'); ?></p>
<br />

<section>
	<h2>入出金</h2>

	<?= $form->create('Bank',array('action' => 'trade')); ?>
	<div>
		<label>操作</label>
		<div>
			<?= $form->radio(
				'Bank.work',
				array('in' => '振込','out' => '引出'),
				array('legend' => false,'value' => 'in')
			); ?>
		</div>
	</div>
	<?= $form->input('Bank.amount',array(
		'label' => '金額',
		'after' => '/'.$form->checkbox('all').'全額'
	)); ?>
	<?= $form->end('決定'); ?>
</section>

<section>
	<h2>送金</h2>

	<?= $form->create('Bank', array('action' => 'send')); ?>
	<?= $form->input('User.username', array('label' => '送信先')); ?>
	<?= $form->input('Bank.amount', array('label' => '金額')); ?>
	<?= $form->end('送金') ?>
</section>