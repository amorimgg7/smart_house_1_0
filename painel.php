<?php
    session_start();
    if(!isset($_SESSION['id_morador']))
    {
        header("location: index.php");
        exit;
    }
    require_once 'classes/dispositivos.php';
   
    $u = new Usuario;
?><!--Validar sessão aberta, se usuário está logado.-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Bem Vindo</title>
    <meta http-equiv='refresh' content='5'>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<header>

<table><tr><th>Olá, <?php echo $_SESSION['fnome_morador'];?></th><th> <img src="<?php if($_SESSION['foto_morador'] == ""){echo'https://ionicframework.com/docs/demos/api/avatar/avatar.svg';}else{echo $_SESSION['foto_morador'];}?>"></th></tr></table><br><br>

</header>
<a href="01-cadDispositivo.php">Cadastrar dispositivo</a>
<body>
<?php echo '<h1>Usuário: '. $_SESSION['id_morador'].'<br>Casa: '.$_SESSION['id_casa'].'</h1>';?>
    <div class="menu">
        <ul>
            <li><a href="04-portas.php"     >Portas     </a></li>
            <li><a href="04-luzes.php"      >Luzes      </a></li>
            <li><a href="04-higrometros.php">Higrômetro </a></li    >
        </ul>
    </div>
    <div class="visual-2">
    <?php include("classes/conn.php");
        $sql_tipo = "SELECT * FROM dispositivos";
        $resulta = $conn->query($sql_tipo);
        if ($resulta->num_rows > 0){
            while ( $row = $resulta->fetch_assoc()){
                echo '<div class="card-disp-v2">';
                echo '<h4>'.$row['mac_dispositivo'].'</h4>';
                echo '<h4>'.$row['ip_dispositivo'].'</h4>';
                echo '<h4>Modelo: '.$row['modelo_dispositivo']. '</h4>';
                echo '<h4>Grupo: '.$row['grupo_dispositivo']. '</h4>';
                echo '<h4><a href="03-paramDispositivo.php">Editar Parametros</a></h4>';
                echo '<form method="POST"><tr>';
                echo '<p><input type="hidden" name="mac_dispositivo" value="'. $row['mac_dispositivo'] .'" readonly></p>';
                if($row['status_dispositivo1']=="1"){echo '<td><input type="hidden" name="status_dispositivo1" value="2"><input type="submit" class="btnDesligado" value="ON/OFF"></td>';}elseif($row['status_dispositivo1']=="2"){echo '<td><input type="hidden" name="status_dispositivo1" value="1"><input type="submit" class="btnLigado" value="ON/OFF"</td>';};
                if($row['status_dispositivo2']=="1"){echo '<td><input type="hidden" name="status_dispositivo2" value="2"><input type="submit" class="btnDesligado" value="ON/OFF"></td>';}elseif($row['status_dispositivo2']=="2"){echo '<td><input type="hidden" name="status_dispositivo2" value="1"><input type="submit" class="btnLigado" value="ON/OFF"</td>';};
                if($row['status_dispositivo3']=="1"){echo '<td><input type="hidden" name="status_dispositivo3" value="2"><input type="submit" class="btnDesligado" value="ON/OFF"></td>';}elseif($row['status_dispositivo3']=="2"){echo '<td><input type="hidden" name="status_dispositivo3" value="1"><input type="submit" class="btnLigado" value="ON/OFF"</td>';};
                if($row['status_dispositivo4']=="1"){echo '<td><input type="hidden" name="status_dispositivo4" value="2"><input type="submit" class="btnDesligado" value="ON/OFF"></td>';}elseif($row['status_dispositivo4']=="2"){echo '<td><input type="hidden" name="status_dispositivo4" value="1"><input type="submit" class="btnLigado" value="ON/OFF"</td>';};
                echo '</tr></form>';
                echo '</div>';
            }
        }else{
            echo '<a href="cadDispositivos.php"><h1>Adicione dispositivos para automatizar sua casa!</h1></a>';
        }
    ?>
    <?php

    if (isset($_POST['status_dispositivo1']) || isset($_POST['status_dispositivo2']) || isset($_POST['status_dispositivo3']) || isset($_POST['status_dispositivo4']))
    {
        $mac_dispositivo = addslashes($_POST['mac_dispositivo']);
        if($_POST['status_dispositivo1'] == ""){$status_dispositivo1 = addslashes('NULL');}else{$status_dispositivo1 = addslashes($_POST['status_dispositivo1']);}
        if($_POST['status_dispositivo2'] == ""){$status_dispositivo2 = addslashes('NULL');}else{$status_dispositivo2 = addslashes($_POST['status_dispositivo2']);}
        if($_POST['status_dispositivo3'] == ""){$status_dispositivo3 = addslashes('NULL');}else{$status_dispositivo3 = addslashes($_POST['status_dispositivo3']);}
        if($_POST['status_dispositivo4'] == ""){$status_dispositivo4 = addslashes('NULL');}else{$status_dispositivo4 = addslashes($_POST['status_dispositivo4']);}    
        if (!empty($mac_dispositivo) && !empty($status_dispositivo1) && !empty($status_dispositivo2) && !empty($status_dispositivo3) && !empty($status_dispositivo4))//verificar se todos os campos estão preenchidos
        {
            $u->conectar("smart_house_1_0","85.10.205.173","amorimgabriel","27092000");//Conectando ao banco
            if ($msgErro == "")//se mensagem de erro for vazia, faça...
            {
                if($u->alterarStatusPorta($mac_dispositivo,$status_dispositivo1,$status_dispositivo2,$status_dispositivo3,$status_dispositivo4))
                {
                    echo '<script>location.href="painel.php";</script>';
                    ?>
                        <div id="msg-sucesso">Alterado com sucesso</div>
                    <?php
                }
                else
                {
                    ?>
                        <div class="msg-erro">Nada para alterar</div>
                    <?php
                }
            }
            else
            {
                ?>
                    <div class="msg-erro">
                <?php echo "Erro: ".$u->msgErro;?>
                </div>
                <?php
            }
        }else
        {
            echo "Preencha todos os campos!";
        }
    }
?>
</div>

</body>
<footer>
    <h1>Rodapé</h1>
    <a href="AreaPrivada.php"><h1>Voltar</h1></a>
</footer>
</html>