document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (e) {
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const address = form.address.value.trim();
    const contact = form.contact.value.trim();
    const dob = form.dob.value.trim();
    const password = form.password.value;
    const confirmPassword = form.confirm_password.value;
    const role = form.role.value;

    // Field validation
    if (!role || !name || !email || !address || !contact || !dob || !password || !confirmPassword) {
      alert("Please fill in all the fields.");
      e.preventDefault();
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert("Please enter a valid email address.");
      e.preventDefault();
      return;
    }

    if (!/^\d{10,}$/.test(contact)) {
      alert("Please enter a valid contact number (at least 10 digits).");
      e.preventDefault();
      return;
    }

    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      e.preventDefault();
      return;
    }

    if (password.length < 6) {
      alert("Password must be at least 6 characters long.");
      e.preventDefault();
      return;
    }

    const dobDate = new Date(dob);
    const today = new Date();
    const age = today.getFullYear() - dobDate.getFullYear();
    if (dobDate > today || age < 18) {
      alert("You must be at least 18 years old to register.");
      e.preventDefault();
      return;
    }
  });
});
