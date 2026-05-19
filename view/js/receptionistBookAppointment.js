function loadSlots() {
    var doctorId = document.getElementById("doctor_id").value;
    var date     = document.getElementById("appointment_date").value;
    var container = document.getElementById("slotsContainer");

    document.getElementById("appointment_time").value = "";

    if (doctorId === "" || date === "") {
        container.innerHTML = "<p>Select a doctor and date to see available slots.</p>";
        return;
    }

    container.innerHTML = "<p>Loading slots...</p>";

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            if (data.slots && data.slots.length > 0) {
                var html = '<div class="slot-buttons">';
                for (var i = 0; i < data.slots.length; i++) {
                    html += '<button type="button" class="slot-btn" onclick="selectSlot(\'' + data.slots[i].time + '\', this)">' + data.slots[i].label + '</button>';
                }
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = "<p>No available slots for this date.</p>";
            }
        }
    };
    xhr.open("GET", "../../controllers/receptionistBookAppointmentController.php?action=getSlots&doctor_id=" + doctorId + "&date=" + date, true);
    xhr.send();
}

function selectSlot(time, btn) {
    document.getElementById("appointment_time").value = time;
    var buttons = document.querySelectorAll(".slot-btn");
    for (var i = 0; i < buttons.length; i++) { buttons[i].classList.remove("selected"); }
    btn.classList.add("selected");
    document.getElementById("timeErr").innerHTML = "";
}

window.onload = function () {
    var doctorSel = document.getElementById("doctor_id");
    var dateInput = document.getElementById("appointment_date");
    if (doctorSel) { doctorSel.onchange = loadSlots; }
    if (dateInput) { dateInput.onchange = loadSlots; }
};