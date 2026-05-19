function validate(form) {
    var name  = form.name.value.trim();
    var email = form.email.value.trim();
    var phone = form.phone.value.trim();
    var pass  = form.password.value.trim();
    var flag  = true;

    document.getElementById("nameErr").innerHTML  = "";
    document.getElementById("emailErr").innerHTML = "";
    document.getElementById("phoneErr").innerHTML = "";
    document.getElementById("passErr").innerHTML  = "";

    if (name  === "") { document.getElementById("nameErr").innerHTML  = "Name is required.";     flag = false; }
    if (email === "") { document.getElementById("emailErr").innerHTML = "Email is required.";    flag = false; }
    if (phone === "") { document.getElementById("phoneErr").innerHTML = "Phone is required.";    flag = false; }
    if (pass  === "") { document.getElementById("passErr").innerHTML  = "Password is required."; flag = false; }
    return flag;
}