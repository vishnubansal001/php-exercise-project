document.addEventListener('DOMContentLoaded', function () {
    const registrationForm = document.getElementById('registrationForm');

    registrationForm.addEventListener('submit', function (event) {
        let valid = true;
        const errorMessages = [];

        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!firstName) {
            valid = false;
            errorMessages.push("First name is required.");
        }
        
        if (!lastName) {
            valid = false;
            errorMessages.push("Last name is required.");
        }
        
        if (!email) {
            valid = false;
            errorMessages.push("Email is required.");
        } else {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                valid = false;
                errorMessages.push("Invalid email format.");
            }
        }
        
        if (!password) {
            valid = false;
            errorMessages.push("Password is required.");
        }

        if (!valid) {
            event.preventDefault();
            alert(errorMessages.join("\n"));
        }
    });
});
