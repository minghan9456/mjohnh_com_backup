<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="/space/css/rate.css">
<title>Exchange Rate</title>
<head>
<body>
	<h1>USD TO <?=($realm)?></h1>
	<table class="rate-table">
		<tbody>
		<tr>
			<th>realm</th>
			<th>rate</th>
			<th>time</th>
		</tr>
		<?php foreach ($rateData as $rd): ?>
			<tr>
				<td><?=($rd['realm'])?></td>
				<td><?=($rd['exchange_rate'])?></td>
				<td><?=($rd['created_datatime'])?></td>
			</tr>
    <?php endforeach; ?>
		</tbody>
	</table>
</body>
</html>
