document.addEventListener("DOMContentLoaded", () => {
  const boxes = document.querySelectorAll(".box");
  boxes.forEach(box => {
    box.style.transition = "transform 0.3s ease";
    box.addEventListener("mouseenter", () => box.style.transform = "scale(1.02)");
    box.addEventListener("mouseleave", () => box.style.transform = "scale(1)");
  });

  console.log("pharmacy/view_prescriptions.js loaded");
});
