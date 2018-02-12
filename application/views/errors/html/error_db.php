<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>Somethings wrong</title>
		<style type="text/css">
			body {
				font-family: arial;
				font-size: 14px;
				color: #353535;
			}
			.container {
				border: 1px solid #e9e9e9;
				width: 43%;
				margin: 0 auto;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				box-shadow: 2px 2px 3px #e9e9e9;
				}
			.content {
				padding: 5px 40px 30px 40px;
			}	
			image {
				position: absolute;
				display: block;
				margin-left: auto;
				margin-right: auto;		
				}
			a {
				text-decoration: none;
				color: #2c87f0;
			}
			.btn{
				padding: 20px;
				border: 1px solid #b39e6a;
				border-radius: 10px;
				color:#504e4e;
				font-weight: bold;
				background-color: #fecb50;
			}
		</style>
	</head>
	<body>
		<?php echo $message?>
		<?php send_message_to_developer($heading , $message); ?>
		<div class="container">
			<div class="content">

					<div style="text-align: center;">
						<img src="<?php echo base_url("public/images/sign.png"); ?>">
						<img src="<?php echo base_url("public/images/truck.png"); ?>">
					</div>
					<br>

				It looks as though we've broken something on our system. <br><br>

				DON'T PANIC - I've emailed <a href="mailto:mhar@trackerteer.com">Mhar</a> and told him what's wrong. He'll get to it as soon as possible. <br><br>

				In the meantime, please feel free to click this <a href="javascript:history.go(-1)">link</a> to go back on the working page. <br><br>

				<!-- <h3 style="text-align: center;color: #666666">Who's responsible for this madness?</h3> <br>

				That would be Mhar. He's the Transport App Developer and therefore he takes responsibility when things break. But he's here to help. -->
				<div style="text-align: center;margin: 50px;">
					<a href="javascript:history.go(-1)" class="btn">Go Back to Working Page</a>
				</div>
			</div>
		</div>

	</body>
	</html>
