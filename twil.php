<?
$template = false;
?>

<html lang="ja">
<head>
	<meta charset="SHIFT-JIS" />
	<title>������Twil�ɗ͂��I�I - �P�[�^�C only</title>
</head>
<body>
	<? if (!empty($_GET["mail"])): ?>
		<? $mail = $_GET["mail"] ?>
		- Twil -
		<br>
		&#63879;<a href="mailto:<?= $mail ?>" accesskey="1">TL</a>
		&#63880;<a href="mailto:<?= $mail ?>?subject=R" accesskey="2">�ԐM</a>
		&#63881;<a href="mailto:<?= $mail ?>?subject=D" accesskey="3">DM</a>
		&#63882;<a href="mailto:<?= $mail ?>?subject=�H" accesskey="4">����</a>
		<br><br>
		&#63883;<a href="mailto:<?= $mail ?>?subject=@user/list" accesskey="5">���X�g</a>
		&#63884;<a href="mailto:<?= $mail ?>?subject=@user?" accesskey="6">�v���t�B�[��</a>
		&#63885;<a href="mailto:<?= $mail ?>?subject=@user" accesskey="7">���[�U�[�̃c�C�[�g</a>
		<br><br>
		&#63886;<a href="mailto:<?= $mail ?>?subject=�t�H���[�F" accesskey="8">�t�H���[</a>
		&#63887;<a href="mailto:<?= $mail ?>?subject=�A���t�H���[�F" accesskey="9">�t�H���[�̉���</a>
		<br><br>
		&#63888;<a href="mailto:<?= $mail ?>?subject=�w���v" accesskey="0">�w���v</a>
		<br><br>
		���B�F<br>
		<a href="mailto:<?= $mail ?>?subject=���B�F�J�n">�J�n</a> 
		<a href="mailto:<?= $mail ?>?subject=���B�F��~">��~</a> 
		<a href="mailto:<?= $mail ?>?subject=���B�F6������18��">�w�莞��</a>
		<br><br>
		����F<br>
		<a href="mailto:<?= $mail ?>?subject=����F10��">�w�莞�Ԃ���</a> 
		<a href="mailto:<?= $mail ?>?subject=����F6������18��">���Ԏw��</a> 
		<a href="mailto:<?= $mail ?>?subject=����F20��">����</a> 
		<a href="mailto:<?= $mail ?>?subject=����F�S�č폜">�S�č폜</a>
		<br><br>
		���Ԏw��F<br>
		<a href="mailto:<?= $mail ?>?subject=���Ԏw��F1��2��3��4��">�w�莞��</a> 
		<a href="mailto:<?= $mail ?>?subject=���Ԏw��F1���Ԍ�">�w�莞����</a> 
		<a href="mailto:<?= $mail ?>?subject=���Ԏw��F���Z�b�g">���Z�b�g</a>
		<br><br>

		- �摜 -
		<br>
		��]�F<br>
		<a href="mailto:<?= $mail ?>?subject=��]�F�E">�E��]</a>
		<a href="mailto:<?= $mail ?>?subject=��]�F��">����]</a>
		<br><br>
		���e��F<br>
		<a href="mailto:<?= $mail ?>?subject=�c�C��">�c�C��</a> 
		<a href="mailto:<?= $mail ?>?subject=�c�C�s�N">�c�C�s�N</a> 
		<a href="mailto:<?= $mail ?>?subject=�C���W��">�C���W��</a> 
		<a href="mailto:<?= $mail ?>?subject=�͂Ă�">�͂Ă�</a> 
		<a href="mailto:<?= $mail ?>?subject=�g�ѕS�i">�g�ѕS�i</a>
		<br><br>

		- �ݒ� -
		<br>
		�m�F:<br>
		<a href="mailto:<?= $mail ?>?subject=�m�F�F�Ȃ�">�Ȃ�</a> 
		<a href="mailto:<?= $mail ?>?subject=�m�F�F�ŏ�">�ŏ�</a> 
		<a href="mailto:<?= $mail ?>?subject=�m�F�F�^�C�����C��">�^�C�����C��</a> 
		<a href="mailto:<?= $mail ?>?subject=�m�F�F�ԐM">�ԐM</a>
		<br><br>
		���[��:<br>
		<a href="mailto:<?= $mail ?>?subject=���[���F�e�L�X�g">�e�L�X�g</a> 
		<a href="mailto:<?= $mail ?>?subject=���[���F�V���v��">�V���v��</a> 
		<a href="mailto:<?= $mail ?>?subject=���[���F�f�R���[��">�f�R���[��</a> 
		<a href="mailto:<?= $mail ?>?subject=���[���F�t��">�t��</a>
		<br><br>
		�c�C�[�g:<br>
		<a href="mailto:<?= $mail ?>?subject=�c�C�[�g:20��">�w�茏��</a> 
		<a href="mailto:<?= $mail ?>?subject=�c�C�[�g:�V�K�̂�">�V�K�̂�</a> 
		<a href="mailto:<?= $mail ?>?subject=�c�C�[�g:���[�U��:����">���[�U������</a> 
		<a href="mailto:<?= $mail ?>?subject=�c�C�[�g:���[�U��:�Ȃ�">���[�U���Ȃ�</a> 
		<a href="mailto:<?= $mail ?>?subject=�c�C�[�g:�A�b�g�}�[�N:����">@����</a> 
		<a href="mailto:<?= $mail ?>?subject=�c�C�[�g:�A�b�g�}�[�N:�Ȃ�">@�Ȃ�</a>
		<br><br>
		�t�H���g:<br>
		<a href="mailto:<?= $mail ?>?subject=�t�H���g�F��">��</a> 
		<a href="mailto:<?= $mail ?>?subject=�t�H���g�F��">��</a> 
		<a href="mailto:<?= $mail ?>?subject=�t�H���g�F��">��</a> 
		<a href="mailto:<?= $mail ?>?subject=�t�H���g�F�W��">�W��</a>
		<br><br>
		���c�C�[�g:<br>
		<a href="mailto:<?= $mail ?>?subject=���c�C�[�g�F����">����</a> 
		<a href="mailto:<?= $mail ?>?subject=���c�C�[�g�F���[�U�[">���[�U�[</a>
		<br><br>
		<a href="mailto:<?= $mail ?>?subject=�����F#twil">�����̕ύX</a>
		<br><br>
		<a href="mailto:<?= $mail ?>?subject=�A�h���X�ǉ��F">���[���A�h���X�̒ǉ�</a>
		<br><br>
		<a href="mailto:<?= $mail ?>?subject=�ݒ�">�ݒ���m�F����</a>
	<? else: ?>
		<p>���Ȃ���Twil�̃A�h���X����͂��Ă��������B
		<p>��Ftwil2-abc@docodemo.jp
		
		<form action='twil.php'>
		<input type='text' name='mail'>
		<input type='submit' value='���M'>
		</form>
		
		<p>���M��ɕ\�����ꂽ�y�[�W���A��ʃ����ɒǉ����邾���I
		<p>Enjoy your Twil life�I
		
		<p>inspired by <a href="http://2dkukan.blog.shinobi.jp/Entry/218/">2������� Twil�R�}���h�W��ʃ���</a>
	<? endif ?>
</body>
</html>