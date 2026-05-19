function openReschedule(apptId, doctorId) {
    var div = document.getElementById("reschedule_" + apptId);
    div.style.display = div.style.display === "none" ? "block" : "none";
}

function loadRescheduleSlots(apptId, doctorId) {
    var date = document.getElementById("new_date_" + apptId).value;
    var container = document.getElementById("reschedule_slots_" + apptId);

    document.getElementById("new_time_" + apptId).value = "";

    if (date === "") { container.innerHTML = "<p style='font-size:13px;'>Select a date first.</p>"; return; }

    container.innerHTML = "<p style='font-size:13px;'>Loading...</p>";

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            if (data.slots && data.slots.length > 0) {
                var html = '<div class="slot-buttons">';
                for (var i = 0; i < data.slots.length; i++) {
                    html += '<button type="button" class="slot-btn" onclick="selectRescheduleSlot(\'' + data.slots[i].time + '\', ' + apptId + ', this)">' + data.slots[i].label + '</button>';
                }
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = "<p style='font-size:13px;'>No slots available.</p>";
            }
        }
    };
    xhr.open("GET", "../../controllers/receptionistManageAppointmentController.php?action=getSlots&doctor_id=" + doctorId + "&date=" + date, true);
    xhr.send();
}

function selectRescheduleSlot(time, apptId, btn) {
    document.getElementById("new_time_" + apptId).value = time;
    var container = document.getElementById("reschedule_slots_" + apptId);
    var buttons = container.querySelectorAll(".slot-btn");
    for (var i = 0; i < buttons.length; i++) { buttons[i].classList.remove("selected"); }
    btn.classList.add("selected");
}