// adminLogin.js — Client-side form validation for admin login

function validateLogin(form) {
    const email    = form.email.value.trim();
    const password = form.password.value.trim();
    let valid = true;

    document.getElementById('emailErr').innerHTML    = '';
    document.getElementById('passwordErr').innerHTML = '';

    if (email === '') {
        document.getElementById('emailErr').innerHTML = 'Email is required.';
        valid = false;
    } else if (!email.includes('@')) {
        document.getElementById('emailErr').innerHTML = 'Enter a valid email address.';
        valid = false;
    }

    if (password === '') {
        document.getElementById('passwordErr').innerHTML = 'Password is required.';
        valid = false;
    }

    return valid;
}