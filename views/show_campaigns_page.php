<html>
	<head>
		<meta charset="utf-8">
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
		  <h2>All posts from table</h2>
		  <ul class="list-group">
			  	<?php
			  		foreach ($data as $data) {
			  			echo '<li class="list-group-item">'.$data->post_title.'</li>';
			  		}
			 	?>
		  </ul>
		</div>
	</body>
</html>