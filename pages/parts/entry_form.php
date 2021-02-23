<?php 

if($_SESSION && $_SESSION['admin']){
  $_SESSION = array();
  header("Location: /");
}

 ?>


<style>

  .entry, .form{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  .form input, .form button{
    border:0;outline:0;
    margin:10px;
    height:40px;
    min-width:80px;
    text-align: center;
    font-size:20px;
  }

  .form button:hover{
    cursor:pointer;
  }




</style>

<div class="entry">
  <div>For administration only</div>
  <form class='form' >
    
       <input 
       class="theme-strict-item"
       type="text" 
       name='login' 
       placeholder="login" />

        <input 
        class="theme-strict-item"
        type="password" 
        name='password' 
        placeholder="password" />
    
   <div>
      <button class="theme-strict-item"
       type='submit'>sign in</button>
      <button class="theme-strict-item" 
      onclick="window.history.back(); return false; " >back</button>
   </div>
   
  </form>
</div>

<script>
  async function preSendingForm(e){
    
    e.preventDefault();
    const fields = document.getElementsByTagName('input');

    for(let field of fields){
      if(!field.value || !field.value.match(/[a-zA-Z0-9]/)){
        alert('You have to fill '+field.name);
        field.focus();
        return false;
      }
      if(field.name === 'password' && field.value.length < 8){
        alert('Your password length should have at least 8 characters');
        field.focus();
        return false;
      }
    }

  

    let request = await fetch('/requests/login.php', {
      method: 'POST',
      headers: {
        //'Content-Type': 'application/json;charset=utf8'
      },
      body: new FormData(document.forms[0])
    });

    let response = await request.json();
    
    

    if(response.success)document.location = '?pages=admin';
    else document.location.reload();
  }

  document.getElementsByClassName('form')[0]
  .addEventListener('submit', preSendingForm);
</script>
