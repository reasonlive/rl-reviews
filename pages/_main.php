<?php session_start(); ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Review book</title>
    <link rel="shortcut icon" type="image/x-icon" href="book.png">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles/index.css">

  </head>
  <body>
    <?php include("parts/header.php"); ?>

    <main
    onselectstart="return false;" 
    class="theme-strict-block">
      <?php include("switcher.php"); ?>
    </main>
    
    <?php include("parts/footer.php"); ?>
  </body>
</html>

