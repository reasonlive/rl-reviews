<?php 


if(!$_SESSION['admin'] && !$_SESSION['admin']['auth']){
	header("Location: /");
	exit;
}

$reviews = $db->get_all('reviews');
$answers = $db->get_all('answers');


$published = $responded = $reply_arr = array();


foreach($reviews as $review){
	if($review['allowed'])$published[] = $review;
	if($review['answered'])$responded[] = $review;
	
}

foreach($answers as $answer){
	$reply_arr[$answer['review']] = $answer['message'];
}




if(isset($_GET['opt'])){

	$filter = $_GET['opt'];
	switch($filter){
		case 'pub': $iterable = $published;break;
		case 'total': $iterable = $reviews;break;
		case 'resp': $iterable = $responded;break;
		default: $iterable = $reviews;
	}
	

}else{
	$filter = 'total';
	$iterable = $reviews;
}

?>

<style>
  .admin-panel{
    width:100%;
    height:100%;
    display: flex;
  }

  .menu{
  	width:20%;
  	height:100%;
  	background:rgba(69,60,68,1);
  	overflow:hidden;

  }

  .menu-title{
  	font: 22px Oswald, serif;
  	border-bottom: 2px solid white;
  }

  .menu-item{
  	margin:10px;
  	padding:10px;
  	font-size:18px;
  	font-weight: bold;
  	cursor:pointer;
  	transition:all 0.8s;
  }

  .menu-item:hover{
  	border-top: 1px solid white;
  	border-bottom: 1px solid white;
  	transform: scale(1.1);
  	color:white;

  }

  .review-block{
  	width:80%;
  	height:100%;
  	background: lightgreen;
  	overflow:auto;
  }

  .review{
  	width:90%;
  	margin:20px;
  	border-bottom:1px solid black;

  }

  .review-item{
  	display: flex;
  	font-size:14px;
  }

  .review_title{
  	font-weight: bold;
  	font-size: 1.1em;
  }

  .review_left-block{
  	width:30%;
  	background:white;
  	border-right:1px solid black;
  	font-size:10px;
  	font-weight: bold;


  }
  .review_main-block{
  	width:80%;
  	background: rgba(50,50,50,0.3);
  }

  .review-managed-bar{
  	background:firebrick;
  	margin-bottom: 20px;
  	justify-content: flex-end;

  }
  .review-managed-bar button{
  	
  	border:0;outline:0;
  	cursor:pointer;
  	border-right:1px solid black;
  }
  .review-managed-bar button:hover{
  	background: green;
  	color: white;
  }






</style>
<div class='admin-panel'>

  <div class='menu'>
    <div 
    class="menu-title" 
    onclick="document.location = '?pages=admin&opt=total' " >
	Reviews</div>
    <div 
    class="menu-item" 
    onclick="document.location = '?pages=admin&opt=total' ">
    total amount: <?= $reviews ? count($reviews) : 0 ?>
    </div>

    <div 
    onclick="document.location = '?pages=admin&opt=pub' "
    class="menu-item">
    published: <?= $published ? count($published) : 0 ?>
    </div>

    <div 
    class="menu-item"
    onclick="document.location = '?pages=admin&opt=resp' ">
    responded: <?= $responded ? count($responded) : 0 ?>
    </div>

  </div>
  <div class="review-block">

  	
  	<?php foreach($iterable as $ind => $key): ?>
  	
    <div class="review">

    		<!-- begin of review -->
    	<div class="review-item">

    		<div class="review_left-block f-col-center">

		      <div class="review_date">
		       <?= $key['created'] ?>
		     </div>

		     <div class="review_personal-info">

		      <div class='info-username'>
		        name:
		        <?= $key['username'] ?>
		      </div>
		      <div class="info-phone">
		        phone:
		        <?= $key['phone'] ?>
		      </div>
		      <div class="info-email">
		        email:
		        <?= $key['email'] ?>
		      </div>
		      
		     </div>

		    </div> 


		    <div class="review_main-block theme-light-block">

		      <div class='review_title'>
		       <?= $key['title'] ?> 
		      </div>

		      <div class="review_message">
		       <?= $key['message']  ?>
		      </div>

		    </div>

		    <!-- end of review -->
    	</div>
    	<?php  if(array_key_exists($key['id'], $reply_arr)): ?>
    	
    	<div class='answer'> 
        <div>answer:</div>
        <?= $key['username']?>,  <?= $reply_arr[$key['id']] ?> </div>
    	<?php endif; ?>  

    	<div class="review-managed-bar f-row">
    		<button data-action="publish" data-id="<?= $key['id'] ?>">publish</button>
    		<button data-action="correct" data-id="<?= $key['id'] ?>">correct</button>
    		<button data-action="delete" data-id="<?= $key['id'] ?>">delete</button>
    		<button data-action="reply" data-id="<?= $key['id'] ?>">reply</button>
    	</div>
    </div>
    
    <?php endforeach; ?>

  </div>
  
</div>

<script>



	async function manageReview(event,act){



		let {action,id} = event.target.dataset;
		
		let form = new FormData();
		form.set('id', id);
		form.set('action',action);

		if(action === 'publish'){
			let request = await fetch("/requests/update_review.php", {
				method: 'POST',
				headers:{
					//'Content-Type': 'application/json;charset=utf8'
				},
				body: form
			})

			let response = await request.json();
			
			if(response.success)document.location.reload();
			else alert('Operation Error! Try this action again');
		}

		if(action === 'delete'){
			let request = await fetch("/requests/update_review.php", {
				method: 'POST',
				headers:{
					//'Content-Type': 'application/json;charset=utf8'
				},
				body: form
			})

			let response = await request.json();
			
			if(response.success)document.location.reload();
			else alert('Operation Error! Try this action again');
		}

		if(action === 'correct'){

			let review = event.target.closest('.review').children[0].children[1];
			let revTitle = review.children[0].innerHTML;
			let revMessage = review.children[1].innerHTML;

			let message = prompt(`Correct review '${revTitle.trim()}': `, revMessage);

			form.set('message', message);
			let request = await fetch("/requests/update_review.php", {
			method: 'POST',
			
			body: form
			})

			let response = await request.json();
			if(response.success)document.location.reload();
			else alert('Operation Error! Try this action again');
		}

		if(action === 'reply'){

			let message = prompt('Reply on this review:');
			form.set('message', message);
			let request = await fetch("/requests/update_review.php", {
				method: 'POST',
				headers:{
					//'Content-Type': 'application/json;charset=utf8'
				},
				body: form
			})

			let response = await request.json();
			
			if(response.success)document.location.reload();
			else alert('Operation Error! Try this action again');
		}
	}

	let btns = document.getElementsByTagName('button');

	for(let btn of btns){
		btn.addEventListener('click', function(e){
			manageReview(e, btn.dataset.action);
		});
	}
</script>