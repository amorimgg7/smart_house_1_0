<?php

class Usuario
{
    private $pdo;
    public $msgErro = "";
    public function conectar($nome, $host, $usuario, $senha)
    {
        global $pdo; 
        global $msgErro;
        try {
            $pdo = new PDO("mysql:dbname=".$nome.";host=".$host, $usuario, $senha);
        } catch (PDOException $e) {
            $msgErro = $e->getMessage();
        }
    }
    public function vincule_casa_morador($id_morador, $cep_casa, $senha_casa){
        global $pdo;
        $sql = $pdo->prepare("SELECT id_casa FROM casas WHERE cep_casa = :cep");
        $sql->bindValue("cep", $cep_casa);
        $sql->execute();
        if($sql->rowCount() > 0){//cep cadastrado
            $sql = $pdo->prepare("SELECT id_casa FROM casas WHERE cep_casa = :cep and senha_casa = :snc");
            $sql->bindValue("cep", $cep_casa);
            $sql->bindValue("snc", $senha_casa);
            $sql->execute();
            if($sql->rowCount() > 0){//senha correta
                $morador = $sql->fetch();
                $id_casa = $morador['id_casa'];
                echo 'Casa: '.$id_casa;
                echo 'Morador: '.$id_morador;
                $sql = $pdo->prepare("SELECT id_casa FROM casa_morador WHERE id_casa = :idc AND id_morador = :idm");
                $sql->bindValue("idc", $id_casa);
                $sql->bindValue("idm", $id_morador);
                $sql->execute();
                if($sql->rowCount() > 0){
                    ?>
                        <div class="msg-erro">Usuário Já vinculado a esta casa!</div>
                    <?php
                }else{//não vinculado
                    
                    $sql = $pdo->prepare("INSERT INTO casa_morador(id_casa,id_morador) VALUES(:idc, :idm)");
                    $sql->bindValue("idc", $id_casa);
                    $sql->bindValue("idm", $id_morador);
                    $sql->execute();
                    ?>
                        <div class="msg-sucesso">Vinculo realizado!</div>
                    <?php
                    return true;
                }
            }else{//senha errada
                ?>
                    <div class="msg-erro">Senha errada!</div>
                <?php
            }

        }else{//cep não cadastrado
            ?><link rel="stylesheet" href="css/estilo.css">
                <div id="msg-erro">CEP Não encontrado!</div>
                <script>setTimeout("location.href = '02-cadCasas.php';", 5000);</script>
            <?php
        }
    }
    public function ver_casa_morador($id_morador, $id_casa){
        global $pdo;
        //buscar casas vinculadas ao usuário logado
        $sql = $pdo->prepare("SELECT id_morador,id_casa FROM morador");
        $sql->bindValue(":sn", );//md5($senha_morador);
        $sql->execute();
    }
}


?>