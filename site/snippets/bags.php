<?php $cart = get_cart(); ?>
<?php $count = cart_count($cart); ?>

<?php if ($count > 0): ?>
<div class="bags cf">
	<p>You have <strong><?php echo $count ?> article<?php if ($count > 1) echo 's' ?></strong> in your cart.</p>
	<a class="btn-white" href="<?php echo url('cart') ?>">Go to your cart.</a>
</div>
<?php endif ?>