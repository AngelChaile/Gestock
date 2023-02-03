<?php
    session_start();
    if ($_SESSION['valido'])
    {
        header('Location: ?c=user&a=Index');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $_SESSION['title']; ?></title>
    <link href="Assets/css/styles.css" rel="stylesheet" />
    <script src="Assets/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header text-center">
                                    <h3 class="font-weight-light my-4"></h3>
                                    <img src="Assets/img/logo.jpeg" class="img-fluid rounded" width="150">
                                </div>
                                <div class="card-body">
                                    <form id="frmLogin" action="?c=user&a=Logear" method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="user" value="<?php echo $pvd->Username; ?>" name="Username" type="text" placeholder="User"/>
                                            <label for="user">Usuario</label>
                                            
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="password" value="<?php echo $pvd->Userpass; ?>" name="Password" type="password" placeholder="Contraseña"/>
                                            <label for="password">Contraseña</label>
                                        </div>
                                        <?php echo isset($_SESSION['alert']) ? $_SESSION['alert'] : ''; unset($_SESSION['alert']); ?>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary btn-lg">INGRESAR</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>