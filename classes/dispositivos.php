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
    public function cadastrarDispositivo($ip_dispositivo, $mac_dispositivo, $grupo_dispositivo, $modelo_dispositivo, $comodo_dispositivo, $casa_dispositivo)
    {
        global $pdo;
        //verificar se ja está cadastrado
        $sql = $pdo->prepare("SELECT id_dispositivo FROM dispositivos WHERE mac_dispositivo = :mc");
        $sql->bindValue(":mc", $mac_dispositivo);
        $sql->execute();
        if($sql->rowCount() > 0)
        {
            echo 'Ja está cadastrado!';
            //return false; //ja está cadastrado
        }
        else
        {//não cadastrado, cadastrando agora
            $sql = $pdo->prepare("INSERT INTO dispositivos(ip_dispositivo, mac_dispositivo, grupo_dispositivo, modelo_dispositivo, comodo_dispositivo, status_dispositivo1, status_dispositivo2, status_dispositivo3, status_dispositivo4) VALUES (:ip, :mc, :gp, :md, :cd, :st1, :st2, :st3, :st4)");
            $sql->bindValue(":ip", $ip_dispositivo);
            $sql->bindValue(":mc", $mac_dispositivo);
            $sql->bindValue(":gp", $grupo_dispositivo);
            $sql->bindValue(":md", $modelo_dispositivo);
            $sql->bindValue(":cd", $comodo_dispositivo);
            if($modelo_dispositivo == "Lv1" || $modelo_dispositivo == "Pv1"){
                $sql->bindValue(":st1", "1");
                $sql->bindValue(":st2", "NULL");
                $sql->bindValue(":st3", "NULL");
                $sql->bindValue(":st4", "NULL");
            }
            if($modelo_dispositivo == "Lv2" || $modelo_dispositivo == "Pv2"){
                $sql->bindValue(":st1", "1");
                $sql->bindValue(":st2", "1");
                $sql->bindValue(":st3", "NULL");
                $sql->bindValue(":st4", "NULL");
            }
            if($modelo_dispositivo == "Lv3" || $modelo_dispositivo == "Pv3"){
                $sql->bindValue(":st1", "1");
                $sql->bindValue(":st2", "1");
                $sql->bindValue(":st3", "1");
                $sql->bindValue(":st4", "NULL");
            }
            if($modelo_dispositivo == "Lv4" || $modelo_dispositivo == "Pv4"){
                $sql->bindValue(":st1", "1");
                $sql->bindValue(":st2", "1");
                $sql->bindValue(":st3", "1");
                $sql->bindValue(":st4", "1");
            }
            $sql->execute();
            echo "<script>setTimeout('location.href = 'painel.php';', 5000);</script>";
            //echo '<script>location.href="painel.php";</script>';
            return true;
        }
        //se não estiver cadastrado, vamos fazer o cadastro

    }
    public function alterarStatusPorta($mac_dispositivo,$status_dispositivo1)
    {
        global $pdo;
        //verificar se ja está cadastrado
        $sql = $pdo->prepare("SELECT mac_dispositivo FROM dispositivos WHERE mac_dispositivo = :mc AND status_dispositivo1 = :st1 AND status_dispositivo2 = :st2 AND status_dispositivo3 = :st3 AND status_dispositivo4 = :st4");
        $sql->bindValue(":mc", $mac_dispositivo);
        $sql->bindValue(":st1", $status_dispositivo1);//Verificar se status é igual
        $sql->bindValue(":st2", $status_dispositivo2);
        $sql->bindValue(":st3", $status_dispositivo3);
        $sql->bindValue(":st4", $status_dispositivo4);
        $sql->execute();
        if($sql->rowCount() > 0)
        {
            return false;
        }
        else
        {//não cadastrado, cadastrando agora
            if($status_dispositivo1 == ""){
                echo 'Não altera dispositivo 1';
            }else{
                $sql = $pdo->prepare("UPDATE dispositivos SET status_dispositivo1 = (:st1) WHERE mac_dispositivo = (:mc)");
                //UPDATE dispositivos SET status_dispositivo = 'L';
                $sql->bindValue(":mc", $mac_dispositivo);
                $sql->bindValue(":st1", $status_dispositivo1);
                $sql->execute();
                //echo '<script>location.href="01-portas.php";</script>';  
            }
            if($status_dispositivo2 == ""){
                echo 'Não altera dispositivo 2';
            }else{
                $sql = $pdo->prepare("UPDATE dispositivos SET status_dispositivo2 = (:st2) WHERE mac_dispositivo = (:mc)");
                //UPDATE dispositivos SET status_dispositivo = 'L';
                $sql->bindValue(":mc", $mac_dispositivo);
                $sql->bindValue(":st2", $status_dispositivo2);
                $sql->execute();
                //echo '<script>location.href="01-portas.php";</script>';    
            }
            if($status_dispositivo3 == ""){
                echo 'Não altera dispositivo 3';
            }else{
                $sql = $pdo->prepare("UPDATE dispositivos SET status_dispositivo3 = (:st3) WHERE mac_dispositivo = (:mc)");
                //UPDATE dispositivos SET status_dispositivo = 'L';
                $sql->bindValue(":mc", $mac_dispositivo);
                $sql->bindValue(":st3", $status_dispositivo3);
                $sql->execute();
                //echo '<script>location.href="01-portas.php";</script>';      
            }
            if($status_dispositivo4 == ""){
                echo 'Não altera dispositivo 4';
            }else{
                $sql = $pdo->prepare("UPDATE dispositivos SET status_dispositivo4 = (:st4) WHERE mac_dispositivo = (:mc)");
                //UPDATE dispositivos SET status_dispositivo = 'L';
                $sql->bindValue(":mc", $mac_dispositivo);
                $sql->bindValue(":st4", $status_dispositivo4);
                $sql->execute();
                //echo '<script>location.href="01-portas.php";</script>';
            }
            return true;
        }
        //se não estiver cadastrado, vamos fazer o cadastro
    }


    public function vincule_dispositivo_casa($id_casa, $senha_casa, $mac_dispositivo){
        global $pdo;
        //verificar se ja está cadastrado
        $sql = $pdo->prepare("SELECT id_casa FROM casas WHERE id_casa = :idc");
        $sql->bindValue(":idc", $id_casa);
        $sql->execute();
        if($sql->rowCount() > 0){//Casa encontrada
            echo 'Casa Encontrada!    ';          
            $sql = $pdo->prepare("SELECT * FROM casas WHERE id_casa = :idc AND senha_casa = :snc");
            $sql->bindValue(":idc", $id_casa);
            $sql->bindValue(":snc", $senha_casa);//Verificar se status é igual
            $sql->execute();
            if($sql->rowCount() > 0){//vinculo de dispositivo permitido
                echo 'Vinculo de dispositivo permitido!    ';
                $sql = $pdo->prepare("UPDATE dispositivos SET id_casa = :idc WHERE mac_dispositivo = :mds");
                $sql->bindValue(":idc", $id_casa);
                $sql->bindValue(":mds", $mac_dispositivo);//Verificar se status é igual
                $sql->execute();
                return true;
            }else{
                echo 'Senha da casa errada';
            }
        }else{
            echo 'Casa não encontrada!  ';
        }
    }
    public function ver_dispositivo_casa($id_casa){
        global $pdo;
        //buscar casas vinculadas ao usuário logado
        $sql = $pdo->prepare("SELECT * FROM dispositivos WHERE id_casa = :idc");
        $sql->bindValue(":idc", $id_casa);
        $sql->execute();
        $casa = $sql->fetch();
        
        //$_SESSION['id_casa'] = $casa['id_casa'];
        //$_SESSION['fnome_morador'] = $casa['fnome_morador'];
        //$_SESSION['snome_morador'] = $casa['snome_morador'];
    }
}
?>