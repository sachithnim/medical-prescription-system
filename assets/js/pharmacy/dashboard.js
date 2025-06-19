document.addEventListener("DOMContentLoaded", () => {
  console.log("Pharmacy Dashboard loaded");

  // Highlight any quotation boxes with 'rejected' status
  document.querySelectorAll(".box").forEach((box) => {
    const statusText = box.querySelector("p strong:last-child")?.textContent?.toLowerCase();
    if (statusText && statusText.includes("rejected")) {
      box.style.border = "2px solid #e74c3c"; // red border
    } else if (statusText && statusText.includes("accepted")) {
      box.style.border = "2px solid #27ae60"; // green border
    }
  });

  // Optional: Add toggle to collapse/expand quotation tables
  const headers = document.querySelectorAll("h2");
  headers.forEach((header) => {
    header.style.cursor = "pointer";
    header.addEventListener("click", () => {
      const boxes = document.querySelectorAll(".box");
      boxes.forEach(box => {
        box.classList.toggle("collapsed");
      });
    });
  });
});
