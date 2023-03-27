<?php
   include "../../session.php";
   if($liderssp == 0 AND !$admin and $ssp == 0){
	header('Location: ../../permissao.php');
   }
   include "../../header.php";
?>
<?php
$query_select = "SELECT * FROM painel_usuarios where usr_usuario LIKE '".$user_check."'";
$select = mysqli_query($conn, $query_select);
$array = mysqli_fetch_array($select,MYSQLI_ASSOC);
$email = $array['email'];
$sexo = $array['sexo'];
$sobre = $array['sobre'];
$facebook = $array['facebook'];

$usuarioFORM = $user_check;
$emailFORM = $email;
$sobreFORM = $_POST['sobre'];
$facebookFORM = $_POST['facebook'];
$sexoFORM = $_POST['sexo'];

$query_select = "SELECT * FROM painel_ssp";
$select = mysqli_query($conn, $query_select);
$array = mysqli_fetch_array($select,MYSQLI_ASSOC);
$liderguias = $array['usr_lider'];
$avisofixo = $array['usr_avisofixo'];

if($_POST) {
$query = "update painel_usuarios set sobre = '".$sobreFORM."', facebook = '".$facebookFORM."', sexo = '".$sexoFORM."', email = '".$email."', usr_usuario = '".$usuarioFORM."' WHERE usr_usuario = '".$user_check."'";
$insert = mysqli_query($conn, $query);
if($insert){
$mensagem = "
<div class='alert alert-success alert-dismissible fade show' role='alert'>
<strong>Sucesso!</strong> A alteração foi realizada com sucesso!
<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
<span aria-hidden='true'>&times;</span>
</button>
</div>";
}else{
$mensagem = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
<strong>Erro!</strong> A alteração não foi realizada com sucesso!
<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
<span aria-hidden='true'>&times;</span>
</button>
</div>";
}
}
?>

<div class="page-inner no-page-title">
<div id="main-wrapper">
<div class="content-header">
<nav aria-label="breadcrumb">
<ol class="breadcrumb breadcrumb-style-1">
<li class="breadcrumb-item"><a href="#">Recursos Humanos</a></li>
<li class="breadcrumb-item bold" aria-current="page">Setor de Supervisão de Promoções</li>
</ol>
</nav>
<h1 class="page-title">Setor de Supervisão de Promoções</h1>
<div class="page-options">
<a href="<?php echo $link;?>/paginas/relatorios/promover"><button type="button" class="btn btn-info"><i class="fa fa-plus"></i> Registrar Promoção</button></a>
<button type="button" data-toggle="modal" data-target="#exampleModal126623" class="btn btn-outline-dark">Arquivos</button>
<?php if($admin or $liderssp == 1){?>
<a href="<?php echo $link;?>/paginas/ssp/area-lider/index"><button type="button" class="btn btn-outline-dark">Painel de Gestão</button></a>
<?php } ?>

</div>
</div>

<div class="row">
<div class="col-lg">
<div class="card">
<div class="card-body">
<div class="row">
<div class="col">
<div class="alert alert-light" role="alert">
<h6 class="alert-heading"><i class="fa fa-newspaper-o"></i><strong> Quadro de Avisos:</strong></h6>
<p><?php echo $avisofixo; ?></p>
</div>
</div>

<div class="col-4">
<div class="invoice-header">
<?php
$totalpromocoes = 0;
$sql = "SELECT * FROM painel_ultimasatividades_perfil where usr_responsavel = '".$login_session."' and usr_aba = 'Promoções'";
$sql = $conn->query($sql);
$totalpromocoes = $sql->num_rows;
?>
<?php
$promocoesaprovadas = 0;
$sql = "SELECT * FROM painel_ultimasatividades_perfil where usr_responsavel = '".$login_session."'and usr_aba = 'Promoções' and usr_status = 'Ativo'";
$sql = $conn->query($sql);
$promocoesaprovadas = $sql->num_rows;
?>
<?php
$promocoesemanalise = 0;
$sql = "SELECT * FROM painel_ultimasatividades_perfil where usr_responsavel = '".$login_session."'and usr_aba = 'Promoções' and usr_status = 'Pendente'";
$sql = $conn->query($sql);
$promocoesemanalise = $sql->num_rows;
?>
<h4 class="float-left">Suas Promoções: <strong><?php echo $totalpromocoes; ?></strong></h4><br><br>
<h4 class="float-left">Aprovadas: <strong><?php echo $promocoesaprovadas; ?></strong></h4><br><br>
<h4 class="float-left">Pendentes: <strong><?php echo $promocoesemanalise; ?></strong></h4><br>

</div>
</div>
</div>
<div class="row">
<div class="col">
<hr>
<table class="table m-t-xxl">
<thead>
<tr>
<th scope="col"></th>
<th scope="col">Funcionário</th>
<th scope="col">Patente</th>
<th scope="col">Status</th>
<th scope="col">Data</th>
</tr>
</thead>

<?php
/*$pagina=$_GET['pagina'];
if (!$pagina) {
$pc = "1";
} else {
$pc = $pagina;
}


$query = mysqli_query($conn, "SELECT * FROM painel_ultimasatividades_perfil where usr_responsavel = '".$login_session."' and usr_aba = 'Promoções' and usr_status = 'Ativo' ORDER BY usr_id DESC") or die(mysql_error());
while($array = mysqli_fetch_object($query)) {*/

$busca = "SELECT * FROM painel_ultimasatividades_perfil";
$total_reg = "50"; // número de registros por página

$pagina=$_GET['pagina'];
if (!$pagina) {
$pc = "1";
} else {
$pc = $pagina;
}

$inicio = $pc - 1;
$inicio = $inicio * $total_reg;

$limite = mysqli_query($conn, "$busca where usr_responsavel = '".$login_session."' and usr_aba = 'Promoções' ORDER BY usr_id DESC LIMIT $inicio,$total_reg");
$todos = mysqli_query($conn, "$busca");

$tr = mysqli_num_rows($todos); // verifica o número total de registros
$tp = $tr / $total_reg; // verifica o número total de páginas


?>

<?php
while($tr = mysqli_fetch_assoc($limite)){
?>
<tbody>
<tr>
<th scope="row">
<a href="<?php echo $link;?>/paginas/ssp/area-lider/detalhes-promocao?id=<?php echo $tr['usr_id']; ?>"><button type="button" class="btn btn-info"><i data-toggle="tooltip" data-placement="left" title="Informações" class="fa fa-circle-info"></i></button></a>
<td><a style="color: black;" href="<?php echo $link;?>/paginas/perfil?nome=<?php echo $tr['usr_usuario']; ?>"><?php echo $tr['usr_usuario']; ?></a></td>
<td><?php echo $tr['usr_titulo'];?></td>
<td><button
class="btn btn-<?php if($array->usr_status == "Pendente"){ echo "warning";}else if($array->usr_status == "Ativo"){echo "success";}else{echo "danger";}; ?>"><i class="fas fa-<?php if($array->usr_status == "Pendente"){ echo "circle-question";}else if($array->usr_status == "Ativo"){echo "circle-check";}else{echo "circle-xmark";}; ?>"></i></button></td>
<td><?php echo $tr['usr_data']; ?></td>
</tr>

<?php
}
?>
</tbody>
</table>
<?php
$anterior = $pc -1;
$proximo = $pc +1;
echo "<nav><ul class='pagination pagination-circle'>";
if ($pc>1) {
echo "<li class='page-item'><a style='color: black;' class='page-link' href='index.php?pagina=$anterior'><i style='color: black;' class='fa fa-arrow-left'></i> Anterior</a></li>";
}
echo "";
if ($pc<$tp) {
echo "<li class='page-item'><a style='color: black;' class='page-link' href='index.php?pagina=$proximo'>Próximo <i style='color: black;' class='fa fa-arrow-right'></i></a></li>";
}
echo "</ul></nav>";
?>

<?php
if (mysqli_num_rows($limite) == 0) {
echo "Não há relatórios no momento!"; 
}
?>

</div>
</div>

<div class="modal fade" id="exampleModal126623" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalCenterTitle">Arquivos Internos</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body"> 
<?php
$query = mysqli_query($conn, "SELECT * FROM painel_paginas where abanome = 'SsP' ORDER BY usr_id ASC") or die(mysql_error());
while($array = mysqli_fetch_object($query)) {

echo "<a href='". $link."/paginas/ssp/arquivos?id=".$array->usr_id."'><button class='btn btn-info btn-block'>".$array->usr_titulo."</button></a><br>";
}
?>
</div>
</div>
</div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalCenterTitle">Arquivos Internos</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<?php
$query = mysqli_query($conn, "SELECT * FROM painel_paginas where abanome = 'SsP' ORDER BY usr_id ASC") or die(mysql_error());
while($array = mysqli_fetch_object($query)) {
echo "<a href='". $link."/paginas/ssp/arquivos?id=".$array->usr_id."'><button class='btn btn-info btn-block'>".$array->usr_titulo."</button><br>";
}
?>
</div>
</div>
</div>


</div>
</div>
</div>
</div>




<?php include '../../footer.php';?>
