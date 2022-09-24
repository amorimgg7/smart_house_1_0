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
    public function cadCasa($end_casa, $cep_casa, $bairro_casa, $rua_casa, $numero_casa, $complemento_casa, $senha_casa)
    {
        global $pdo;
        //verificar se ja está cadastrado
        $sql = $pdo->prepare("SELECT id_casa FROM casas WHERE cep_casa = :cp");
        $sql->bindValue(":cp", $cep_casa);
        $sql->execute();
        
        if($sql->rowCount() > 0)
        {
            return false; //ja está cadastrado
        }
        else
        {//não cadastrado, cadastrando agora
            $sql = $pdo->prepare("INSERT INTO casas(endereco_casa, cep_casa, bairro_casa, rua_casa, numero_casa, complemento_casa, senha_casa) VALUES (:en, :cp, :br, :ra, :nm, :cm, :sn)");
            $sql->bindValue(":en", $bairro_casa);
            $sql->bindValue(":cp", $cep_casa);
            $sql->bindValue(":br", $bairro_casa);
            $sql->bindValue(":ra", $rua_casa);
            $sql->bindValue(":nm", $numero_casa);
            $sql->bindValue(":cm", $complemento_casa);
            $sql->bindValue(":sn", $senha_casa);
            $sql->execute();
            //echo '<script>location.href="AreaPrivada.php";</script>';
            return true;
        }
        //se não estiver cadastrado, vamos fazer o cadastro

    }
    public function alterarStatusPorta($ip_dispositivo,$status_dispositivo)
    {
        global $pdo;
        //verificar se ja está cadastrado
        $sql = $pdo->prepare("SELECT id_dispositivo FROM dispositivos WHERE ip_dispositivo = :ip AND status_dispositivo = :s");
        $sql->bindValue(":ip", $ip_dispositivo);
        $sql->bindValue(":s", $status_dispositivo);//Verificar se status é igual
        $sql->execute();
        if($sql->rowCount() > 0)
        {
            return false;
        }
        else
        {//não cadastrado, cadastrando agora
            $sql = $pdo->prepare("UPDATE dispositivos SET status_dispositivo = (:s) WHERE ip_dispositivo = (:ip)");
            //UPDATE dispositivos SET status_dispositivo = 'L';
            $sql->bindValue(":ip", $ip_dispositivo);
            $sql->bindValue(":s", $status_dispositivo);
            $sql->execute();
            //echo '<script>location.href="01-portas.php";</script>';
            return true;
        }


        //se não estiver cadastrado, vamos fazer o cadastro

    }
    public function chamaCasa($id_casa){
        global $pdo;
        $sql = $pdo->prepare("SELECT id_casa FROM casas WHERE id_casa =idc");
        $sql->bindValue(":idc", $id_casa);//Verificar se status é igual
        $sql->execute();
        $id_casa_click = $sql->fetch();
        session_start();
        $_SESSION['id_casa'] = $id_casa_click['id_casa'];
        if($sql->rowCount() > 0){
            //$id_casa = $sql->fetch();
            //session_start();
            //$_SESSION['id_morador'] = $morador['id_morador'];
            //$_SESSION['id_casa'] = $morador['id_casa'];
           // $_SESSION['fnome_morador'] = $morador['fnome_morador'];
            //$_SESSION['snome_morador'] = $morador['snome_morador'];
            //return true;
        }else{
            //return false;
        }
    }
}
?>