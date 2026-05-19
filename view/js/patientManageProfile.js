function validateInfo(form) {
    var name  = form.name.value.trim();
    var email = form.email.value.trim();
    var flag  = true;

    document.getElementById("nameErr").innerHTML  = "";
    document.getElementById("emailErr").innerHTML = "";

    if (name === "") {
        document.getElementById("nameErr").innerHTML = "Name is required.";
        flag = false;
    }
    if (email === "") {
        document.getElementById("emailErr").innerHTML = "Email is required.";
        flag = false;
    }
    return flag;
}

function validatePassword(form) {
    var current = form.current_password.value;
    var newPass  = form.new_password.value;
    var confirm  = form.confirm_password.value;
    var flag = true;

    document.getElementById("currentPasswordErr").innerHTML = "";
    document.getElementById("newPasswordErr").innerHTML     = "";
    document.getElementById("confirmPasswordErr").innerHTML = "";

    if (current === "") {
        document.getElementById("currentPasswordErr").innerHTML = "Current password is required.";
        flag = false;
    }
    if (newPass === "") {
        document.getElementById("newPasswordErr").innerHTML = "New password is required.";
        flag = false;
    } else if (newPass.length < 6) {
        document.getElementById("newPasswordErr").innerHTML = "Password must be at least 6 characters.";
        flag = false;
    }
    if (confirm === "") {
        document.getElementById("confirmPasswordErr").innerHTML = "Please confirm new password.";
        flag = false;
    } else if (newPass !== confirm) {
        document.getElementById("confirmPasswordErr").innerHTML = "Passwords do not match.";
        flag = false;
    }
    return flag;
}