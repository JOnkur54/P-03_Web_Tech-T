function openModal(id) {
    document.getElementById(id).classList.add("open");
}

function closeModal(id) {
    document.getElementById(id).classList.remove("open");
}

function openEditRec(id, name, email, phone) {
    document.getElementById("edit_rec_id").value    = id;
    document.getElementById("edit_rec_name").value  = name;
    document.getElementById("edit_rec_email").value = email;
    document.getElementById("edit_rec_phone").value = phone;
    openModal("editRecModal");
}

function validateRec(form) {
    var name  = form.name.value.trim();
    var email = form.email.value.trim();
    var pass  = form.password.value.trim();
    var flag  = true;

    document.getElementById("recNameErr").innerHTML  = "";
    document.getElementById("recEmailErr").innerHTML = "";
    document.getElementById("recPassErr").innerHTML  = "";

    if (name === "") {
        document.getElementById("recNameErr").innerHTML = "Name is required.";
        flag = false;
    }
    if (email === "") {
        document.getElementById("recEmailErr").innerHTML = "Email is required.";
        flag = false;
    }
    if (pass === "") {
        document.getElementById("recPassErr").innerHTML = "Password is required.";
        flag = false;
    }
    return flag;
}