document.addEventListener("DOMContentLoaded", () => {
  console.log("Loaded view_quotation.js");

  // Confirm accept/reject quotation
  const forms = document.querySelectorAll("form[action='handle_response.php']");
  forms.forEach(form => {
    form.addEventListener("submit", function (e) {
      const clickedButton = e.submitter;
      const action = clickedButton?.value;
      const msg = action === "accept" ? "Are you sure you want to accept this quotation?" : "Are you sure you want to reject this quotation?";

      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });
});
