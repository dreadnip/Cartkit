<style>
table {
	width: 100%;
	border-spacing: 0;
}
th, td {
	text-align: left;
	padding: .5em 1em;
	vertical-align: top;
}
th {
	border-bottom: 1px solid #ddd;
	background: #fff;
}
table p, table a {
	margin-bottom: 0;
}
table a {
	display: inline-block;
}
td {
	border-bottom: 1px solid #ddd;
}
tbody tr:nth-child(odd) {
	background: #f5f5f5;
}
</style>
<?php $orders = get_latest_orders(); ?>
<table>
<thead>
<tr>
<th>Name</th><th>Email</th><th>Charge</th><th></th>
</tr>
</thead>
<tbody>
<?php foreach($orders as $order): ?>
	<tr><td><?= $order->billing_name ?></td>
	<td><?= $order->email; ?></td>
	<td>€<?= floatval($order->total_with_shipping/100)?></td>
	<td><a href='https://dashboard.stripe.com/test/payments/<?=$order->stripe_id?>' target='_blank' title='Open in Stripe dashboard'><i class="icon fa fa-external-link "></i></a></td></tr>

<?php endforeach; ?>
</tbody>
</table>