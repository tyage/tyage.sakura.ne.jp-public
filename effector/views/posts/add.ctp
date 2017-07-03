<? $five = array(1, 2, 3, 4, 5) ?>

<?= $form->create() ?>
<?= $form->input('Post.username', array('label' => '名前')) ?>
<?= $form->input('Post.name', array('label' => 'エフェクター名')) ?>
<?= $form->input('Post.cost', array('label' => '購入金額')) ?>
<?= $form->input('Post.cause', array('label' => '好きな音楽のジャンルや影響を受けたギタリストなどなど')) ?>
<?= $form->input('Post.instrument', array('label' => '演奏する楽器・エフェクターの接続など')) ?>
<?= $form->input('Post.category_id', array('label' => 'エフェクターの種類')) ?>
<?= $form->input('Post.all', array('label' => '総合評価', 'options' => $five)) ?>
<?= $form->input('Post.performance', array('label' => 'コストパフォーマンス', 'options' => $five)) ?>
<?= $form->input('Post.quality', array('label' => '音質', 'options' => $five)) ?>
<?= $form->input('Post.operability', array('label' => '使いやすさ', 'options' => $five)) ?>
<?= $form->input('Post.recommend', array('label' => 'おすすめ度', 'options' => $five)) ?>
<?= $form->input('Post.review', array('label' => 'エフェクターレビュー')) ?>
<?= $form->input('Post.password', array('label' => 'パスワード<br>（編集・削除の際に使用します）')) ?>
<?= $form->end('投稿') ?>