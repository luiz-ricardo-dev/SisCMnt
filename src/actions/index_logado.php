<?php
require_once '../../src/database/conectaBD.php';

session_start();

if (empty($_SESSION)) {
  // Significa que as variáveis de SESSAO não foram definidas.
  // Não poderia acessar aqui.
  header("Location: ../../index.php?msgErro=Você precisa se autenticar no sistema.");
  die();
}

/*
echo "Estou logado";
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
die();
*/
$anuncios = array();

if (!empty($_GET['meus_historicos']) && $_GET['meus_historicos'] == 1) {
  // Obter somente os anúncios cadastrados pelo(a) usuário(a) logado(a).
  $sql = "SELECT * FROM historico WHERE identidade_usuario = :identidade ORDER BY id_historico ASC";
  $dados = array(':identidade' => $_SESSION['identidade']);

  try {
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute($dados)) {
      // Execução da SQL Ok!!
      $anuncios = $stmt->fetchAll();
    }
    else {
      die("Falha ao executar a SQL.. #1");
    }
  } catch (PDOException $e) {
    die($e->getMessage());
  }
} else {
  $sql = "SELECT * FROM historico ORDER BY id_historico ASC";
  try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute()) {
      // Execução da SQL Ok!!
      $anuncios = $stmt->fetchAll();

      /*
      echo '<pre>';
      print_r($anuncios);
      echo '</pre>';
      die();
      */
    }
    else {
      die("Falha ao executar a SQL.. #2");
    }

  } catch (PDOException $e) {
    die($e->getMessage());
  }
}



?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Página Inicial - Ambiente Logado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <?php if (!empty($_GET['msgErro'])) { ?>
        <div class="alert alert-warning" role="alert">
          <?php echo $_GET['msgErro']; ?>
        </div>
      <?php } ?>

      <?php if (!empty($_GET['msgSucesso'])) { ?>
        <div class="alert alert-success" role="alert">
          <?php echo $_GET['msgSucesso']; ?>
        </div>
      <?php } ?>
    </div>

    <div class="container">
      <div class="col-md-11">
        <h2 class="title">Olá <i><?php echo $_SESSION['nomeGuerra']; ?></i>, seja bem-vindo(a)!</h2>
      </div>
    </div>
    <div class="container">
      <a href="../pages/user/cad_historico.php" class="btn btn-primary">Novo Histórico</a>
      <a href="index_logado.php?meus_historicos=1" class="btn btn-success">Meus Histórico</a>
      <a href="index_logado.php?meus_historicos=0" class="btn btn-info">Todos Históricos</a>
      <a href="logout.php" class="btn btn-dark">Sair</a>
    </div>

    <?php if (!empty($anuncios)) { ?>
      <!-- Aqui que será montada a tabela com a relação de anúncios!! -->
      <div class="container">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nº PATRIMÔNIO</th>
              <th scope="col">NOMECLATURA</th>
              <th scope="col">GRUPO</th>
              <th scope="col">DATA DA INDISP.</th>
              <th scope="col">ORGÃO DE MANUTENÇÃO</th>
              <th scope="col">EMPRESA DE MANUTENÇÃO</th>
              <th scope="col">OM DE MANUTENÇÃO</th>
              <th scope="col">SITUAÇÃO DA DISPONIBILIDADE</th>
              <th scope="col">REALIZAÇÃO DO SERVIÇO DE MANUTENÇÃO</th>
              <th scope="col">PRIORIDADE DA MANUTENÇÃO</th>
              <th scope="col">CONDIÇÕES DE USO EM MANUTENÇÃO</th>
              <th scope="col">OBTENÇÃO DE SUPRIMENTO PARA MANUTENÇÃO</th>
              <th scope="col">OBTENÇÃO DE SUPRIMENTO PARA MANUTENÇÃO</th>
              <th scope="col">DESCRIÇÃO DO PROBLEMA</th>
              <th scope="col">SOLUÇÃO</th>
              <th scope="col">DATA DO TÉRMINO</th>
              <th scope="col">VALOR DO SERVIÇO</th>
              <th scope="col">VALOR DO MATERIAL</th>
              <th scope="col">VALOR DO MATERIAL</th>
              <th scope="col">VALOR DO MATERIAL</th>
              <th scope="col">ORDEM DE SERVIÇO</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($anuncios as $a) { ?>
              <tr>
                <th scope="row"><?php echo $a['id']; ?></th>
                <td>
                  <?php
                    if ($a['fase'] == 'A') {
                      echo "Adulto";
                    } else {
                      echo "Filhote";
                    }
                  ?>
                </td>
                <td><?php echo $a['tipo'] == 'G' ? "Gato" : "Cachorro"; ?></td>
                <td><?php echo $a['pelagem_cor']; ?></td>
                <td><?php echo $a['raca']; ?></td>
                <td><?php echo $a['sexo'] == 'M' ? "Macho" : "Fêmea"; ?></td>
                <td>
                  <?php if ($a['email_usuario'] == $_SESSION['email']) { ?>
                    <a href="alt_anuncio.php?id_anuncio=<?php echo $a['id']; ?>" class="btn btn-warning">Alterar</a>
                    <a href="del_anuncio.php?id_anuncio=<?php echo $a['id']; ?>" class="btn btn-danger">Excluir</a>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } ?>

  </body>
</html>