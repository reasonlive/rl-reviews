

<header class="theme-light-block">
  <div class="logo">
    <img src="/images/logo.png" alt="logo">
  </div>
  <div class='header-title' onclick="document.location = '/' ">Review Book</div>
  <div class='btn theme-light-item' onclick="document.location = '?pages=publish' " >publish review</div>
  <?php if($_SESSION && $_SESSION['admin']): ?>
  	<div class='btn theme-light-item' onclick="document.location = '?pages=login' ">logout</div>
  	<?php else: ?>
  	<div class='btn theme-light-item' onclick="document.location = '?pages=login' ">login</div>
	<?php endif; ?>
</header>