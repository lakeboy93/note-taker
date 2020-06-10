<html>
<head>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<?php include 'AppendCards.php';?>
</head>

<script>

	$(document).ready(function(){

		function updateCard(UpdateCardNumber){
			console.log("updatecardNo: ", UpdateCardNumber);
			if (UpdateCardNumber > -1 && UpdateCardNumber <= maxCardNumber){
				cardNumber = UpdateCardNumber;
				$('#keyword').html(cardKeywords[cardNumber-1]);
				$('#description').html(cardDescriptions[cardNumber-1]);
				$('#cardCounter').html(UpdateCardNumber + "/" + maxCardNumber);
				console.log(maxCardNumber)
			} 
		}

		function emptySet () {
			$('#keyword').html(`
				<p style="margin-right:10px">your deck is empty!</p>    
				<button class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id='newCardBtn'>
			  		New Card
			  		<img src='img/plus.png' style='height:30px;'></img>
			  	</button>`);
			$('#description').empty();
			$('#cardCounter').html(0 + "/" + 0);
		}

		cardKeywords = <?php echo $keywordsJSON ?>; //grabs keywords from php and stores it in object
		cardDescriptions = <?php echo $descriptionsJSON ?>; // grabs descriptions from php and store it in object
		cardNumber = 1;
		maxCardNumber = cardKeywords.length // grabs how many cards there are 

		setName = "Straight Facts"

		//first card on inital load
		updateCard(cardNumber)
		if(maxCardNumber == 0){
			emptySet()
		}

		//changes to the next card in the object
		$("#nextBtn").click(function(){		
			if(cardNumber < maxCardNumber){
				updateCard(cardNumber+1);
			}	
		});

		//changes to the previous card in the object
		$("#backBtn").click(function(){	
			if(cardNumber > 1)	{
				updateCard(cardNumber-1);
			}	
		});

		var newKeyword = $('#newKeyword').val();
		var newDescription = $('#newDescription').val();


		//function flips the card
		var cardFlipped = false;
		$("#flipBtn").click(function(){
			if (cardFlipped == false){
				$('.flip-card-inner').css("transform", "rotateY(180deg)");
				cardFlipped = true;
			}
			else {
				$('.flip-card-inner').css("transform", "rotateY(360deg)");
				cardFlipped = false;
			}
		});

		$('#addCardBtn').on('click', function() {
			$("#addCardBtn").attr("disabled", true);
			var keyword = $('#newKeyword').val();
			var description = $('#newDescription').val();
			if(keyword!="" && description!=""){
				$.ajax({
			        url: "save.php",
			        type: "post",
			        data: {
			        	setID: setName,
			        	noteID: maxCardNumber+=1,
						keyword: keyword,
						description: description,		
					},
					success: function (response) {
						$('#myModal').modal('hide');
						cardKeywords.push(keyword);
						cardDescriptions.push(description);
						
						updateCard(cardNumber)
						console.log(cardKeywords)
						$("#addCardBtn").removeAttr("disabled")
					// You will get response from your PHP page (what you echo or print)
        			},
			    });
			}
			else{
				alert('Please fill all the fields!');
				$("#addCardBtn").removeAttr("disabled")
			}
		});

		$('.delCardBtn').on('click', function() {
			$(".delCardBtn").attr("disabled", true);
			var keyword = $('h1#keyword.cardText').text();
			var description = $('#description').text();
				$.ajax({
			        url: "delete.php",
			        type: "post",
			        data: {
						keyword: keyword,
						description: description,		
					},
					success: function (response) {
						cardKeywords.splice(cardNumber -1,1);
						cardDescriptions.splice(cardNumber -1,1);
						
						if (maxCardNumber <= 1){
							emptySet();
						}
						else if (cardNumber == 1){
							updateCard(cardNumber)
							console.log("gotum")
						} else {
							updateCard(cardNumber -1)
						}
						
						console.log(cardKeywords)
						maxCardNumber = maxCardNumber - 1;
						console.log(maxCardNumber)
						$(".delCardBtn").removeAttr("disabled")
						updateCard(cardNumber)
					// You will get response from your PHP page (what you echo or print)
        			},
			    });
			
		});
	});	
</script>

<body>


	<!--   Notecard Wrapper   -->
	<div style="margin-top:100px">
		<div class="flip-card" style="margin:auto">
			<div id="cardCounter" style="display: flex; justify-content: center"></div>
		  	<div class="flip-card-inner" id="flipCard">
		    	<div class="flip-card-front">
		      		<h1 class="cardText" id="keyword"></h1>
		      		<div class="cardLabel">Keyword</div>
		      		<button class="delCardBtn" style="float:right;margin-top: -27px;">Delete</button>
		      	</div>
		
		    	<div class="flip-card-back">
		      		<h2 class="cardText" id="description"></h2>
		      		<div class="cardlabel">Description</div>
		      		<button class="delCardBtn" style="float:right;margin-top: -27px;">Delete</button>
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
	<!--   End Notecard Wrapper   -->


			 <div class="newCard"> 
			  		<button class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id='newCardBtn'>
			  			New Card
			  			<img src='img/plus.png' style='height:30px;'></img>
			  		</button>
			 </div>
		</div>
	</div>
	

	<div class="container">

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add a new card</h4>
        </div>
        <div class="modal-body">
        	<div class="row">
        		<label>Keyword</label>
        		<input id="newKeyword"/>
        	</div>
        	<div class="row">
        		<label>Description</label>
        		<input id="newDescription"/>
        	</div>
        	<div>
        		<button id="addCardBtn">Add Card</button>
        	</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Discard Changes</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

</body>
</html>