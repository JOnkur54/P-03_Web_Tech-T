function validate(form) {

    var rating = form.rating.value;
    var flag = true;

    var aptId = form.appointment_id.value;
    var ratingErr = document.getElementById("ratingErr_" + aptId);

    if (ratingErr) {
        ratingErr.innerHTML = "";
    }

    if (rating === "") {
        if (ratingErr) {
            ratingErr.innerHTML = "Please select a rating.";
        }
        flag = false;
    }

    return flag;
}