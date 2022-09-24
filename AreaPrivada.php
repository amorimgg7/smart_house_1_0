<?php
    session_start();
    if(!isset($_SESSION['id_morador']))
    {
        header("location: index.php");
        exit;
    }
    //header("location: painel.php");
    require_once 'classes/casas.php';
    include("classes/conn.php");
    
    $u = new Usuario;
?><!--Validar sessão aberta, se usuário está logado.-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Bem Vindo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv='refresh' content='10'>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>
<header>
        <table><tr><th>Olá, <?php echo $_SESSION['fnome_morador'];?></th><th> <img src="<?php if($_SESSION['foto_morador'] == ""){echo'https://ionicframework.com/docs/demos/api/avatar/avatar.svg';}else{echo $_SESSION['foto_morador'];}?>"></th></tr></table><br><br>
        
    </header>
    <a href="01-cadCasa.php">Cadastrar casa</a>
    <h1><a href="testes.php">Visão 2.0</a></h1>
<body>
<div class="visual-2">
    <?php   
    //$sql_tipo = "SELECT * FROM casa_morador WHERE id_morador_casa =". $_SESSION['id_morador'];
        $sql_tipo = "SELECT * FROM casa_morador WHERE id_morador = ".$_SESSION['id_morador'];
        $resulta = $conn->query($sql_tipo);
        if ($resulta->num_rows > 0){
            if ($resulta->num_rows == 1){
                while ( $row = $resulta->fetch_assoc()){
                    echo '<div class="card-casa-v1">';
                    echo '<form method="POST">';
                    echo 'ID da casa: '.$row['id_casa'].'<br>';
                    echo '<input type="hidden" name="id_casa" value="'.$row['id_casa'].'">';
                    //directCasa();
                    global $id_casa;
                    //$id_casa = $row['id_casa'];
                    $_SESSION['id_casa'] = $row['id_casa'];
                    //$u->ver_dispositivo_casa($id_casa);
                    //$u->chamaCasa($id_casa);
                    echo '<div class="controleCasa-v1"><a href="painel.php" onClick="directCasa'.$row['id_casa'].'();">Controle</a></div>';
                    echo '</form>';
                    echo '</div>';
                    
                }
            }else
            {
                while ($row = $resulta->fetch_assoc()){
                    echo '<div class="card-casa-v2">';
                    echo '<form method="POST">';
                    echo 'ID da casa: '.$row['id_casa'].'<br>';
                    echo '<a href="01-cadDispositivo.php">Cadastrar dispositivo</a>';
                    echo '<input type="hidden" name="id_casa" value="'.$row['id_casa'].'">';
                    //directCasa();
                    global $id_casa;
                    //$id_casa = $row['id_casa'];
                    $_SESSION['id_casa'] = $row['id_casa'];
                    //$u->ver_dispositivo_casa($id_casa);
                    //$u->chamaCasa($id_casa);

                    echo '<div class="controleCasa-v2">';
                

                    $sqlDisp = "SELECT * FROM dispositivos WHERE id_casa = ".$_SESSION['id_casa'];
                    $resultaDisp = $conn->query($sqlDisp);
                    if ($resulta->num_rows > 0){
                        while ( $rowDisp = $resultaDisp->fetch_assoc()){
                            echo '<div class="card-disp-v2" style="font-size:15pt;">';
                            echo '<form method="POST"><tr>';
                            echo 'MAC: '.$rowDisp['mac_dispositivo'].'<br>';
                            echo 'IP: '.$rowDisp['ip_dispositivo'].'<br>';
                            echo 'Modelo: '.$rowDisp['modelo_dispositivo']. '<br>';
                            echo 'Grupo: '.$rowDisp['grupo_dispositivo']. '<br><br>';
                            echo '<a href="03-paramDispositivo.php">Editar Parametros</a>';
                            
                            echo '<p><input type="hidden" name="ip_dispositivo" value="'. $rowDisp['ip_dispositivo'] .'" readonly></p>';
                            if($rowDisp['status_dispositivo']=="D"){echo '<td><input type="hidden" name="status_dispositivo" value="L"><input type="submit" class="btnDesligado" value="ON/OFF"></td>';}elseif($rowDisp['status_dispositivo']=="L"){echo '<td><input type="hidden" name="status_dispositivo" value="D"><input type="submit" id="status" class="btnLigado" value="ON/OFF"</td>';};
                            echo '</tr></form>';
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                    echo '</form>';
                    echo '</div>';
                }

            }
        }else{
            echo '<a href="01-cadCasa.php"><h1>Adicione ou vincule a sua casa para automatizar!</h1></a>';
        }  
    ?>
    <?php 
    if (isset($_POST['status_dispositivo']))
    {
        $ip_dispositivo = addslashes($_POST['ip_dispositivo']);
        $status_dispositivo = addslashes($_POST['status_dispositivo']);    
        if (!empty($ip_dispositivo) && !empty($status_dispositivo))//verificar se todos os campos estão preenchidos
        {
            $u->conectar("smart_house_1_0","85.10.205.173","amorimgabriel","27092000");//Conectando ao banco
            if ($msgErro == "")//se mensagem de erro for vazia, faça...
            {
                if($u->alterarStatusPorta($ip_dispositivo,$status_dispositivo))
                {
                    echo '<script>location.href="AreaPrivada.php";</script>';
                    ?>
                        <div id="msg-sucesso">Alterado com sucesso</div>
                    <?php
                    //echo '<script>onClick="window.location.reload()</script>';
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
    <?php echo "<h1><a href='loggout.php'>Sair</a></h1>"; ?>
</footer>
</html>