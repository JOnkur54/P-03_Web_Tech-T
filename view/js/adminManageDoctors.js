function openModal(id) {
    document.getElementById(id).classList.add("open");
}

function closeModal(id) {
    document.getElementById(id).classList.remove("open");
}

function openEditModal(btn) {
    document.getElementById("edit_doctor_id").value = btn.getAttribute("data-id");
    document.getElementById("edit_name").value       = btn.getAttribute("data-name");
    document.getElementById("edit_email").value      = btn.getAttribute("data-email");
    document.getElementById("edit_phone").value      = btn.getAttribute("data-phone");
    document.getElementById("edit_spec").value       = btn.getAttribute("data-spec");
    document.getElementById("edit_fee").value        = btn.getAttribute("data-fee");
    document.getElementById("edit_exp").value        = btn.getAttribute("data-exp");
    document.getElementById("edit_license").value    = btn.getAttribute("data-license");
    document.getElementById("edit_bio").value        = btn.getAttribute("data-bio");
    openModal("editDoctorModal");
}

function validateAddDoctor(form) {
    var name  = form.name.value.trim();
    var email = form.email.value.trim();
    var pass  = form.password.value.trim();
    var spec  = form.specialization_id.value;
    var flag  = true;

    document.getElementById("addNameErr").innerHTML     = "";
    document.getElementById("addEmailErr").innerHTML    = "";
    document.getElementById("addPasswordErr").innerHTML = "";
    document.getElementById("addSpecErr").innerHTML     = "";

    if (name === "") {
        document.getElementById("addNameErr").innerHTML = "Name is required.";
        flag = false;
    }
    if (email === "") {
        document.getElementById("addEmailErr").innerHTML = "Email is required.";
        flag = false;
    }
    if (pass === "") {
        document.getElementById("addPasswordErr").innerHTML = "Password is required.";
        flag = false;
    }
    if (spec === "") {
        document.getElementById("addSpecErr").innerHTML = "Specialization is required.";
        flag = false;
    }
    return flag;
}