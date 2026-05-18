function validateAnnouncement(form) {
    var title = form.title.value.trim();
    var body  = form.body.value.trim();
    var flag  = true;

    document.getElementById("titleErr").innerHTML = "";
    document.getElementById("bodyErr").innerHTML  = "";

    if (title === "") {
        document.getElementById("titleErr").innerHTML = "Title is required.";
        flag = false;
    }
    if (body === "") {
        document.getElementById("bodyErr").innerHTML = "Message body is required.";
        flag = false;
    }
    return flag;
}