function validate(form) {

    var name = form.name.value.trim();
    var flag = true;

    document.getElementById("nameErr").innerHTML = "";

    if (name === "") {
        document.getElementById("nameErr").innerHTML = "Name is required.";
        flag = false;
    }

    return flag;
}