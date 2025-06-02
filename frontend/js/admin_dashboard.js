
    function getQueryParam(key) {
      return new URLSearchParams(window.location.search).get(key);
    }
  
    const categorySuccess = getQueryParam('category');
    const deleteSuccess = getQueryParam('success');
    const popup = document.getElementById('success-popup');
    
      const popupMessage=document.getElementById('popup-message');
      popupMessage.textContent='category created successfully! ';
    if (categorySuccess === 'success') {

      popup.classList.remove('hidden');
      popup.classList.add('show');
  
      setTimeout(() => {
        popup.classList.remove('show');
        popup.classList.add('hidden');
      }, 3000); // 3 seconds
    }
    else if(categorySuccess === 'duplicate'){
      popupMessage.textContent='category exist ';
      popup.classList.remove('hidden');
      popup.classList.add('show');
  
      setTimeout(() => {
        popup.classList.remove('show');
        popup.classList.add('hidden');
      }, 3000); 
    }
    else if(deleteSuccess==='deleted'){
      const ptext=document.getElementById('ptext');
      ptext.textContent='successfully deleted!'
      ptext.classList.remove('hidden');
      ptext.classList.add('show');
  
      setTimeout(() => {
        ptext.classList.remove('show');
        ptext.classList.add('hidden');
      }, 3000); 
    }
