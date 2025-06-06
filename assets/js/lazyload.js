(function() {
  var fadeDuration = window.lazyloadnav_settings.fade_duration || 300;
  var containerSelector = window.lazyloadnav_settings.container || "main";
  var debugMode = window.lazyloadnav_settings.debug_mode || false;

  function fadeOut(element, duration) {
    return new Promise(function(resolve) {
      element.style.transition = "opacity " + duration + "ms";
      element.style.opacity = 0;
      setTimeout(resolve, duration);
    });
  }

  function fadeIn(element, duration) {
    return new Promise(function(resolve) {
      element.style.transition = "opacity " + duration + "ms";
      element.style.opacity = 1;
      setTimeout(resolve, duration);
    });
  }

  function showLoading() {
    if (debugMode) {
      console.log("Showing loading indicator");
    }
    var loadingEl = document.getElementById("loading");
    if (loadingEl) {
      loadingEl.style.display = "block";
    }
  }

  function hideLoading() {
    if (debugMode) {
      console.log("Hiding loading indicator");
    }
    var loadingEl = document.getElementById("loading");
    if (loadingEl) {
      loadingEl.style.display = "none";
    }
  }

  var LazyloadNav = {
    init: function() {
      if (debugMode) {
        console.log("Lazy Loading and Navigation: Debug mode enabled");
        console.log("Settings:", window.lazyloadnav_settings);
      }
      document.dispatchEvent(new CustomEvent("contentLoaded"));
      if (!document.getElementById("loading")) {
        var loadingDiv = document.createElement("div");
        loadingDiv.id = "loading";
        loadingDiv.style.display = "none";
        loadingDiv.style.position = "fixed";
        loadingDiv.style.top = "50%";
        loadingDiv.style.left = "50%";
        loadingDiv.style.transform = "translate(-50%, -50%)";
        loadingDiv.style.backgroundColor = "#fff";
        loadingDiv.style.padding = "10px";
        loadingDiv.style.border = "1px solid #ccc";
        loadingDiv.style.zIndex = "1000";
        loadingDiv.textContent = window.lazyloadnav_strings.loading + " ...";
        document.body.appendChild(loadingDiv);
      }
    },
    loadPage: function(urlPath) {
      if (debugMode) {
        console.log("Loading page:", urlPath);
      }
      showLoading();
      fetch(urlPath, { method: "GET", credentials: "same-origin" })
        .then(function(response) {
          return response.text();
        })
        .then(function(responseText) {
          if (debugMode) {
            console.log("AJAX response received:", responseText);
          }
          var parser = new DOMParser();
          var doc = parser.parseFromString(responseText, "text/html");
          var newTitle = "";
          var titleElem = doc.querySelector("title");
          if (titleElem) {
            newTitle = titleElem.textContent;
            document.title = newTitle;
          }
          var newContainer = doc.querySelector(containerSelector);
          var newContent = newContainer ? newContainer.innerHTML : "";
          var currentContainer = document.querySelector(containerSelector);
          if (newContent && currentContainer) {
            fadeOut(currentContainer, fadeDuration).then(function() {
              currentContainer.innerHTML = newContent;
              fadeIn(currentContainer, fadeDuration).then(function() {
                window.scrollTo({ top: 0, behavior: "smooth" });
              });
            });
          }
          hideLoading();
          window.history.pushState(
            { html: newContent, pageTitle: newTitle },
            "",
            urlPath
          );
          document.dispatchEvent(new CustomEvent("contentLoaded"));
        })
        .catch(function(error) {
          console.error("AJAX request failed for URL: " + urlPath, error);
          hideLoading();
        });
    }
  };

  document.addEventListener("DOMContentLoaded", function() {
    LazyloadNav.init();
  });

  document.addEventListener("click", function(e) {
    var el = e.target;
    while (el && el.tagName !== "A") {
      el = el.parentElement;
    }
    if (el && el.tagName === "A") {
      var href = el.getAttribute("href");
      if (
        !href ||
        href.indexOf("#") === 0 ||
        el.getAttribute("target") === "_blank" ||
        href.indexOf("mailto:") === 0 ||
        href.indexOf("tel:") === 0
      ) {
        return;
      }
      if (href.indexOf("http") === 0 && href.indexOf(window.location.origin) === -1) {
        return;
      }
      e.preventDefault();
      LazyloadNav.loadPage(href);
    }
  });

  window.addEventListener("popstate", function(e) {
    if (e.state) {
      if (debugMode) {
        console.log("Popstate event triggered:", e.state);
      }
      var currentContainer = document.querySelector(containerSelector);
      if (currentContainer) {
        fadeOut(currentContainer, fadeDuration).then(function() {
          currentContainer.innerHTML = e.state.html;
          fadeIn(currentContainer, fadeDuration);
        });
      }
      document.title = e.state.pageTitle || document.title;
      document.dispatchEvent(new CustomEvent("contentLoaded"));
    }
  });
})();

