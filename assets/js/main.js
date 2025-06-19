document.addEventListener("DOMContentLoaded", () => {
  console.log("main.js loaded");

  document.querySelectorAll("button").forEach(button => {
    button.addEventListener("click", () => {
      console.log(`Clicked: ${button.innerText}`);
    });
  });

  const confirmBtns = document.querySelectorAll("[data-confirm]");
  confirmBtns.forEach(btn => {
    btn.addEventListener("click", (e) => {
      const message = btn.getAttribute("data-confirm");
      if (!confirm(message)) {
        e.preventDefault();
      }
    });
  });
});
