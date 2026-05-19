function validateLogin(form) {
    var email    = form.email.value.trim();
    var password = form.password.value.trim();
    var flag = true;

    document.getElementById("emailErr").innerHTML    = "";
    document.getElementById("passwordErr").innerHTML = "";

    if (email === "") {
        document.getElementById("emailErr").innerHTML = "Email is required.";
        flag = false;
    }
    if (password === "") {
        document.getElementById("passwordErr").innerHTML = "Password is required.";
        flag = false;
    }
    return flag;
}