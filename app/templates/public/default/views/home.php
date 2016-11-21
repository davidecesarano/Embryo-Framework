<?php $this->template_part('header'); ?>
	
	<div class="page-header">
		<h1>Home Page</h1>
	</div>
	<p class="lead">
		Ciao, benvenuto dal controller <strong>Page</strong>!<br />
		Questo contenuto può essere cambiato in <code><?php echo folder_views_template('home.php'); ?></code><br /> 
	</p>
	<hr />
	<p>Visita un'altra <a href="<?php echo site_url('example'); ?>">pagina</a>.</p>
	
<?php $this->template_part('footer'); ?>