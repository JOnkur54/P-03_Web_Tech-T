function loadSlots() {

    var doctorId = document.getElementById("doctorSelect").value;
    var appointmentDate = document.getElementById("appointmentDate").value;
    var container = document.getElementById("slotsContainer");

    if (doctorId === "" || appointmentDate === "") {
        container.innerHTML = "<p>Please select doctor and date.</p>";
        return;
    }

    container.innerHTML = "<p>Loading slots...</p>";

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);

            if (data.slots && data.slots.length > 0) {
                var output = '<ul class="slot-list">';
                for (var i = 0; i < data.slots.length; i++) {
                    output += '<li class="slot-item">' + data.slots[i].label + '</li>';
                }
                output += '</ul>';
                container.innerHTML = output;
            } else {
                container.innerHTML = "<p>No slots available.</p>";
            }
        }
    };

    xhttp.open("GET", "../../controllers/patientDoctorController.php?action=getSlots&doctor_id=" + doctorId + "&date=" + appointmentDate, true);
    xhttp.send();
}

document.getElementById("doctorSelect").onchange = loadSlots;
document.getElementById("appointmentDate").onchange = loadSlots;

window.onload = function () {
    var doctorId = document.getElementById("doctorSelect").value;
    var appointmentDate = document.getElementById("appointmentDate").value;

    if (doctorId !== "" && appointmentDate !== "") {
        loadSlots();
    }
};