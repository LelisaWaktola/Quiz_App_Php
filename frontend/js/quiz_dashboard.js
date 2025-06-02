
      function getUrl(key){
            return new URLSearchParams(window.location.search).get(key);
            }

      const getError=()=>{
        const avalable=getUrl('noQuestion');
        const id=getUrl('id');

            if (avalable==='empty') {
            const ptext = document.getElementById('${id}'); 
            ptext.innerText = 'Email or password is incorrect.'; 
            ptext.style.color = 'red';
            ptext.style.padding = '0em';
            setTimeout(() => {
                ptext.innerText = '';
                ptext.style.padding = '';
            }, 3000);
      }
      }
getError();