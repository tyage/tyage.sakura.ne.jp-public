<p>経験値が<?= $point; ?>増加しました。</p>
<p>（<?= $userJob['UserJob']['point']; ?>/<?= $maxPoint; ?>）</p>

<? if (!empty($levelUp)): ?>
	<p>レベルが上がりました！</p>
<? endif; ?>
<p>Lv.<?= $level; ?></p>

<? if (empty($salary)): ?>
	<p>給料まであと<?= $rest; ?>回です。</p>
<? else: ?>
	<p>給料を<?= $salary; ?>円もらいました。</p>
<? endif ?>