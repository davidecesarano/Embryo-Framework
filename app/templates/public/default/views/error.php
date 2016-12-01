<?php $this->template_part('header'); ?>
	
	<div class="page-header">
		<h1><?php echo $type; ?></h1>
	</div>
	<p class="lead">
		<?php echo $message; ?><br />
		Questo contenuto può essere cambiato in <code><?php echo template_folder('error.php'); ?></code><br /> 
	</p>
	<hr />
	<p>Torna alla <a href="<?php echo site_url(); ?>">Home Page</a>.</p>
	
<?php $this->template_part('footer'); ?>