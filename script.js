/* =========================================================
   Bainevier Plumbers & Contractors — site script
   ========================================================= */
(function () {
  "use strict";

  /* ---------- Footer year ---------- */
  document.querySelectorAll("[data-year]").forEach(function (el) {
    el.textContent = new Date().getFullYear();
  });

  /* ---------- Rotating typewriter tagline ---------- */
  var twEl = document.querySelector("[data-typewriter]");
  if (twEl) {
    var words = (twEl.getAttribute("data-typewriter") || "")
      .split(",")
      .map(function (w) { return w.trim(); })
      .filter(Boolean);
    var reduceMotion = window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (!words.length) {
      // no-op
    } else if (reduceMotion) {
      twEl.textContent = words[0];
    } else {
      (function () {
        var wordIndex = 0, charIndex = 0, deleting = false;
        var TYPE_MS = 70, DELETE_MS = 40, HOLD_MS = 1500, GAP_MS = 400;
        function tick() {
          var current = words[wordIndex];
          if (!deleting) {
            charIndex++;
            twEl.textContent = current.slice(0, charIndex);
            if (charIndex === current.length) {
              deleting = true;
              setTimeout(tick, HOLD_MS);
            } else {
              setTimeout(tick, TYPE_MS);
            }
          } else {
            charIndex--;
            twEl.textContent = current.slice(0, charIndex);
            if (charIndex === 0) {
              deleting = false;
              wordIndex = (wordIndex + 1) % words.length;
              setTimeout(tick, GAP_MS);
            } else {
              setTimeout(tick, DELETE_MS);
            }
          }
        }
        tick();
      })();
    }
  }

  /* ---------- Mobile nav toggle ---------- */
  var toggle = document.querySelector(".nav-toggle");
  var nav = document.querySelector(".main-nav");
  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var open = nav.classList.toggle("open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      toggle.innerHTML = open ? "&#10005;" : "&#9776;";
    });
    nav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        nav.classList.remove("open");
        toggle.setAttribute("aria-expanded", "false");
        toggle.innerHTML = "&#9776;";
      });
    });
  }

  /* ---------- Active nav link based on current page ---------- */
  var here = location.pathname.split("/").pop() || "index.html";
  document.querySelectorAll(".main-nav a").forEach(function (link) {
    var target = link.getAttribute("href");
    if (target === here || (here === "" && target === "index.html")) {
      link.classList.add("active");
      link.setAttribute("aria-current", "page");
    }
  });

  /* ---------- Scroll reveal ---------- */
  var revealEls = document.querySelectorAll(".reveal");
  var revealSupported = false;
  if ("IntersectionObserver" in window && revealEls.length) {
    try {
      var io = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add("in-view");
              io.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.15 }
      );
      revealEls.forEach(function (el) { io.observe(el); });
      revealSupported = true;
    } catch (err) {
      revealSupported = false;
    }
  }
  if (!revealSupported) {
    revealEls.forEach(function (el) { el.classList.add("in-view"); });
  }
  // Safety net: whatever happens above, never leave content permanently
  // hidden (covers older browsers, slow observers, or anything unforeseen).
  window.setTimeout(function () {
    revealEls.forEach(function (el) { el.classList.add("in-view"); });
  }, 2500);

  /* ---------- Animated stat counters ---------- */
  var statEls = document.querySelectorAll("[data-count-to]");
  if ("IntersectionObserver" in window && statEls.length) {
    var statIO = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          var el = entry.target;
          var end = parseInt(el.getAttribute("data-count-to"), 10);
          var suffix = el.getAttribute("data-suffix") || "";
          var start = 0;
          var duration = 1100;
          var startTime = null;
          function step(ts) {
            if (!startTime) startTime = ts;
            var progress = Math.min((ts - startTime) / duration, 1);
            var value = Math.floor(progress * (end - start) + start);
            el.textContent = value + suffix;
            if (progress < 1) requestAnimationFrame(step);
          }
          requestAnimationFrame(step);
          statIO.unobserve(el);
        });
      },
      { threshold: 0.5 }
    );
    statEls.forEach(function (el) { statIO.observe(el); });
  }

  /* ---------- Lightbox for gallery & before/after images ---------- */
  var lightbox = document.querySelector(".lightbox");
  if (lightbox) {
    var lbImg = lightbox.querySelector("img");
    var lbCaption = lightbox.querySelector(".lightbox-caption");
    var closeBtn = lightbox.querySelector(".lightbox-close");

    function openLightbox(src, alt) {
      lbImg.src = src;
      lbImg.alt = alt || "";
      lbCaption.textContent = alt || "";
      lightbox.classList.add("open");
      document.body.style.overflow = "hidden";
      closeBtn.focus();
    }
    function closeLightbox() {
      lightbox.classList.remove("open");
      document.body.style.overflow = "";
      lbImg.src = "";
    }

    document.querySelectorAll("[data-lightbox] img").forEach(function (img) {
      img.parentElement.addEventListener("click", function () {
        openLightbox(img.getAttribute("src"), img.getAttribute("alt"));
      });
      img.parentElement.setAttribute("tabindex", "0");
      img.parentElement.setAttribute("role", "button");
      img.parentElement.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          openLightbox(img.getAttribute("src"), img.getAttribute("alt"));
        }
      });
    });

    closeBtn.addEventListener("click", closeLightbox);
    lightbox.addEventListener("click", function (e) {
      if (e.target === lightbox) closeLightbox();
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && lightbox.classList.contains("open")) closeLightbox();
    });
  }

  /* ---------- Quote form: validation + WhatsApp handoff ---------- */
  var form = document.querySelector(".quote-form");
  if (form) {
    var statusBox = document.querySelector(".form-status");

    function setError(field, message) {
      var wrapper = field.closest(".field");
      if (!wrapper) return;
      wrapper.classList.add("invalid");
      var msg = wrapper.querySelector(".error-msg");
      if (msg) msg.textContent = message;
    }
    function clearError(field) {
      var wrapper = field.closest(".field");
      if (!wrapper) return;
      wrapper.classList.remove("invalid");
    }

    form.querySelectorAll("input, select, textarea").forEach(function (field) {
      field.addEventListener("input", function () { clearError(field); });
      field.addEventListener("change", function () { clearError(field); });
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      var name = form.querySelector("#name");
      var phone = form.querySelector("#phone");
      var date = form.querySelector("#date");
      var service = form.querySelector("#service");
      var message = form.querySelector("#message");

      var valid = true;
      [name, phone, service].forEach(function (f) { clearError(f); });

      if (!name.value.trim()) { setError(name, "Please enter your name."); valid = false; }
      var phoneDigits = phone.value.replace(/[^0-9+]/g, "");
      if (phoneDigits.length < 9) { setError(phone, "Please enter a valid phone number."); valid = false; }
      if (!service.value) { setError(service, "Please select a service."); valid = false; }

      if (!valid) {
        statusBox.classList.remove("show");
        var firstInvalid = form.querySelector(".invalid input, .invalid select");
        if (firstInvalid) firstInvalid.focus();
        return;
      }

      var lines = [
        "New quote request — Bainevier Plumbers & Contractors",
        "Name: " + name.value.trim(),
        "Phone: " + phone.value.trim(),
        date.value ? "Preferred date: " + date.value : null,
        "Service: " + service.value,
        message.value.trim() ? "Details: " + message.value.trim() : null
      ].filter(Boolean);

      var text = encodeURIComponent(lines.join("\n"));
      var url = "https://wa.me/256702714729?text=" + text;

      statusBox.textContent = "Opening WhatsApp with your request. If a new tab didn't open, " + 
        "use the link that just appeared below the button.";
      statusBox.classList.add("show");

      var popup = window.open(url, "_blank");
      if (!popup) {
        var fallback = document.createElement("a");
        fallback.href = url;
        fallback.target = "_blank";
        fallback.rel = "noopener";
        fallback.textContent = "Tap here to continue on WhatsApp";
        fallback.style.display = "inline-block";
        fallback.style.marginTop = "8px";
        fallback.style.fontWeight = "600";
        fallback.style.color = "#0a5ea8";
        statusBox.appendChild(document.createElement("br"));
        statusBox.appendChild(fallback);
      }
    });
  }
})();
