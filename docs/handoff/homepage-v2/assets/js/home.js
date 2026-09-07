(function () {
  const header = document.querySelector("[data-header]");
  const menuToggle = document.querySelector(".menu-toggle");
  const languagePicker = document.querySelector("[data-language-picker]");
  const languageTrigger = languagePicker?.querySelector(".language-trigger");

  const closeLanguage = () => {
    languagePicker?.classList.remove("is-open");
    languageTrigger?.setAttribute("aria-expanded", "false");
  };

  menuToggle?.addEventListener("click", () => {
    const isOpen = header.classList.toggle("menu-open");
    menuToggle.setAttribute("aria-expanded", String(isOpen));
    menuToggle.setAttribute("aria-label", isOpen ? "Close menu" : "Open menu");
    closeLanguage();
  });

  languageTrigger?.addEventListener("click", (event) => {
    event.stopPropagation();
    const isOpen = languagePicker.classList.toggle("is-open");
    languageTrigger.setAttribute("aria-expanded", String(isOpen));
  });

  document.addEventListener("click", (event) => {
    if (!languagePicker?.contains(event.target)) closeLanguage();
  });

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") return;
    closeLanguage();
    header?.classList.remove("menu-open");
    menuToggle?.setAttribute("aria-expanded", "false");
  });

  document.querySelectorAll("[data-accordion] .appointment-item > button").forEach((button) => {
    button.addEventListener("click", () => {
      const item = button.closest(".appointment-item");
      const accordion = item.closest("[data-accordion]");
      const wasOpen = item.classList.contains("is-open");

      accordion.querySelectorAll(".appointment-item").forEach((sibling) => {
        sibling.classList.remove("is-open");
        sibling.querySelector("button")?.setAttribute("aria-expanded", "false");
      });

      if (!wasOpen) {
        item.classList.add("is-open");
        button.setAttribute("aria-expanded", "true");
      }
    });
  });

  const revealItems = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    const observer = new IntersectionObserver((entries, instance) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add("is-visible");
        instance.unobserve(entry.target);
      });
    }, { rootMargin: "0px 0px -8%", threshold: 0.08 });

    revealItems.forEach((item) => observer.observe(item));
  } else {
    revealItems.forEach((item) => item.classList.add("is-visible"));
  }

})();
