function openModal(id) {
    document.getElementById(id).classList.add("open");
}

function closeModal(id) {
    document.getElementById(id).classList.remove("open");
}

function openEditSpec(id, name, desc) {
    document.getElementById("edit_spec_id").value   = id;
    document.getElementById("edit_spec_name").value = name;
    document.getElementById("edit_spec_desc").value = desc;
    openModal("editSpecModal");
}

function validateSpec(form) {
    var name = form.name.value.trim();
    var flag = true;

    document.getElementById("nameErr").innerHTML = "";

    if (name === "") {
        document.getElementById("nameErr").innerHTML = "Name is required.";
        flag = false;
    }
    return flag;
}