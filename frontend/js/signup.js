
function getQueryParam(key) {
  return new URLSearchParams(window.location.search).get(key);
}

const addition = getQueryParam('email');
const confirmPassword = getQueryParam('confirmPassword');

if (confirmPassword === 'f') {
  const con_p = document.getElementsByName('confirm_password');
  con_p[0].style.outline = '2px solid red';
  con_p[0].placeholder = 'Passwords do not match';
  con_p[0].style.color = "red";
  setTimeout(() => {
    con_p[0].style.outline = '';
    con_p[0].placeholder = '';
    con_p[0].style.color = "";
  }, 3000);
} else if (addition === 'registered') {
  const em = document.getElementsByName('email');
  em[0].style.outline = '2px solid red';
  em[0].placeholder = 'Email already taken!';
  em[0].style.color = "red";
  setTimeout(() => {
    em[0].style.outline = '';
    em[0].placeholder = '';
    em[0].style.color = "";
  }, 3000);
}
