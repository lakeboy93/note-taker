<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "note-taker";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT userid, keyword, description FROM notes";
$result = $conn->query($sql);

if (!$result) {
    trigger_error('Invalid query: ' . $conn->error);
}
$counter = 0;
$arr = [];
$myObj = new stdClass();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        
        $myObj->userid = $row["userid"];
		$myObj->keyword = $row["keyword"];
		$myObj->description = $row["description"];

		$myJSON = json_encode($myObj);
		array_push($arr,$myJSON);
    }
} else {
    echo "0 results";
}
$arrLength = count($arr);
$randomNumber = rand(0, $arrLength-1);
for($i = 1; $i < $arrLength; $i++){
	$toppings = json_decode($arr[$randomNumber], true);
	$userid = $toppings['userid'];
	$keyword = rtrim($toppings['keyword']);
	$description = rtrim($toppings['description']);
};

$conn->close();
?>

<html>
<head>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/styles.css">
</head>
<script>
	$(document).ready(function(){
		var keyword = "<?php echo $keyword ?>";
		var description = "<?php echo $description ?>";
		$('#keyword').append(keyword);
		$('#description').append(description);
		var count = true;
		$("#flipBtn").click(function(){
			if (count == true){
				$('.flip-card-inner').css("transform", "rotateY(180deg)");
				count = false;
			}
			else {
				$('.flip-card-inner').css("transform", "rotateY(360deg)");
				count = true;
			}
		});
	});
</script>
<body>
	<div style="margin-top:100px">
		<div class="flip-card" style="margin:auto">
		  	<div class="flip-card-inner">
		    	<div class="flip-card-front">
		      		<h1 id="keyword"></h1>
		    	</div>
		    	<div class="flip-card-back">
		      		<h2 id="description"></h2>
		   		</div>
		  	</div>
		  	<div class="row" style="margin-top: 25px">
			  	<div style="float:left">
			  		<button id="backBtn">Back</button>
			  	</div>
			  	<div style="margin: auto;width: fit-content">
			  		<button id="flipBtn">Flip</button>
			  	</div>
			  	<div style="float:right">
			  		<button id="nextBtn">Next</button>
			  	</div>
			 </div>
		</div>
	</div>
</body>
</html>