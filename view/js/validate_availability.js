document.addEventListener('DOMContentLoaded', () => {
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    // --- Availability Form Validation ---
    const availForm = document.getElementById('availabilityForm');
    if (availForm) {
        availForm.addEventListener('submit', (e) => {
            let isValid = true;

            days.forEach(day => {
                const checkbox = document.getElementById(`avail_checkbox_${day}`);
                const startInput = document.getElementById(`avail_start_${day}`);
                const endInput = document.getElementById(`avail_end_${day}`);
                const startErr = document.getElementById(`avail_start_error_${day}`);
                const endErr = document.getElementById(`avail_end_error_${day}`);

                // Reset UI
                startErr.textContent = '';
                endErr.textContent = '';
                startInput.classList.remove('error-border');
                endInput.classList.remove('error-border');

                if (checkbox && checkbox.checked) {
                    const startTime = startInput.value;
                    const endTime = endInput.value;

                    if (!startTime || !endTime) {
                        if (!startTime) {
                            startErr.textContent = 'Start time is required.';
                            startInput.classList.add('error-border');
                        }
                        if (!endTime) {
                            endErr.textContent = 'End time is required.';
                            endInput.classList.add('error-border');
                        }
                        isValid = false;
                    } else if (startTime >= endTime) {
                        startErr.textContent = 'Start must be before end.';
                        endErr.textContent = 'End must be after start.';
                        startInput.classList.add('error-border');
                        endInput.classList.add('error-border');
                        isValid = false;
                    }
                }
            });

            if (!isValid) e.preventDefault();
        });

        // Clear errors on interaction
        days.forEach(day => {
            [
                document.getElementById(`avail_checkbox_${day}`),
                document.getElementById(`avail_start_${day}`),
                document.getElementById(`avail_end_${day}`)
            ].filter(Boolean).forEach(el => {
                el.addEventListener('change', () => {
                    document.getElementById(`avail_start_error_${day}`).textContent = '';
                    document.getElementById(`avail_end_error_${day}`).textContent = '';
                    document.getElementById(`avail_start_${day}`).classList.remove('error-border');
                    document.getElementById(`avail_end_${day}`).classList.remove('error-border');
                });
            });
        });
    }

    // --- Leave Form Validation ---
    const leaveForm = document.getElementById('leaveForm');
    if (leaveForm) {
        const leaveDateInput = document.getElementById('leave_date');
        const leaveDateError = document.getElementById('leave_date_error');

        const validateLeave = () => {
            leaveDateError.textContent = '';
            leaveDateInput.classList.remove('error-border');

            if (!leaveDateInput.value) {
                leaveDateError.textContent = 'Date is required.';
                leaveDateInput.classList.add('error-border');
                return false;
            }

            // Parse YYYY-MM-DD manually to avoid timezone shifts
            const [year, month, day] = leaveDateInput.value.split('-').map(Number);
            const selected = new Date(year, month - 1, day);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            selected.setHours(0, 0, 0, 0);

            if (selected < today) {
                leaveDateError.textContent = 'Date cannot be in the past.';
                leaveDateInput.classList.add('error-border');
                return false;
            }
            return true;
        };

        leaveDateInput.addEventListener('input', validateLeave);
        leaveDateInput.addEventListener('change', validateLeave);

        leaveForm.addEventListener('submit', (e) => {
            if (!validateLeave()) e.preventDefault();
        });
    }
});