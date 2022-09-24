<?php
    require_once 'classes/usuarios.php';
    $u = new Usuario;

?>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <title>Tela de Login</title>
    <link rel="stylesheet" href="css/estilo.css">
    <script src="js/functions.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <meta name="google-signin-client_id" content="1046285428289-m8qq1r39eg1uubivdjgjbfhknbrrij26.apps.googleusercontent.com">
</head>
<body>
    <div id="corpo-form-login">
    <h1>Entrar</h1>
    
    <form method="POST">
        <input oninput="mascara(this)" type="tel" name="cpf_morador" placeholder="CPF">
        <input type="password" name="senha_morador" placeholder="Senha">
        <input type="submit" value="ACESSAR">
        <a href="01-cadMorador.php">Ainda não é escrito?<strong>Cadastre-se!</strong></a>
    </form>
    </div>
<?php
if (isset($_POST['cpf_morador']))
{
    $cpf_morador = addslashes($_POST['cpf_morador']);
    $senha_morador = addslashes($_POST['senha_morador']);
    
    if (!empty($cpf_morador) && !empty($senha_morador)) 
    {
        $u->conectar("smart_house_1_0","85.10.205.173","amorimgabriel","27092000");
        if ($u-> $msgErro == "")
        {
            if($u->logar($cpf_morador,$senha_morador))  
            {
                ?>
                    <div class="msg-sucesso">Entrando</div>
                <?php
                //header("location: AreaPrivada.php");
                echo '<script>location.href="AreaPrivada.php";</script>';
            }
            else
            {
                ?>
                    <div class="msg-erro">Email ou senha incorretos!</div>
                <?php
            }
        }
        else
        {
            ?>
                <div class="msg-erro"><?php echo "Erro: ".$u->msgErro;?></div>
            <?php
        }
    }
    else
    {
        ?>
            <div class="msg-erro"><?php echo "preencha todos os campos!"?></div>
        <?php
    }
}
?>
    <table id="loggin_loggout">
        <tr>
            <th>
            <div id="g_id_onload"
         data-client_id="1046285428289-m8qq1r39eg1uubivdjgjbfhknbrrij26.apps.googleusercontent.com"
         data-login_uri="login/01-loginGoogle.php"
         data-auto_prompt="false">
      </div>
      <div class="g_id_signin"
         data-type="standard"
         data-size="large"
         data-theme="outline"
         data-text="sign_in_with"
         data-shape="rectangular"
         data-logo_alignment="left">
      </div>
            </th>
            <!--<th>
                <a href="login.php" onclick="signOut();">Sign out</a>
                <script>
                    function signOut() {
                        var auth2 = gapi.auth2.getAuthInstance();
                        auth2.signOut().then(function () {
                            console.log('User signed out.');
                        });
                    }
                </script>
            </th>
            -->
        </tr>
    </table>
</body>
</html>
