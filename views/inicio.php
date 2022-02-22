<!DOCTYPE html>
<html lang="pt-br">
<?php require_once "_head.php" ?>
<style>
	
</style>
<body>
	<div class="container-fluid">
	
		<!-- Modal -->
		<div id="1" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<div id='injetada' style="width: 900px; height: 400px;"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Menu -->
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a class="btn btn-primary" href="index?grupo=001" role="button">MEDICAMENTOS</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-primary" href="index?grupo=002" role="button">HOSPITALAR</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-primary" href="index?grupo=010" role="button">HIGIENE</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-primary" href="index?grupo=006" role="button">EXPEDIENTE</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-primary" href="index?grupo=009" role="button">MANUTENÇÃO</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-primary" href="index?grupo=008" role="button">ALIMENTÍCIOS</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-primary" href="index?grupo=003" role="button">LABORATÓRIO</a>
			</div>
		</div>

		<br>
		
		<!-- Tabela -->
		<table class="display" id="example">
			<thead>
				<tr>
					<th class="text-center" scope="col" data-tippy-content="Código do material">Código</th>
					<th scope="col" data-tippy-content="Descrição do material">Descrição</th>
					<th class="text-center" scope="col" data-tippy-content="Grau de importância">Curva</th>
					<th class="text-center" scope="col" data-tippy-content="Estoque atual">Estoque</th>
					<th class="text-center" scope="col" data-tippy-content="Consumo médio mensal">CMM</th>
					<th class="text-center" scope="col" data-tippy-content="Consumo nos últimos 7 dias">7D</th>
					<th class="text-center" scope="col" data-tippy-content="Consumo intenso">Status</th>
					<th class="text-center" scope="col" data-tippy-content="Quantidade suficiente em dias">Suficiência</th>
					<th class="text-center" scope="col" data-tippy-content="Consumo mês atual"><?=$consulta->result_mes->MESATU?></th>
					<th class="text-center" scope="col" data-tippy-content="Consumo mês passado"><?=$consulta->result_mes->MESANT?></th>
					<th class="text-center" scope="col" data-tippy-content="Consumo mês anterior"><?=$consulta->result_mes->MESPAS?></th>
					<th class="text-center" scope="col" data-tippy-content="AF autorizada">AF</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($consulta->result as $value): ?>
				<tr>
					<th class="text-center" scope="row"><?=$value->CODMAT?></th>
					<td><?=$value->DESCRI?></td>
					<td class="text-center"><?=$value->CURVAC?></td>
					<td class="text-center"><?=$consulta->arredonda($value->ESTOQU)?></td>
					<td class="text-center"><?=$consulta->formatarNumero($value->CONSUM)?></td>
					<td class="text-center"><?=$consulta->arredonda($value->SETEDI)?></td>
					<td class="text-center"><?=$value->RESULTADO?></td>
					<td class="text-center"><?=$consulta->arredonda($value->SUFICI)?></td>
					<td class="text-center"><?=$consulta->arredonda($value->MESATU)?></td>
					<td class="text-center"><?=$consulta->arredonda($value->MESPAS)?></td>
					<td class="text-center"><?=$consulta->arredonda($value->MESANT)?></td>
					<td class="text-center"><?=$value->CODIGO?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<?php require_once "_scripts.php" ?>
	
	<!-- Configurações da tabela -->
	<script>
		$(document).ready(function() {
			$('#example').DataTable({
				"paging":   false,
				fixedHeader: true
			});
			new $.fn.dataTable.FixedHeader( table );
		} );
	</script>
	<!-- Configurações popper com info nas colunas da tabela -->
	<script>		
		tippy('[data-tippy-content]', {
			placement: 'bottom',
		});      
    </script>
	<!-- Configurações do modal -->
	<script type="text/javascript">
		function loadModal(teste){
			// recebe constante php com o endereço
			let url = "<?php ENDERECO ?>"			
			// consulta af autorizada
			$('#injetada').html(`<iframe src="${url}consulta-af?codigo=${teste}" width="850" height="400"></iframe>`);
		}
	</script>

</body>
</html>