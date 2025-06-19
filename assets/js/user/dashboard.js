document.addEventListener("DOMContentLoaded", () => {
  const flashMsg = document.querySelector(".flash-success");

  if (flashMsg) {
    setTimeout(() => {
      flashMsg.style.opacity = "0";
    }, 3000);
  }

  console.log("dashboard.js loaded");
});
