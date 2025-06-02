
    function getUrl(key){
        return new URLSearchParams(window.location.search).get(key);
    }
    const e=getUrl('email');
    const p=getUrl('password');

    if (e === 'f' || p === 'f') {
    const ptext = document.getElementById('in_pe'); 
    ptext.innerText = 'Email or password is incorrect. Please try again.'; 
    ptext.style.color = 'red';
    ptext.style.padding = '2em';
    setTimeout(() => {
        ptext.innerText = '';
        ptext.style.padding = '';
    }, 3000);
}
