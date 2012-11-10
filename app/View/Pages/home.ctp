<?php echo $this->Html->script('home', false); ?>

<?php echo $this->Form->create(); ?>
	<?php echo $this->Form->input('search', array('label' => false, 'placeholder' => 'search')); ?>
<?php echo $this->Form->end(); ?>

<div id="results">
  <ul id="newest"></ul>
  <ul id="top"></ul>
</div>