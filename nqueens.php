<!DOCTYPE html>
<?php
require_once('nqueens.class.php');
$q = new Queens();

?>
<html>
<head>
    <title><?php echo $q->title; ?></title>
    
    <?php $q->insert_head(); ?>
    
</head>

<body>

	<?php $q->insert_nav(); ?>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col align-self-start hidden-sm"></div>
			<div class="col-sm-12 col-md-10 col-lg-8 col-xl-6 align-self-center">

				<?php $q->print_board(); ?>
								
			</div>
			<div class="col align-self-end hidden-sm"></div>
		</div>
	</div>
	
</body>
</html>
