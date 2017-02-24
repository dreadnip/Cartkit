<?php snippet('header') ?>

<?php if(count($cart) == 0): ?>

	<main id="cart" class="main black" role="main">
		<div class="text">
			<h1>Your cart is empty.</h1>
			<a class="btn-white" href="<?php echo url('products') ?>">Go to the products.</a>
		</div>
	</main>

<?php else: ?>
	<main id="cart" class="main" role="main">
		<div class="text">
			<h1><?php echo $page->title()->html() ?></h1>
			<table cellpadding="6" rules="GROUPS" frame="BOX">
				<thead>
					<tr>
						<th>Product</th>
						<th>Amount</th>
						<th></th>
						<th style="text-align: right;">Price</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=0; $count = 0; $total = 0; $order_sum = ''; ?>
					<?php foreach($cart as $id => $quantity): ?>
						<?php if($product = $products->findByURI($id)): ?>
							<?php $i++; ?>
							<?php $count += $quantity ?>
							<?php $order_sum .=$product->title().', '; ?>
							<tr>
								<td>
									<a href="<?php echo $product->url() ?>">
										<?php echo kirbytext($product->title(), false) ?>
									</a>
								</td>
								<td>
									<?php echo $quantity ?> x
									<a class="btn add" href="<?php echo url('cart') ?>?action=add&amp;id=<?php echo $product->uid() ?>">+</a>
									<?php if ($quantity > 1): ?>
										<a class="btn remove" href="<?php echo url('cart') ?>?action=remove&amp;id=<?php echo $product->uid() ?>">-</a>
									<?php endif ?>
									<?php $prodtotal = floatval($product->price()->value)*$quantity ?>
								</td>
								<td><a class="btn-red delete" href="<?php echo url('cart') ?>?action=delete&amp;id=<?php echo $product->uid() ?>">Remove</a></td>
								<td style="text-align: right;"><?php echo $site->currency_symbol() ?><?php printf('%0.2f', $prodtotal) ?></td>
							</tr>
							<?php $total += $prodtotal ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td align="left" colspan="3">Sub-total</td>
						<td style="text-align: right;"><?php echo $site->currency_symbol() ?><?php printf('%0.2f', $total) ?></td>
					</tr>
					<tr>
						<?php $postage = cart_postage($total) ?>
						<td align="left" colspan="3">Shipping cost</td>
						<td style="text-align: right;"><?php echo $site->currency_symbol() ?><?php printf('%0.2f', $postage) ?></td>
					</tr>
					<tr>
						<th align="left" colspan="3">Total</th>
						<th style="text-align: right;"><?php echo $site->currency_symbol() ?><?php printf('%0.2f', $total+$postage) ?></th>
					</tr>
				</tfoot>
			</table>
			<div>
				<button class="btn btn-paypal pay-button" data-description="<?= $order_sum ?>" data-amount="<?= ($total+$postage)*100 ?>">Pay now</button>
				or <a class="btn" href="<?php echo url('products') ?>">Continue shopping</a>
			</div>
		</form>
	</div>
</main>

<?php endif; ?>
<!-- Include Stripe JS file only in this template to reduce load on other pages-->
<script src="https://checkout.stripe.com/checkout.js"></script>
<?php snippet('footer') ?>