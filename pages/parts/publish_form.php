<style>

  

  .publish{
    width:60%;
    height:50%;
  }
  
  .publish-title{
    font: 24px Oswald, serif;
  }

  .form-wrapper{
    margin:20px;
    background: black;
  }

 .publish_personal-info,
  .publish_review-info{
    
    width:50%;
    
    
  }

  .publish_review-info textarea{
    resize: none;
    height: 100%;
  }


  .form input, .form button{
    border:0;outline:0;
    margin:10px;
    height:40px;
    min-width:80px;
    text-align: center;
    font-size:20px;
  }

  






</style>

<div class="publish" onchange="checkFields()">
  <div class='publish-title'>In order to create and publish review yon need to give us personal info:</div>
  <form method="POST" action="requests/publish_review.php" class='form'>
    <div class='f-row form-wrapper'>

      <div class="publish_personal-info f-col">
           <input 
           class="theme-light-item"
           type="text" 
           name='username' 
           placeholder="your name" />
          <input 
          class="theme-light-item"
          type="text" 
          name='phone' 
          placeholder="your phone number" />
          <input 
          class="theme-light-item"
          type="email" 
          name='email' 
          placeholder="your email" />

        <div>
          <button class="theme-light-item" 
          type='submit'>publish</button>
          <button class="theme-light-item" 
          onclick="window.history.back(); return false; " >back</button>
        </div>
      </div>

      <div class="publish_review-info f-col">

        <input
        class="theme-light-item" 
        type="text" 
        name='title' 
        placeholder="review title" />

        <textarea 
        name="message"  
        cols="30" 
        rows="10" 
        maxlength="500"
        disabled="disabled"
        >
        
        </textarea>

      </div>
    </div>
    
  </form>
</div>

<script>
  

  function preSendingForm(e){
    
    e.preventDefault();
    const fields = document.getElementsByTagName('input');
    const message = document.getElementsByTagName('textarea')[0];

    for(let field of fields){
      if(!field.value || !field.value.match(/[a-zA-Z0-9]/)){
        alert('You have to fill '+field.name);
        field.focus();
        return false;
      }
      if(field.name === 'phone' && !field.value.match(/^\+?[0-9]{7,15}$/)){
        alert('Your phone number is incorrect!');
        field.focus();
        return false;
      }
    }



    if(message.value && message.value.length > 5 && message.value.length < 500){
      message.value = message.value.trim();
      alert("Your review sent to the moderation");
      document.forms[0].submit();
    }
    else message.focus()
  }

  function checkFields(){
    const fields = document.getElementsByTagName('input');
    const message = document.getElementsByTagName('textarea')[0];

    for(let field of fields)
      if(field.value.length < 1){
        message.disabled = true;
        return;
      }
  
    message.disabled = false; 

  }

  document.getElementsByClassName('form')[0]
  .addEventListener('submit', preSendingForm);
</script>
