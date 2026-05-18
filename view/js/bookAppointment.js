function loadSlots() {
    var doctorId = document.getElementById("doctor_id").value;
    var appointmentDate = document.getElementById("appointment_date").value;
    var container = document.getElementById("slotsContainer");

    // Clear previous selection
    document.getElementById("appointment_time").value = "";

    if (doctorId === "" || appointmentDate === "") {
        container.innerHTML = "<p>Please select a doctor and date to view available slots.</p>";
        return;
    }

    container.innerHTML = "<p>Loading slots...</p>";

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            try {
                var data = JSON.parse(this.responseText);

                if (data.slots && data.slots.length > 0) {
                    var output = '<div class="slot-buttons">';
                    
                    for (var i = 0; i < data.slots.length; i++) {
                        var slotTime = data.slots[i].time || data.slots[i].label;
                        var slotLabel = data.slots[i].label || data.slots[i].time;
                        
                        output += '<button type="button" class="slot-btn" onclick="selectSlot(\'' + slotTime + '\')">' + slotLabel + '</button>';
                    }
                    
                    output += '</div>';
                    container.innerHTML = output;
                } else {
                    container.innerHTML = "<p>No slots available for this date.</p>";
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
                console.log("Response:", this.responseText);
                container.innerHTML = "<p>Error loading slots. Please try again.</p>";
            }
        } else if (this.readyState == 4) {
            container.innerHTML = "<p>Error loading slots. Please check console.</p>";
            console.error("HTTP Status:", this.status);
        }
    };

    xhttp.open("GET", "../../controllers/patientDoctorController.php?action=getSlots&doctor_id=" + doctorId + "&date=" + appointmentDate, true);
    xhttp.send();
}

function selectSlot(time) {
    // Store the selected time in hidden input
    document.getElementById("appointment_time").value = time;
    
    // Remove 'selected' class from all buttons
    var buttons = document.querySelectorAll(".slot-btn");
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove("selected");
    }
    
    // Add 'selected' class to clicked button
    event.target.classList.add("selected");
    
    // Clear error message
    document.getElementById("timeErr").innerHTML = "";
}

function validate(form) {
    var doctorId = form.doctor_id.value;
    var appointmentDate = form.appointment_date.value;
    var appointmentTime = form.appointment_time.value;
    var reason = form.reason.value.trim();

    var flag = true;

    document.getElementById("doctorErr").innerHTML = "";
    document.getElementById("dateErr").innerHTML = "";
    document.getElementById("timeErr").innerHTML = "";
    document.getElementById("reasonErr").innerHTML = "";

    if (doctorId === "") {
        document.getElementById("doctorErr").innerHTML = "Please select a doctor.";
        flag = false;
    }

    if (appointmentDate === "") {
        document.getElementById("dateErr").innerHTML = "Please select an appointment date.";
        flag = false;
    }

    if (appointmentTime === "") {
        document.getElementById("timeErr").innerHTML = "Please select a time slot.";
        flag = false;
    }

    if (reason === "") {
        document.getElementById("reasonErr").innerHTML = "Please provide a reason for visit.";
        flag = false;
    }

    return flag;
}

// Attach event listeners when page loads
window.onload = function() {
    var doctorSelect = document.getElementById("doctor_id");
    var dateInput = document.getElementById("appointment_date");
    
    if (doctorSelect) {
        doctorSelect.onchange = loadSlots;
    }
    
    if (dateInput) {
        dateInput.onchange = loadSlots;
    }
    
    // Auto-load slots if doctor is pre-selected
    if (doctorSelect && doctorSelect.value && dateInput && dateInput.value) {
        loadSlots();
    }
};