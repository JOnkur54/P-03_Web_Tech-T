document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('consultationForm');
    
    const fields = {
        symptoms: document.getElementById('symptoms'),
        diagnosis: document.getElementById('diagnosis'),
        prescription: document.getElementById('prescription'),
        follow_up_date: document.getElementById('follow_up_date')
    };

    const errors = {
        symptoms: document.getElementById('symptoms-error'),
        diagnosis: document.getElementById('diagnosis-error'),
        prescription: document.getElementById('prescription-error'),
        follow_up_date: document.getElementById('follow_up_date-error')
    };

    const clearError = (field) => {
        if (errors[field]) errors[field].textContent = '';
        if (fields[field]) fields[field].classList.remove('error');
    };

    const showError = (field, message) => {
        if (errors[field]) errors[field].textContent = message;
        if (fields[field]) fields[field].classList.add('error');
    };

    // Clear errors as the user types
    Object.keys(fields).forEach(field => {
        fields[field].addEventListener('input', () => clearError(field));
    });

    form.addEventListener('submit', (e) => {
        let isValid = true;

        // Validate mandatory fields
        ['symptoms', 'diagnosis', 'prescription'].forEach(field => {
            const value = fields[field].value.trim();
            if (!value) {
                const label = field.charAt(0).toUpperCase() + field.slice(1).replace('_', ' ');
                showError(field, `${label} is required.`);
                isValid = false;
            }
        });

        // Validate follow-up date (only if provided)
        const dateValue = fields.follow_up_date.value;
        if (dateValue) {
            const [year, month, day] = dateValue.split('-').map(Number);
            const selectedDate = new Date(year, month - 1, day);
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            selectedDate.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                showError('follow_up_date', 'Follow-up date cannot be in the past.');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault(); // Stop form submission
        }
    });
});