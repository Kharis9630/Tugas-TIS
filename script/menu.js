document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector(".menu-toggle");
    const sidebar = document.querySelector(".sidebar");
    let isOpen = false;

    menuToggle.addEventListener("click", function () {
        if (isOpen) {
            sidebar.style.transform = "translateX(-100%)";
            document.body.style.paddingLeft = "0";
        } else {
            sidebar.style.transform = "translateX(0)";
            document.body.style.paddingLeft = "250px";
        }
        isOpen = !isOpen;
    });
});
