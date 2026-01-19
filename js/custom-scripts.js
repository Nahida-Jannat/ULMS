document.addEventListener("DOMContentLoaded", () => {
  // Create button if not present
  let toggleBtn = document.getElementById("darkModeToggle");
  if (!toggleBtn) {
    toggleBtn = document.createElement("button");
    toggleBtn.id = "darkModeToggle";
    toggleBtn.innerText = "🌙 Dark Mode";

    // Insert under header/navbar
    const header = document.querySelector("header");
    if (header) header.insertAdjacentElement('afterend', toggleBtn);
    else document.body.prepend(toggleBtn);
  }

  // Apply saved dark mode
  if (localStorage.getItem("darkMode") === "on") {
    document.body.classList.add("dark-mode");
    toggleBtn.innerText = "☀️ Light Mode";
  }

  // Toggle function
  toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    if (document.body.classList.contains("dark-mode")) {
      localStorage.setItem("darkMode", "on");
      toggleBtn.innerText = "☀️ Light Mode";
    } else {
      localStorage.setItem("darkMode", "off");
      toggleBtn.innerText = "🌙 Dark Mode";
    }
  });
});
