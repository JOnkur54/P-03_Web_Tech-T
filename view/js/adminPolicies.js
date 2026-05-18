function validatePolicies(form) {
    var cancel  = form.min_cancel_hours.value;
    var advance = form.max_advance_days.value;
    var fee     = form.default_fee.value;
    var flag    = true;

    document.getElementById("cancelErr").innerHTML  = "";
    document.getElementById("advanceErr").innerHTML = "";
    document.getElementById("feeErr").innerHTML     = "";

    if (cancel === "" || parseInt(cancel) < 0) {
        document.getElementById("cancelErr").innerHTML = "Enter a valid number of hours.";
        flag = false;
    }
    if (advance === "" || parseInt(advance) < 1) {
        document.getElementById("advanceErr").innerHTML = "Enter at least 1 day.";
        flag = false;
    }
    if (fee === "" || parseFloat(fee) < 0) {
        document.getElementById("feeErr").innerHTML = "Enter a valid fee.";
        flag = false;
    }
    return flag;
}