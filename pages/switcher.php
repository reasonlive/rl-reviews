<?php 



if(isset($_GET['pages'])){

  $page = strval($_GET['pages']);

  switch($page){
    case 'login': include('./pages/parts/entry_form.php');break;
    case 'publish': include('./pages/parts/publish_form.php');break;
    case 'reviews': include('./pages/reviews.php');break;
    case 'admin': include ('./pages/_main_admin.php');break;
    default: include('./pages/not_found.php');
  }
}else{
  header('Location: /?pages=reviews');
}



?>
