function loadDoctorSlots() {
    const doctorId = document.getElementById('doctor_id').value;
    const date = document.getElementById('appointment_date').value;
    const target = document.getElementById('slotOptions');
    target.innerHTML = '<option value="">Loading slots...</option>';
    if (!doctorId || !date) {
        target.innerHTML = '<option value="">Select doctor and date first</option>';
        return;
    }
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../controllers/CheckAvailabilityController.php?doctor_id=' + encodeURIComponent(doctorId) + '&date=' + encodeURIComponent(date), true);
    xhr.onload = function () {
        if (xhr.status !== 200) {
            target.innerHTML = '<option value="">Unable to load slots</option>';
            return;
        }
        const response = JSON.parse(xhr.responseText);
        if (response.error) {
            target.innerHTML = '<option value="">' + response.error + '</option>';
            return;
        }
        const slots = response.slots || [];
        if (!slots.length) {
            target.innerHTML = '<option value="">No available slots</option>';
            return;
        }
        target.innerHTML = '<option value="">Select a time slot</option>';
        slots.forEach(function (slot) {
            const option = document.createElement('option');
            option.value = slot;
            option.textContent = slot;
            target.appendChild(option);
        });
    };
    xhr.send();
}

function setAvailabilityDate(element) {
    const date = element.value;
    window.location.href = '../view/receptionist_availability.php?date=' + encodeURIComponent(date);
}
