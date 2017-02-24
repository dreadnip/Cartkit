<?php s::remove('cart'); ?>
<?php snippet('header') ?>

<main id="paid" class="main black" role="main">

	<div class="text">
		<h1><?php echo $page->subtitle()->or($page->title()) ?></h1>
		<a class="btn-white" href="<?php echo url('products') ?>">Fancy another product?</a>
	</div>

</main>

<?php snippet('footer') ?>