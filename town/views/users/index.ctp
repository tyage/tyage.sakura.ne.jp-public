<div id='towns' class='inline'>
	<?= $this->element('town',array('town' => $town)); ?>
</div>

<div class='inline'>
	<?= $this->element('map', array('location' => $town['Town']['id'])); ?>
</div>

<?= $html->css('town', null, array('inline' => false)); ?>
<?= $html->script('town', array('inline' => false)); ?>
<?= $this->Html->script('jquery/jquery.ui.core', array('inline' => false)) ?>
<?= $this->Html->script('jquery/jquery.ui.widget', array('inline' => false)) ?>
<?= $this->Html->script('jquery/jquery.ui.mouse', array('inline' => false)) ?>
<?= $this->Html->script('jquery/jquery.ui.draggable', array('inline' => false)) ?>

<script>
$(function () {
	$('.self').draggable({
		containment: '#town-container',
		stop: function () {
			var position = $(this).position();
			$.ajax({
				url: '/town/users/move/'+position.left+'/'+position.top
			});
		}
	});
})
</script>