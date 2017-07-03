<p>現在コインを<?= $coin ?>枚、お金を<?= $money; ?>円持っています。</p>

<section>
	<h1>購入する</h1>
	<p>一枚<?= $buyRate; ?>円で購入できます。</p>
	<?= $form->create('Coin', array('action' => 'buy')); ?>
	<?= $form->input('Coin.amount', array('label' => '枚数')); ?>
	<?= $form->input('Coin.all', array('label' => '買えるだけ買う','type' => 'checkbox')); ?>
	<?= $form->end('購入'); ?>
</section>

<section>
	<h1>売却する</h1>
	<p>一枚<?= $sellRate; ?>円で売却できます。</p>
	<?= $form->create('Coin', array('action' => 'sell')); ?>
	<?= $form->input('Coin.amount', array('label' => '枚数')); ?>
	<?= $form->input('Coin.all', array('label' => '売れるだけ売る','type' => 'checkbox')); ?>
	<?= $form->end('売却'); ?>
</section>