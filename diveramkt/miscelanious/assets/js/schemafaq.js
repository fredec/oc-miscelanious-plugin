document.addEventListener("click", function(e) {
    if (e.target.classList.contains("faq-question")) {
        const li = e.target.closest("li");
        li.classList.toggle("active");
    }
});