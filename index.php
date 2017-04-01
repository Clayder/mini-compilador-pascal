<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title> Compilador </title>

        <!-- Bootstrap -->
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/bootstrap/css/mystyle.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <!-- <body style="background-color: #222"> -->
    <body>

        <div class="container" style="margin-top: 30px;">
            <form action="compilador.php" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn button-compilar">
                            <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
                            run 
                        </button> 
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-5" style="height: 630px; overflow-x: 200px">

                        <textarea name="codigo" class="text-codigo" placeholder="escreva o código aqui ..." autofocus>
                            <?php echo isset($inputCodigo) ? $inputCodigo : ""; ?>
                        </textarea>


                    </div>
                    <div class="col-md-7" style="height: 630px;">
                            <?php include("resultado.php"); ?>
                    </div>
                </div>
            </form>
        </div>



        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="assets/jquery.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>