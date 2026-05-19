function loadSlots() {
    var doctorId        = document.getElementById("doctorSelect").value;
    var appointmentDate = document.getElementById("appointmentDate").value;
    var container       = document.getElementById("slotsContainer");

    if (doctorId === "" || appointmentDate === "") {
        container.innerHTML = "<p style='font-size:13px;color:#666;'>Select a doctor and date.</p>";
        return;
    }

    container.innerHTML = "<p style='font-size:13px;color:#666;'>Loading...</p>";

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            if (data.slots && data.slots.length > 0) {
                var output = '<div class="slot-buttons">';
                for (var i = 0; i < data.slots.length; i++) {
                    output += '<span class="slot-btn">' + data.slots[i].label + '</span>';
                }
                output += '</div>';
                container.innerHTML = output;
            } else {
                container.innerHTML = "<p style='font-size:13px;color:#666;'>No slots available.</p>";
            }
        }
    };

    xhttp.open("GET", "../../controllers/patientDoctorController.php?action=getSlots&doctor_id=" + doctorId + "&date=" + appointmentDate, true);
    xhttp.send();
}

window.onload = function () {
    var doctorSel = document.getElementById("doctorSelect");
    var dateInput = document.getElementById("appointmentDate");
    if (doctorSel) { doctorSel.onchange = loadSlots; }
    if (dateInput) { dateInput.onchange = loadSlots; }
    if (doctorSel && doctorSel.value && dateInput && dateInput.value) { loadSlots(); }
};