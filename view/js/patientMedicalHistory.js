function validate(form) {
    var note = form.note_text.value.trim();
    var flag = true;

    document.getElementById("noteErr").innerHTML = "";

    if (note === "") {
        document.getElementById("noteErr").innerHTML = "Please enter a note.";
        flag = false;
    }
    return flag;
}