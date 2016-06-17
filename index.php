<?php include('inc/header.php'); ?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Synapse's Payment Gateway</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>

  <body>
<?php 
  odbc_exec($mssql, 'USE [ACCOUNT_DBF]');
  $idquery = odbc_exec($mssql, "SELECT uid FROM [ACCOUNT_TBL_DETAIL] WHERE [account] = '$user'");
  $result = odbc_result($idquery, 'uid');
  $id = $result;
?>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./">Logged in as: <?php echo $user; ?></a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="../">Home</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
      <div class="starter-template">
        <h1>Synapse's Donation Center</h1>
		<?php 
			if($id == "") {
				echo "Please <a href='../'>Login</a> to view this page.";
			} else {
			echo "
			<div class='payment_container'>
				<iframe src='https://api.paymentwall.com/api/ps/?key="./* PaymentWall API KEY GOES HERE */."&uid=<?php echo $id; ?>&widget=p1_1' width='750' height='800' frameborder='0'></iframe>
				<script src'https://api.paymentwall.com/js/widget/mobile.client.js' type='text/javascript'></script><a id='[BUTTON_ID]' style='display: block; cursor: pointer; height: 37px; width: 230px; text-indent: -2000em; background: url(https://api.paymentwall.com/images/widgets/mobile/mobile_but3.png) repeat scroll 0 0 transparent;' onclick='pw_widget_mobile('[BUTTON_ID]', '919a4dc58c0c978019120c1db27e19a4', '[USER_ID]', 'm1_1', null, {'controller':'ps'}); return false;'></a>
			</div>";
			}
		?>
   </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
