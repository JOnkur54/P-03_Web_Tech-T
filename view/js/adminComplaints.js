function validateResolve(form) {
    var response = form.response.value.trim();
    var id       = form.complaint_id.value;
    var flag     = true;

    var errEl = document.getElementById("resolveErr_" + id);
    if (errEl) { errEl.innerHTML = ""; }

    if (response === "") {
        if (errEl) { errEl.innerHTML = "Please enter a response before resolving."; }
        flag = false;
    }
    return flag;
}