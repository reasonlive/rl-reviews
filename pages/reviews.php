<?php 

$show_max_num = 5;
$reviews_amount = count($db->get_allowed('reviews'));
$pages_amount = ceil($reviews_amount / $show_max_num);


$answers = $db->get_all('answers');


$reply_arr = array();
foreach($answers as $ans){
  $reply_arr[$ans['review']] = $ans['message'];
}


if(isset($_GET['p']) && intval($_GET['p']) ){

$page = intval($_GET['p']);

//define offset related to page number 
$offset = $page * $show_max_num - $show_max_num;


$reviews = $db->get_some('reviews', $show_max_num, $offset);


}else{

  $page = 1;
  $reviews = $db->get_some('reviews', $show_max_num);
}

//var_dump($page);
?>

<style>

.reviews{
  padding:10px;
  width:90%;
  height:90%;
  background:white;
  overflow: auto;
}


.review{
  margin:10px 0 10px 0;
  width:100%;
  min-height:100px;
  
  display: flex;
}

  .review_title{
    font-size:20px;
    font-weight: bold;
  }

  .review_left-block{
    text-align: left;
    min-width:200px;
    overflow:hidden;
  }

  .review_main-block{
    text-align: center;
    width:100%;
    background: rgba(50,50,50,1);
    color: white;
    font-size:20px;
    
  }





.paginate{
  display: flex;
  align-items: center;
}

.pag-nums{
  height:30px;
  width:150px;
  background: red;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 5px;
}

.pag-nums div{
  width:25px;
  height:25px;
  border-radius: 50%;
  display: inline;
  cursor: pointer;
  
}

.curr-num{
  background: green;
}

.cntrl{

  width:50px;
  padding:5px;
  background: red;
  border-radius:5px;
  margin:5px;
  cursor:pointer;
}

.cntrl:hover, .pag-nums div:hover{
  background: white;
}

  
</style>

<div class="reviews">

  <?php if($reviews_amount < 1):?>
      <div>review list is empty</div>
      <div>to add review click on button 'publish review' </div>
    <?php endif ?>
  
  <?php foreach($reviews as $ind => $key): ?>
    <!-- begin of review -->

    <div class="review theme-strict-block">

    <div class="review_left-block">

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

     
   </div>
   <!-- end of review -->
   <!-- begin of answer -->

   <?php  if(array_key_exists($key['id'], $reply_arr)): ?>
      
      <div class='answer f-col-left'>
        <div>answer:</div>
       <?= $key['username']?>,  <?= $reply_arr[$key['id']] ?> </div>
      <?php endif; ?>    
   
  <?php endforeach; ?>

</div>

<div class='paginate'>
  
  <div
  onclick="switchPages(this)" 
  class='cntrl l'> < </div>
  <div class="pag-nums">

    <?php 
    if($page == 1)echo "<div class='curr-num'> 1 </div>";
    else echo "<div onclick= 'switchPages(this)'> 1 </div>";
    ?>

    <?php if($pages_amount > 2): ?>

      <?php if($page == 1): ?>
      <div onclick="switchPages(this)"> <?= $page+1 ?> </div>

      <?php elseif($page > 1 && $page < $pages_amount-1): ?>
        <div class="curr-num"> <?= $page ?> </div>
        <div onclick="switchPages(this)"> <?= $page+1 ?> </div>

        <?php elseif($page > 1 && $page == $pages_amount): ?>

          <div onclick="switchPages(this)"> <?= $page-1 ?> </div>
          <?php else: ?>
            <div class="curr-num"> <?= $page ?> </div>

      <?php endif; ?>
    <?php endif; ?>

    <?php 
    if($page < $pages_amount)echo "<div onclick= 'switchPages(this)'> $pages_amount </div>";
    if($pages_amount > 1 && $page == $pages_amount) echo "<div class='curr-num'> $pages_amount </div>";
    
    
     ?>
  </div>

  <div 
  onclick="switchPages(this)"
  class='cntrl r'> > </div>
  
</div>

<script>

  //handler for left/right buttons and cipher buttons
  //needs to switch lists of reviews
  function switchPages(elem){

   

    let endPage = <?= $pages_amount ?>;

    if(!isNaN(elem.innerHTML)){
      let num = Number(elem.innerHTML);
      document.location.href = `?pages=reviews&p=${num}`;
      return;
    };
    
    let currentPage = document.getElementsByClassName('curr-num')[0];
    currentPage = Number(currentPage.innerHTML);
    


    if(elem.classList.contains('l')){
     
      if(currentPage === 1)return;
      document.location.href = `?pages=reviews&p=${currentPage-1}`;
      return;
    }
    if(elem.classList.contains('r')){
      
      if(currentPage === endPage)return;
      document.location.href = `?pages=reviews&p=${currentPage+1}`;
      return;
    }

    

  }


</script>
