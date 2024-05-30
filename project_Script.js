document.addEventListener('DOMContentLoaded', function () {
    const workoutForm = document.getElementById('workoutForm');
    workoutForm.addEventListener('submit', function (event) {
        const duration = document.getElementById('duration').value;
        const distance = document.getElementById('distance').value;

        if (duration <= 0 || distance <= 0) {
            event.preventDefault();
            alert('Duration and distance must be greater than zero.');
        }
    });
});
