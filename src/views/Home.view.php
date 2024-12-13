<!DOCTYPE html>
<html lang='en'>

<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<title>Home</title>
</head>

<body>
	<main style="height: 80vh;
				 display: flex;
				 flex-direction: column;
				 justify-content: center;
				 align-items: center;
				 gap: 2rem;
				">
		<img style="width: 25rem;" src="mono.jpg" alt="mono logo">
		<h2>The Fast, Scalable, Object-Oriented PHP Framework.</h2>
		<div
			style="color: gray;
			 	   width: 10rem; 
				   display: grid;
			       place-items: center;">
			<marquee>
				<?= $msg ?>
			</marquee>
		</div>
	</main>
</body>

</html>