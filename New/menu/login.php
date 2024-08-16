
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Empresa</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-3"></div>
        <div class="col-6">
         <div class="jumbotron">
           <h1 class="display-4">Login</h1>  
           <form action="../controles/valida.php" method="POST">
             <div class="mb-3">
               <label for="exampleInputEmail1" class="form-label">Usuario</label>
               <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="login">
               <div class="form-text">Entre com seus dados de acesso</div>
             </div>
             <div class="mb-3">
               <label for="exampleInputPassword1" class="form-label">Senha</label>
               <input type="password" class="form-control" name="senha">
             </div>

             <button type="submit" name="" class="btn btn-primary">Acessar</button>
           </form>
    
             
         </div>
      </div>
      <div class="col-3"></div>

    </div>



    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
