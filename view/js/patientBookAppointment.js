function loadSlots() {
    var doctorId        = document.getElementById("doctor_id").value;
    var appointmentDate = document.getElementById("appointment_date").value;
    var container       = document.getElementById("slotsContainer");

    document.getElementById("appointment_time").value = "";

    if (doctorId === "" || appointmentDate === "") {
        container.innerHTML = "<p style='font-size:13px;color:#666;'>Select a doctor and date to view available slots.</p>";
        return;
    }

    container.innerHTML = "<p style='font-size:13px;color:#666;'>Loading slots...</p>";

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            try {
                var data = JSON.parse(this.responseText);
                if (data.slots && data.slots.length > 0) {
                    var output = '<div class="slot-buttons">';
                    for (var i = 0; i < data.slots.length; i++) {
                        var slotTime  = data.slots[i].time;
                        var slotLabel = data.slots[i].label;
                        output += '<button type="button" class="slot-btn" onclick="selectSlot(\'' + slotTime + '\', this)">' + slotLabel + '</button>';
                    }
                    output += '</div>';
                    container.innerHTML = output;
                } else {
                    container.innerHTML = "<p style='font-size:13px;color:#666;'>No slots available for this date.</p>";
                }
            } catch (e) {
                container.innerHTML = "<p style='color:red;font-size:13px;'>Error loading slots. Please try again.</p>";
            }
        }
    };

    xhttp.open("GET", "../../controllers/patientDoctorController.php?action=getSlots&doctor_id=" + doctorId + "&date=" + appointmentDate, true);
    xhttp.send();
}

function selectSlot(time, btn) {
    document.getElementById("appointment_time").value = time;
    var buttons = document.querySelectorAll(".slot-btn");
    for (var i = 0; i < buttons.length; i++) { buttons[i].classList.remove("selected"); }
    btn.classList.add("selected");
    document.getElementById("timeErr").innerHTML = "";
}

function validate(form) {
    var doctorId  = form.doctor_id.value;
    var date      = form.appointment_date.value;
    var time      = form.appointment_time.value;
    var reason    = form.reason.value.trim();
    var flag = true;

    document.getElementById("doctorErr").innerHTML = "";
    document.getElementById("dateErr").innerHTML   = "";
    document.getElementById("timeErr").innerHTML   = "";
    document.getElementById("reasonErr").innerHTML = "";

    if (doctorId === "") { document.getElementById("doctorErr").innerHTML = "Please select a doctor."; flag = false; }
    if (date === "")     { document.getElementById("dateErr").innerHTML   = "Please select a date."; flag = false; }
    if (time === "")     { document.getElementById("timeErr").innerHTML   = "Please select a time slot."; flag = false; }
    if (reason === "")   { document.getElementById("reasonErr").innerHTML = "Please provide a reason."; flag = false; }

    return flag;
}

window.onload = function () {
    var doctorSel = document.getElementById("doctor_id");
    var dateInput = document.getElementById("appointment_date");
    if (doctorSel) { doctorSel.onchange = loadSlots; }
    if (dateInput) { dateInput.onchange = loadSlots; }
    if (doctorSel && doctorSel.value && dateInput && dateInput.value) { loadSlots(); }
};