<?php $this->template_part('header'); ?>
	
	<div class="page-header">
		<h1>Pagina</h1>
	</div>
	<p class="lead">
		Ciao, benvenuto dal controller <strong>Page</strong> e dal metodo <strong>example</strong>!<br />
		Questo contenuto può essere cambiato in <code><?php echo folder_template_public(); ?>/views/pages/single.php</code>
	</p>
	<hr />
	<p>Torna alla <a href="<?php echo site_url(); ?>">Home Page</a>.</p>
	
<?php $this->template_part('footer'); ?>