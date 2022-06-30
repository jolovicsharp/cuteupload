<?php
session_start();
$_SESSION['token'] = bin2hex(random_bytes(32));
$_SESSION['token1'] = bin2hex(random_bytes(32));
$token = $_SESSION['token'];
$token1 = $_SESSION['token1'];
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        #sticky-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        #heading {
            overflow: hidden;
        }
        body {
            color: #ee0979;
            overflow: hidden;
        }
        /* #ee0979 */
        #main {
            background-color: white;
       /*height: 100vh; */
        }
        * {
            font-family: "Lato";
            margin: 0;
            overflow: auto;
        }
        h3 {
           color: #ee0979;
        }
        #first {
            color: #ee0979;
            font-size: 15px;
        }
        #second {
            color: #ee0979;
            width: 25rem;
        }
        @media only screen and (max-width: 768px) {
        .main {
            width: 100%;
            flex-direction: column!important;
        }
        .d {
            width: fit-content;
            height: fit-content;
        }
        
 #first {
    width: 75%;
    flex-direction: column!important;
  }
  #sticky-footer {
    position: relative;
            bottom: 0;
            width: 100%;
  }
  #second {
    width: 75%;
    flex-direction: column!important;
  }
}
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CUTECODE</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
</head>
<body>
    <div id="all" class="">
    <nav class="test navbar navbar-expand-lg sticky-top navbar-custom bg-white navbar-light border-bottom border-1">
        <div class="container"><a class="navbar-brand" href="#">CUTECODE</a><button data-bs-toggle="collapse" class="navbar-toggler" style="color: transparent;" data-bs-target="#navbarResponsive"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="api.php" >Api</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="heading" class="mt-3 overflow-hidden"><center><h3 class="overflow-hidden">Cutecode. This is only a beta.</h3></center></div>
    <div id="main" class="main d-flex flex-column d-sm-flex flex-sm-row justify-content-around align-items-center">
    <div id="first" class="d-flex flex-column border border-2 border-primary p-3 rounded justify-content-center align-items-center">
        <p>Upload your file</p>
        <form method="post" action="engine/cuteupload.php" enctype="multipart/form-data">
        <input type="hidden" name="t" value="<?php echo $_SESSION['token'];?>">
        <div class="p-2 border border-1 rounded">
            <label class="form-label">Select your file</label>
            <input class="form-control" name="file" type="file" id="formFile">
            <label for=""class="form-label mt-2">Password (optional)</label>
            <input type="text" name="password" class="form-control" id="" placeholder="">
            <label for="" class="form-label mt-2">Description (optional - max 25 chars)</label>
            <textarea class="form-control mb-3" name="description" cols="3" rows="3"></textarea>
            <button class="btn btn-primary w-100" name="submit" type="submit"><i class="bi bi bi-upload"></i><span> Upload file</span></button>
            <?php if(isset($_SESSION['error'])) {echo '<p>' . $_SESSION['error'] . '</p>';} ?>
        </form>
          </div>
    </div>
    <div id="second" class="d-flex flex-column border border-2 border-primary p-3 m-3 rounded justify-content-center align-items-center">
        <p>Type cute code</p>
        <form class="w-100" method="post" action="engine/cutedownload.php" enctype="">
        <input type="hidden" name="t1" value="<?php echo $_SESSION['token1'];?>">
        <input class="form-control mb-3" type="text" name="cutecode">
        <button class="btn btn-primary w-100" name="check" type="submit"><i class="bi bi-box-arrow-in-down"></i><span> Check file</span></button>
        <?php if(isset($_SESSION['mistake'])) {echo '<p>' . $_SESSION['mistake'] . '</p>'; unset($_SESSION['mistake']);} ?>
    </form>
    </div>
    </div>
    <footer id="sticky-footer" class="mt-2 p-1 flex-shrink-0 py-2 bg-primary text-white-50">
        <div class="container text-center">
            <p>Coded by Dušan Jolović with love. Buy me a coffe</p>
          <small>Copyright &copy; Cuteupload</small>
        </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>