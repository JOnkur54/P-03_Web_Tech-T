function validateInfo(form) {

    var name = form.name.value;
    var email = form.email.value;
    var flag = true;

    document.getElementById("nameErr").innerHTML = "";
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

    var currentPassword = form.current_password.value;
    var newPassword = form.new_password.value;
    var confirmPassword = form.confirm_password.value;
    var flag = true;

    document.getElementById("currentPasswordErr").innerHTML = "";
    document.getElementById("newPasswordErr").innerHTML = "";
    document.getElementById("confirmPasswordErr").innerHTML = "";

    if (currentPassword === "") {
        document.getElementById("currentPasswordErr").innerHTML = "Current password is required.";
        flag = false;
    }

    if (newPassword === "") {
        document.getElementById("newPasswordErr").innerHTML = "New password is required.";
        flag = false;
    }

    if (newPassword.length < 6 && newPassword !== "") {
        document.getElementById("newPasswordErr").innerHTML = "Password must be at least 6 characters.";
        flag = false;
    }

    if (confirmPassword === "") {
        document.getElementById("confirmPasswordErr").innerHTML = "Please confirm new password.";
        flag = false;
    }

    if (newPassword !== confirmPassword) {
        document.getElementById("confirmPasswordErr").innerHTML = "Passwords do not match.";
        flag = false;
    }

    return flag;
}