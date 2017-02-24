<style>
td {
    text align: left;
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
	<td><?= $order->total_with_shipping?></td>
	<td><a href='https://dashboard.stripe.com/test/payments/<?=$order->stripe_id?>' target='_blank'><i class="icon fa fa-external-link "></i></a></td>

<?php endforeach; ?>