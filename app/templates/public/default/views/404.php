<?php $this->template_part('header'); ?>
	
	<div class="page-header">
		<h1>Errore 404!</h1>
	</div>
	<p class="lead">
		Spiacente, la pagina che stai cercando non esiste.<br />
		Questo contenuto può essere cambiato in <code><?php echo folder_template_public(); ?>/views/404.php</code><br /> 
	</p>
	<hr />
	<p>Torna alla <a href="<?php echo site_url(); ?>">Home Page</a>.</p>
	
<?php $this->template_part('footer'); ?>