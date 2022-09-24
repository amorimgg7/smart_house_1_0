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
    public function cadastrar($foto_morador, $fnome_morador, $snome_morador, $cpf_morador, $email_morador, $tel_morador, $dt_nasc_morador, $senha_morador)
    {
        global $pdo;
        //verificar se ja está cadastrado
        $sql = $pdo->prepare("SELECT id_morador FROM moradores WHERE email_morador = :em");
        $sql->bindValue(":em", $email_morador);
        $sql->execute();
        if($sql->rowCount() > 0)
        {
            return false; //ja está cadastrado
        }
        else
        {//não cadastrado, cadastrando agora
            $sql = $pdo->prepare("INSERT INTO moradores(foto_morador, fnome_morador, snome_morador, cpf_morador, email_morador, tel_morador, dt_nasc_morador, senha_morador) VALUES (:ftm, :fnm, :snm, :cf, :em, :tl, :dn, :sn)");
            $sql->bindValue(":ftm", $foto_morador);
            $sql->bindValue(":fnm", $fnome_morador);
            $sql->bindValue(":snm", $snome_morador);
            $sql->bindValue(":cf", $cpf_morador);
            $sql->bindValue(":em", $email_morador);
            $sql->bindValue(":tl", $tel_morador);
            $sql->bindValue(":dn", $dt_nasc_morador);
            $sql->bindValue(":sn", $senha_morador);//md5($senha_morador);
            $sql->execute();
            //echo '<script>location.href="login.php";</script>';
            return true;
        }
        //se não estiver cadastrado, vamos fazer o cadastro
    }
    public function logar($cpf_morador, $senha_morador)
    {
        global $pdo;
        //verificar se o email e senha estão cadastrados
        $sql = $pdo->prepare("SELECT id_morador,foto_morador,fnome_morador,snome_morador FROM moradores WHERE cpf_morador = :cpf AND senha_morador = :sn");
        $sql->bindValue(":cpf", $cpf_morador);
        $sql->bindValue(":sn", $senha_morador);//md5($senha_morador);
        $sql->execute();
        if($sql->rowCount() > 0)
        {
            //entrar no sistema(sessão)
            $morador = $sql->fetch();
            session_start();
            $_SESSION['id_morador'] = $morador['id_morador'];
            $_SESSION['foto_morador'] = $morador['foto_morador'];
            $_SESSION['fnome_morador'] = $morador['fnome_morador'];
            $_SESSION['snome_morador'] = $morador['snome_morador'];
            echo '<script>location.href="AreaPrivada.php";</script>';
            return true;
        }
        else
        {
            //não foi possivel logar
            return false;
        }
    }
}


?>