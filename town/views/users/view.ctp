<h1><?= $user['User']['username']; ?>さんのステータス</h1>

<?= $html->image('chara'.DS.$user['User']['image']); ?>
<dl>
	<dt>性別</dt><dd><?= $user['Profile']['sex']; ?></dd>
	<dt>街</dt><dd><?= $town['Town']['name'] ?></dd>
	<dt>所持金</dt><dd><?= $user['Profile']['money']; ?>円</dd>
	<dt>銀行資産</dt><dd><?= $user['Profile']['bank'] ?>円</dd>
	<dt>コイン</dt><dd><?= $user['Profile']['coin'] ?>枚</dd>
	<dt>仕事</dt><dd><?= $userJob['Job']['name'] ?>（経験値：<?= $userJob['Job']['point']; ?>）</dd>
	<dt>健康</dt><dd><?= $user['Profile']['health']; ?></dd>
	<dt>身長</dt><dd><?= $user['Profile']['height']; ?></dd>
	<dt>体重</dt><dd><?= $user['Profile']['weight']; ?></dd>
	<dt>BMI</dt><dd><?= $user['Profile']['bmi']; ?></dd>
	<dt>体力</dt><dd><?= $user['Profile']['energy']; ?>/<?= $user['Profile']['maxEnergy']; ?></dd>
	<dt>精神力</dt><dd><?= $user['Profile']['spirit']; ?>/<?= $user['Profile']['maxSpirit']; ?></dd>
	<? foreach ($abilities as $ability): ?>
		<dt><?= __($ability); ?></dt><dd><?= $user['Profile'][$ability]; ?></dd>
	<? endforeach; ?>
</dl>