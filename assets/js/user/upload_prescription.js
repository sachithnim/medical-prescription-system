document.addEventListener("DOMContentLoaded", () => {
  const fileInput = document.querySelector('input[type="file"]');
  const form = document.querySelector("form");
  const previewBox = document.createElement("div");
  previewBox.className = "prescription-images";

  // Insert preview box just after file input
  fileInput.parentNode.insertBefore(previewBox, fileInput.nextSibling);

  fileInput.addEventListener("change", () => {
    previewBox.innerHTML = ""; // Clear previews
    const files = fileInput.files;

    if (files.length > 5) {
      alert("You can only upload a maximum of 5 images.");
      fileInput.value = "";
      return;
    }

    Array.from(files).forEach((file) => {
      if (!file.type.startsWith("image/")) {
        alert("Only image files are allowed.");
        fileInput.value = "";
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        const img = document.createElement("img");
        img.src = e.target.result;
        img.className = "prescription";
        previewBox.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
  });

  form.addEventListener("submit", () => {
    alert("Submitting prescription...");
  });

  console.log("upload_prescription.js loaded");
});
