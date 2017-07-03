<?
$template = false;
?>

<html lang="ja">
<head>
	<meta charset="SHIFT-JIS" />
	<title>もっとTwilに力を！！ - ケータイ only</title>
</head>
<body>
	<? if (!empty($_GET["mail"])): ?>
		<? $mail = $_GET["mail"] ?>
		- Twil -
		<br>
		&#63879;<a href="mailto:<?= $mail ?>" accesskey="1">TL</a>
		&#63880;<a href="mailto:<?= $mail ?>?subject=R" accesskey="2">返信</a>
		&#63881;<a href="mailto:<?= $mail ?>?subject=D" accesskey="3">DM</a>
		&#63882;<a href="mailto:<?= $mail ?>?subject=？" accesskey="4">検索</a>
		<br><br>
		&#63883;<a href="mailto:<?= $mail ?>?subject=@user/list" accesskey="5">リスト</a>
		&#63884;<a href="mailto:<?= $mail ?>?subject=@user?" accesskey="6">プロフィール</a>
		&#63885;<a href="mailto:<?= $mail ?>?subject=@user" accesskey="7">ユーザーのツイート</a>
		<br><br>
		&#63886;<a href="mailto:<?= $mail ?>?subject=フォロー：" accesskey="8">フォロー</a>
		&#63887;<a href="mailto:<?= $mail ?>?subject=アンフォロー：" accesskey="9">フォローの解除</a>
		<br><br>
		&#63888;<a href="mailto:<?= $mail ?>?subject=ヘルプ" accesskey="0">ヘルプ</a>
		<br><br>
		速達：<br>
		<a href="mailto:<?= $mail ?>?subject=速達：開始">開始</a> 
		<a href="mailto:<?= $mail ?>?subject=速達：停止">停止</a> 
		<a href="mailto:<?= $mail ?>?subject=速達：6時から18時">指定時刻</a>
		<br><br>
		定期：<br>
		<a href="mailto:<?= $mail ?>?subject=定期：10分">指定時間ごと</a> 
		<a href="mailto:<?= $mail ?>?subject=定期：6時から18時">時間指定</a> 
		<a href="mailto:<?= $mail ?>?subject=定期：20件">件数</a> 
		<a href="mailto:<?= $mail ?>?subject=定期：全て削除">全て削除</a>
		<br><br>
		時間指定：<br>
		<a href="mailto:<?= $mail ?>?subject=時間指定：1月2日3時4分">指定時刻</a> 
		<a href="mailto:<?= $mail ?>?subject=時間指定：1時間後">指定時刻後</a> 
		<a href="mailto:<?= $mail ?>?subject=時間指定：リセット">リセット</a>
		<br><br>

		- 画像 -
		<br>
		回転：<br>
		<a href="mailto:<?= $mail ?>?subject=回転：右">右回転</a>
		<a href="mailto:<?= $mail ?>?subject=回転：左">左回転</a>
		<br><br>
		投稿先：<br>
		<a href="mailto:<?= $mail ?>?subject=ツイル">ツイル</a> 
		<a href="mailto:<?= $mail ?>?subject=ツイピク">ツイピク</a> 
		<a href="mailto:<?= $mail ?>?subject=イメジリ">イメジリ</a> 
		<a href="mailto:<?= $mail ?>?subject=はてな">はてな</a> 
		<a href="mailto:<?= $mail ?>?subject=携帯百景">携帯百景</a>
		<br><br>

		- 設定 -
		<br>
		確認:<br>
		<a href="mailto:<?= $mail ?>?subject=確認：なし">なし</a> 
		<a href="mailto:<?= $mail ?>?subject=確認：最小">最小</a> 
		<a href="mailto:<?= $mail ?>?subject=確認：タイムライン">タイムライン</a> 
		<a href="mailto:<?= $mail ?>?subject=確認：返信">返信</a>
		<br><br>
		メール:<br>
		<a href="mailto:<?= $mail ?>?subject=メール：テキスト">テキスト</a> 
		<a href="mailto:<?= $mail ?>?subject=メール：シンプル">シンプル</a> 
		<a href="mailto:<?= $mail ?>?subject=メール：デコメール">デコメール</a> 
		<a href="mailto:<?= $mail ?>?subject=メール：フル">フル</a>
		<br><br>
		ツイート:<br>
		<a href="mailto:<?= $mail ?>?subject=ツイート:20件">指定件数</a> 
		<a href="mailto:<?= $mail ?>?subject=ツイート:新規のみ">新規のみ</a> 
		<a href="mailto:<?= $mail ?>?subject=ツイート:ユーザ名:あり">ユーザ名あり</a> 
		<a href="mailto:<?= $mail ?>?subject=ツイート:ユーザ名:なし">ユーザ名なし</a> 
		<a href="mailto:<?= $mail ?>?subject=ツイート:アットマーク:あり">@あり</a> 
		<a href="mailto:<?= $mail ?>?subject=ツイート:アットマーク:なし">@なし</a>
		<br><br>
		フォント:<br>
		<a href="mailto:<?= $mail ?>?subject=フォント：大">大</a> 
		<a href="mailto:<?= $mail ?>?subject=フォント：中">中</a> 
		<a href="mailto:<?= $mail ?>?subject=フォント：小">小</a> 
		<a href="mailto:<?= $mail ?>?subject=フォント：標準">標準</a>
		<br><br>
		リツイート:<br>
		<a href="mailto:<?= $mail ?>?subject=リツイート：公式">公式</a> 
		<a href="mailto:<?= $mail ?>?subject=リツイート：ユーザー">ユーザー</a>
		<br><br>
		<a href="mailto:<?= $mail ?>?subject=署名：#twil">署名の変更</a>
		<br><br>
		<a href="mailto:<?= $mail ?>?subject=アドレス追加：">メールアドレスの追加</a>
		<br><br>
		<a href="mailto:<?= $mail ?>?subject=設定">設定を確認する</a>
	<? else: ?>
		<p>あなたのTwilのアドレスを入力してください。
		<p>例：twil2-abc@docodemo.jp
		
		<form action='twil.php'>
		<input type='text' name='mail'>
		<input type='submit' value='送信'>
		</form>
		
		<p>送信後に表示されたページを、画面メモに追加するだけ！
		<p>Enjoy your Twil life！
		
		<p>inspired by <a href="http://2dkukan.blog.shinobi.jp/Entry/218/">2次元空間 Twilコマンド集画面メモ</a>
	<? endif ?>
</body>
</html>