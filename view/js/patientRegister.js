function validate(form) {
    var name            = form.name.value.trim();
    var email           = form.email.value.trim();
    var phone           = form.phone.value.trim();
    var password        = form.password.value;
    var confirmPassword = form.confirm_password.value;
    var dob             = form.dob.value;
    var flag = true;

    document.getElementById("nameErr").innerHTML            = "";
    document.getElementById("emailErr").innerHTML           = "";
    document.getElementById("phoneErr").innerHTML           = "";
    document.getElementById("passwordErr").innerHTML        = "";
    document.getElementById("confirmPasswordErr").innerHTML = "";
    document.getElementById("dobErr").innerHTML             = "";

    if (name === "") {
        document.getElementById("nameErr").innerHTML = "Name is required.";
        flag = false;
    } else if (name.length < 2) {
        document.getElementById("nameErr").innerHTML = "Name must be at least 2 characters.";
        flag = false;
    }

    if (email === "") {
        document.getElementById("emailErr").innerHTML = "Email is required.";
        flag = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById("emailErr").innerHTML = "Invalid email format.";
        flag = false;
    }

    if (phone === "") {
        document.getElementById("phoneErr").innerHTML = "Phone is required.";
        flag = false;
    } else if (phone.length < 10) {
        document.getElementById("phoneErr").innerHTML = "Phone must be at least 10 digits.";
        flag = false;
    }

    if (password === "") {
        document.getElementById("passwordErr").innerHTML = "Password is required.";
        flag = false;
    } else if (password.length < 6) {
        document.getElementById("passwordErr").innerHTML = "Password must be at least 6 characters.";
        flag = false;
    }

    if (confirmPassword === "") {
        document.getElementById("confirmPasswordErr").innerHTML = "Please confirm your password.";
        flag = false;
    } else if (password !== confirmPassword) {
        document.getElementById("confirmPasswordErr").innerHTML = "Passwords do not match.";
        flag = false;
    }

    if (dob === "") {
        document.getElementById("dobErr").innerHTML = "Date of birth is required.";
        flag = false;
    }

    return flag;
}