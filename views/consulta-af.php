<!DOCTYPE html>
<html lang="pt-br">
<?php require_once "_head.php" ?>
<body>
	<div class="container-fluid">
		<table class="display" id="example">
			<thead>
				<tr>
					<th class="text-center" scope="col">AF</th>
					<th class="text-center" scope="col">Código</th>
					<th class="text-center" scope="col">Empresa</th>
					<th class="text-center" scope="col">Compra</th>
					<th class="text-center" scope="col">Quantidade</th>
					<th class="text-center" scope="col">Pendente</th>
					<th class="text-center" scope="col">Previsão</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($consulta->resultAF as $value): ?>
				<tr>
					<th class="text-center" scope="row"><?=$value->FIC_AFT_SERIE?>.<?=$value->FIC_AFT_NUM?></th>
					<td class="text-center"><?=$value->FIC_MAT_COD?></td>
					<td><?=$value->FNE_NOME_FANTASIA?></td>
					<td class="text-center"><?=$value->COMPRA?></td>
					<td class="text-center"><?=$consulta->arredonda($value->FIC_QTDE_COTADA)?></td>
					<td class="text-center"><?=$consulta->arredonda($value->FIC_QTDE_PENDENTE)?></td>
					<td class="text-center"><?=$value->PREVISAO?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<?php require_once "_scripts.php" ?>

	<script>
		$(document).ready(function() {
			$('#example').DataTable({
				"paging":   false,
				"ordering": false,
				"info":     false,
				"bFilter": false
			});
		} );
	</script>

</body>
</html>