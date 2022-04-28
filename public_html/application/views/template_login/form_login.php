    <link rel="stylesheet" type="text/css" href="assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>

    <div class="be-wrapper be-login">
        
        <div class="main-content container-fluid" style="padding-top: 100px;">
          <div class="splash-container">
            <div class="panel panel-default panel-border-color panel-border-color-primary">
              <div class="panel-heading"><span class="splash-description">Por favor entre com suas informações.</span></div>
              <div class="panel-body">
              
              <form class="form" method="post" action="login/auth">

                    <div class="form-group">
                        <input id="nm_login" name="nm_login" type="text" placeholder="Nome do Usuário" autocomplete="off" class="form-control">
                    </div>
                    <div class="form-group">
                        <input id="ps_login" name="ps_login" type="password" placeholder="Senha" class="form-control">
                    </div>
                    <div class="form-group row login-tools">
                        <div class="col-xs-6 login-remember">
                        <div class="be-checkbox">
                            <input type="checkbox" id="remember">
                            <label for="remember">Lembrar</label>
                        </div>
                        </div>
                        <div class="col-xs-6 login-forgot-password"><a href="#">Esqueceu sua Senha?</a></div>
                    </div>
                    <div class="form-group login-submit">
                        <button data-dismiss="modal" type="submit" class="btn btn-primary btn-xl">Entrar</button>
                    </div>

                </form>

              </div>
            </div>
            <div class="splash-footer"><span>Não tem uma conta? <a href="#">Cadastre-se</a></span></div>
          </div>
        </div>

    </div>
    <script src="assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src="assets/js/main.js" type="text/javascript"></script>
    <script src="assets/lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
      	//initialize the javascript
      	App.init();
      });
      
    </script>
  <?php phpinfo(); ?>