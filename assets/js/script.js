const img = document.querySelector('#Hero-Banner-Full img');

if (img.complete) {
  onImageLoaded();
} else {
  img.addEventListener('load', onImageLoaded);
}

function onImageLoaded() {
  document.querySelector('#loader-animation').style.display = 'none';
}

document.addEventListener("DOMContentLoaded", function () {
    const header = document.querySelector(".uc-header");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 100) {
            header.style.background = "rgba(255, 255, 255, 0.2)"; // Glassmorphism effect
            header.style.backdropFilter = "blur(10px)";
            header.style.webkitBackdropFilter = "blur(10px)";
            header.style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.1)"; // Optional shadow for depth
        } else {
            header.style.background = "#E31E23"; // Original solid color
            header.style.backdropFilter = "none";
            header.style.webkitBackdropFilter = "none";
            header.style.boxShadow = "none";
        }
    });

    const container = document.querySelector("#products-carousal #container");
    let scrollAmount = 0;
    const scrollStep = 300;
    const scrollSpeed = 4000;

    function autoScroll() {
        if (container) {
            scrollAmount += scrollStep;
            if (scrollAmount >= container.scrollWidth - container.clientWidth) {
                scrollAmount = 0;
            }
            // container.scrollTo({
            //     left: scrollAmount,
            //     behavior: "smooth"
            // });

            container.style.scrollBehavior = "smooth";
            container.style.left = `${-scrollAmount}px`;
        }
    }

    setInterval(autoScroll, scrollSpeed);
});
