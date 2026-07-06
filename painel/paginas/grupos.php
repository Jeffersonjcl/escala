<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'grupos';

//verificar se ele tem a permissão de estar nessa página
if(@$grupos == 'ocultar'){
    echo "<script>window.location='index.php'</script>";
    exit();
}
?>

<div class="breadcrumb-header justify-content-between">
 	<div class="left-content mt-2">
<a class="btn ripple btn-success text-white" onclick="inserir()" type="button"><i class="fa fa-file-circle-plus me-1"></i> Adicionar <?php echo ucfirst($pag); ?></a>


</div>
</div>

<div class="row row-sm">
<div class="col-lg-12">
<div class="card custom-card">
<div class="card-body" id="listar">

</div>
</div>
</div>
</div>



<div class="bs-example widget-shadow" style="padding:15px" id="listar">
	
</div>


<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form">
			<div class="modal-body">

					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o Nome" required>    
							</div> 	
						</div>
						<div class="col-md-3" style="margin-top: -4px;">
							<button type="submit" class="btn btn-success">Salvar<i class="fa-solid fa-check ms-2"></i></button>
						
						</div>
					</div>

					
						<input type="hidden" name="id" id="id">

					<br>
					<small><div id="mensagem" align="center"></div></small>
				</div>
			</form>

							
		</div>
	</div>
</div>



<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
			
			$('#modalForm').on('shown.bs.modal', function () {
			    $('#nome').focus();
			});
</script>
