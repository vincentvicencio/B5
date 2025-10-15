document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('traitForm');
    const nextBtn = document.getElementById('nextBtn');
    const allRadios = document.querySelectorAll('input[type="radio"]');

    if (!form || !nextBtn || !allRadios.length) return;

    // Count questions
    const likertScaleCount = document.querySelectorAll('.likert-option:first-child').length;
    const totalQuestions = allRadios.length / likertScaleCount;

    function updateProgress() {
        const answered = new Set();
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            answered.add(radio.name);
        });
        nextBtn.disabled = answered.size < totalQuestions;
    }

    allRadios.forEach(radio => {
        radio.addEventListener('change', updateProgress);
    });

    updateProgress();

    form.addEventListener('submit', function (e) {
        const answered = new Set();
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            answered.add(radio.name);
        });

        if (answered.size < totalQuestions) {
            e.preventDefault();
            alert('Please answer all questions before continuing.');
            return false;
        }

        nextBtn.disabled = true;
        nextBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    });
});
