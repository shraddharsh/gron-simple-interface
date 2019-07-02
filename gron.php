<?php

if(!empty($_POST)){
	$command = $_POST['command']; // Get the command from frontend
	// $output = shell_exec( $command );
	$gron_function = $_POST['gron_function']; // Gron or UnGron

	// $file = shell_exec('file_'.time().'.json < '.$command);
	// exec('file_'.time().'.json < '.$command, $file);
	$filename = './file_'.time().'.json'; // Create a file since the json entered by the user can be very long
	file_put_contents($filename, $command); // and write in that file

	if($gron_function != 'gron'){ // Check whether the user wants to gron/ungron
		$command = '/opt/gron --ungron '.$filename;
	}
	else{
		$command = '/opt/gron '.$filename;
	}

	exec($command, $output, $status); // Execute the command
	unlink($filename); // delete the file incase you don't want to then comment this line
	echo json_encode($output); // and return the output
	exit();	
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Get Gron or Ungron</title>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container" style="margin-top: 100px">
		<form action="getGron.php" method="POST">

			<!-- Get command from user -->
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-2 col-form-label">Command</label>
				<div class="col-sm-10">
					<textarea class="form-control" id="command" name="command" placeholder="Enter your gron command without 'gron'"></textarea>
				</div>
			</div>
	
			<!-- Get the function that user wants to perform i.e. Gron/UnGron -->
			<fieldset class="form-group">
				<div class="row">
					<legend class="col-form-label col-sm-2 pt-0">Function: </legend>
					<div class="col-sm-10">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="gron-function" id="gron-radio" value="gron" checked>
							<label class="form-check-label" for="gron-radio">
								Gron
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="gron-function" id="ungron-radio" value="--ungron">
							<label class="form-check-label" for="ungron-radio">
								UnGron
							</label>
						</div>
					</div>
				</div>
			</fieldset>

			<!-- Display the output -->
			<div class="form-group row">
				<label for="inputPassword3" class="col-sm-2 col-form-label">Output</label>
				<div class="col-sm-10">
					<!-- <textarea class="form-control" id="output" name="output" placeholder="Your Gronned/Ungronned output will appear here"></textarea> -->
					<section id="output" contenteditable="true">
					</section>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-sm-10">
					<button type="button" id="submit-command" class="btn btn-primary">Gron it..!!</button>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		$(function(){

			// When the user clicks submit then 
			// 		fetch the command and submit to 
			// 		the server for processing
			// 		and display the output

			$("#submit-command").on("click", function(e){
				let form = $(this).closest('form');
				$.ajax({
					// url: form.attr('action'),
					method: form.attr('method'),
					type: 'json',
					data: {
						command: $('#command').val(),
						gron_function: $("input[name='gron-function']:checked").val()
					},
					success: function( response ){
						let data = JSON.parse(response);
						let output = '<ul>';
						$.each(data, function(i, v){
							output += "<li>"+v+"</li>";
						});
						output += '</ul>'
						$("#output").html(output);
					},
					error: function( error ){
						console.log(error);
					}
				});
			});
		});
	</script>
</body>
</html>
