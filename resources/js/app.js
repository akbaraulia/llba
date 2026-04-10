import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const drawer = document.getElementById("default-sidebar");
    if (!drawer) {
        return;
    }

    const toggleButtons = document.querySelectorAll("[data-drawer-toggle]");

    toggleButtons.forEach((button) => {
        const targetId =
            button.getAttribute("data-drawer-target") ||
            button.getAttribute("aria-controls");

        if (!targetId || targetId !== drawer.id) {
            return;
        }

        button.addEventListener("click", () => {
            drawer.classList.toggle("-translate-x-full");
        });
    });

    document.addEventListener("click", (event) => {
        if (window.innerWidth >= 640) {
            return;
        }

        const clickedToggle = event.target.closest("[data-drawer-toggle]");
        const clickedInsideDrawer = event.target.closest("#default-sidebar");

        if (!clickedToggle && !clickedInsideDrawer) {
            drawer.classList.add("-translate-x-full");
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && window.innerWidth < 640) {
            drawer.classList.add("-translate-x-full");
        }
    });
});
