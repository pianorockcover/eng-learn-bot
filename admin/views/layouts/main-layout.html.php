<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<title>Bot Admin Panel</title>
	
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" type="image/png" href="assets/img/favicon.png" />
</head>
<body>
	<div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php" class="navbar-brand">Панель управления ботом</a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php?r=main/logout">Выход</a></li>
          </ul>

        </div>
      </div>
    </div>
    <div class="page-wrapper">
  		<div class="container">
  			<?= $content ?>
  		</div>
    </div>

    <footer>
      <div class="container">
        <div class="row">
          <div class="col-sm-6 pull-right"><b>powered by</b> <a href="https://njdstudio.com" target="_blank" class="copyright"><img src="assets/img/njd-logo.png" alt=""> Studio</a></div>
        </div>
      </div>
    </footer>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/forms.js"></script>

    <script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>