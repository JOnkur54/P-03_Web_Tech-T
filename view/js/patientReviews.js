function validate(form) {
    var rating = form.rating.value;
    var aptId  = form.appointment_id.value;
    var flag   = true;

    var errEl = document.getElementById("ratingErr_" + aptId);
    if (errEl) { errEl.innerHTML = ""; }

    if (rating === "") {
        if (errEl) { errEl.innerHTML = "Please select a rating."; }
        flag = false;
    }
    return flag;
}