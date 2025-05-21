let ENABLE_PAGE_PRELOADER = !0,
  DEFAULT_DARK_MODE = !1,
  USE_LOCAL_STORAGE = !0,
  USE_SYSTEM_PREFERENCES = !1,
  DEFAULT_BREAKPOINTS = {
    xs: 0,
    sm: 576,
    md: 768,
    lg: 992,
    xl: 1200,
    xxl: 1400,
  };
document.addEventListener("DOMContentLoaded", () => {
  html.classList.add("dom-ready");
});
const updateScrollWidth = () =>
  document.documentElement.style.setProperty(
    "--body-scroll-width",
    window.innerWidth - document.documentElement.clientWidth + "px"
  );
window.addEventListener("resize", updateScrollWidth), updateScrollWidth();
const html = document.documentElement,
  setupBp = (e, t, a = "min") => {
    const n = matchMedia(`(${a}-width: ${t}px)`),
      o = `bp-${e}${"max" === a ? "-max" : ""}`,
      d = () => html.classList.toggle(o, n.matches);
    (n.onchange = d), d();
  };
Object.entries(DEFAULT_BREAKPOINTS).forEach(([e, t]) => {
  setupBp(e, t, "min"), setupBp(e, t - 1, "max");
});
const isDarkMode = () => html.classList.contains("uc-dark"),
  setDarkMode = (e) => {
    (e = !!e),
      isDarkMode() !== e &&
        (html.classList.toggle("uc-dark", e),
        window.dispatchEvent(new CustomEvent("darkmodechange")));
  },
  getInitialDarkMode = () =>
    USE_LOCAL_STORAGE && null !== localStorage.getItem("darkMode")
      ? "1" === localStorage.getItem("darkMode")
      : USE_SYSTEM_PREFERENCES
      ? matchMedia("(prefers-color-scheme: dark)").matches
      : DEFAULT_DARK_MODE;
setDarkMode(getInitialDarkMode());
const dark = new URLSearchParams(location.search).get("dark");
document.addEventListener("DOMContentLoaded", function () {
  const e = document.getElementById("uc-gdpr-notification");
  localStorage.getItem("gdprAccepted") ||
    setTimeout(function () {
      e?.classList.add("show");
    }, 5e3),
    document
      .getElementById("uc-accept-gdpr")
      ?.addEventListener("click", function () {
        e.classList.remove("show"),
          localStorage.setItem("gdprAccepted", "true");
      }),
    document
      .getElementById("uc-close-gdpr-notification")
      ?.addEventListener("click", function () {
        e.classList.remove("show");
      });
}),
  document.addEventListener("DOMContentLoaded", function () {
    const e = document.getElementById("clients_feedback_area"),
      t = document.getElementById("clients-feedback-toggle-area");
    if (e) {
      const a = e.querySelector("a");
      a &&
        a.addEventListener("click", function (n) {
          n.preventDefault(),
            e.classList.contains("uc-active")
              ? (e.classList.remove("h-700px"),
                e.classList.add("h-auto"),
                t.classList.remove("position-absolute"),
                t.classList.remove("h-300px"),
                t.classList.add("mt-8"),
                a.classList.remove("btn-primary"),
                a.classList.add("btn-secondary"),
                (a.textContent = "Close feedbacks"))
              : (e.classList.remove("h-auto"),
                e.classList.add("h-700px"),
                t.classList.add("position-absolute"),
                t.classList.add("h-300px"),
                t.classList.remove("mt-8"),
                a.classList.add("btn-primary"),
                a.classList.remove("btn-secondary"),
                (a.textContent = "View all feedbacks"));
        });
    }
  });
