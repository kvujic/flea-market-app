document.addEventListener("DOMContentLoaded", () => {
    const completeForm = document.getElementById("completeForm");
    const ratingModal = document.getElementById("rating-modal");
    const stars = document.querySelectorAll("#rating-modal .star");
    const ratingInput = document.getElementById("ratingInput");
    const ratingForm = document.getElementById("ratingForm");
    const errorBox = document.getElementById("ratingError");
    const ratingSubmitBtn = document.getElementById("ratingSubmitBtn");

    if (!completeForm || !ratingModal) return;

    completeForm.addEventListener("submit", (e) => {
        e.preventDefault();
        ratingModal.classList.add("is-open");
        document.body.style.overflow = "hidden";
    });

    stars.forEach(star => {
        star.addEventListener("click", () => {
            const val = parseInt(star.dataset.value, 10);
            ratingInput.value = val;

            stars.forEach(s => s.classList.remove("selected"));

            stars.forEach(s => {
                if (parseInt(s.dataset.value, 10) <= val) {
                    s.classList.add("selected");
                }
            })

            errorBox.textContent = "";
        });
    });

    ratingForm.addEventListener("submit", (e) => {
        if (!ratingInput.value || ratingInput.value === "0") {
            e.preventDefault();
            errorBox.textContent = "星を選んでください。";
            return;
        }
        if (ratingSubmitBtn) ratingSubmitBtn.disabled = true;
    });
});