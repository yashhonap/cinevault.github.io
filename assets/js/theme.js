(function () {
  const body = document.body;
  const menuToggle = document.querySelector(".js-menu-toggle");
  const siteNav = document.querySelector(".js-site-nav");
  const authModal = document.querySelector(".js-auth-modal");
  const authOpeners = document.querySelectorAll(".js-auth-open");
  const authClosers = document.querySelectorAll(".js-auth-close");
  const drawer = document.querySelector(".js-favorites-drawer");
  const drawerOpeners = document.querySelectorAll(".js-favorites-open");
  const drawerClosers = document.querySelectorAll(".js-favorites-close");
  const favoritesList = document.querySelector(".js-favorites-list");
  const favoritesCount = document.querySelector(".js-favorites-count");
  const storageKey = "cinevaultFavorites";
  let hasLoadedAccountFavorites = false;

  const settings = window.cinevaultSettings || {};

  const escapeHtml = (value) =>
    String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");

  const safeUrl = (value) => {
    try {
      const url = new URL(String(value || ""), window.location.origin);
      return ["http:", "https:"].includes(url.protocol) ? url.href : "#";
    } catch (error) {
      return "#";
    }
  };

  const readFavorites = () => {
    try {
      return JSON.parse(window.localStorage.getItem(storageKey)) || [];
    } catch (error) {
      return [];
    }
  };

  const writeFavorites = (items) => {
    window.localStorage.setItem(storageKey, JSON.stringify(items));
  };

  const mergeFavorites = (localItems, remoteItems) => {
    const merged = [...remoteItems, ...localItems].filter(Boolean);
    return merged.filter((item, index) => merged.findIndex((candidate) => candidate.id === item.id) === index);
  };

  const syncFavorites = (items) => {
    if (!settings.isLoggedIn || !settings.ajaxUrl || !settings.favoritesNonce) return;

    const bodyData = new FormData();
    bodyData.append("action", "cinevault_save_favorites");
    bodyData.append("nonce", settings.favoritesNonce);
    bodyData.append("favorites", JSON.stringify(items));

    window.fetch(settings.ajaxUrl, {
      method: "POST",
      credentials: "same-origin",
      body: bodyData,
    }).catch(() => {});
  };

  const loadAccountFavorites = () => {
    if (hasLoadedAccountFavorites || !settings.isLoggedIn || !settings.ajaxUrl || !settings.favoritesNonce) return;
    hasLoadedAccountFavorites = true;

    const bodyData = new FormData();
    bodyData.append("action", "cinevault_load_favorites");
    bodyData.append("nonce", settings.favoritesNonce);

    window
      .fetch(settings.ajaxUrl, {
        method: "POST",
        credentials: "same-origin",
        body: bodyData,
      })
      .then((response) => response.json())
      .then((response) => {
        if (!response.success || !Array.isArray(response.data)) return;
        const merged = mergeFavorites(readFavorites(), response.data);
        writeFavorites(merged);
        renderFavorites(false);
      })
      .catch(() => {});
  };

  const lockBody = () => body.classList.add("is-locked");
  const unlockBody = () => {
    if (!document.querySelector(".auth-modal.is-open") && !document.querySelector(".favorites-drawer.is-open")) {
      body.classList.remove("is-locked");
    }
  };

  const openModal = (element) => {
    if (!element) return;
    element.classList.add("is-open");
    element.setAttribute("aria-hidden", "false");
    lockBody();
  };

  const closeModal = (element) => {
    if (!element) return;
    element.classList.remove("is-open");
    element.setAttribute("aria-hidden", "true");
    unlockBody();
  };

  const normalizeButton = (button, isActive) => {
    button.classList.toggle("is-active", isActive);
    button.setAttribute("aria-label", isActive ? "Remove from favorites" : "Add to favorites");
    const icon = button.querySelector("span[aria-hidden='true']");
    if (icon) {
      icon.textContent = isActive ? "\u2665" : "\u2661";
    } else if (button.textContent.trim().toLowerCase().includes("save")) {
      button.textContent = isActive ? "Saved to Favorites" : "Save to Favorites";
    }
  };

  const updateFavoriteButtons = () => {
    const ids = new Set(readFavorites().map((item) => item.id));
    document.querySelectorAll(".js-favorite-toggle").forEach((button) => {
      normalizeButton(button, ids.has(button.dataset.id));
    });
  };

  const renderFavorites = (shouldSync = true) => {
    const items = readFavorites();
    if (favoritesCount) favoritesCount.textContent = String(items.length);

    if (!favoritesList) {
      updateFavoriteButtons();
      if (shouldSync) syncFavorites(items);
      return;
    }

    if (!items.length) {
      favoritesList.innerHTML = '<div class="empty-state">Your saved movies and series will appear here.</div>';
      updateFavoriteButtons();
      if (shouldSync) syncFavorites(items);
      return;
    }

    favoritesList.innerHTML = items
      .map(
        (item) => {
          const openUrl = safeUrl(item.affiliate || item.url);
          return `
          <article class="favorite-item">
            <img src="${safeUrl(item.image)}" alt="">
            <div>
              <h3>${escapeHtml(item.title)}</h3>
              <p>${escapeHtml(item.meta || "")}</p>
              <div class="favorite-actions">
                <a class="tiny-button" href="${openUrl}" target="_blank" rel="nofollow sponsored noopener">Open</a>
                <button class="tiny-button js-remove-favorite" type="button" data-id="${escapeHtml(item.id)}">Remove</button>
              </div>
            </div>
          </article>
        `;
        }
      )
      .join("");

    updateFavoriteButtons();
    if (shouldSync) syncFavorites(items);
  };

  const toggleFavorite = (button) => {
    const item = {
      id: button.dataset.id,
      title: button.dataset.title,
      url: button.dataset.url,
      affiliate: button.dataset.affiliate,
      image: button.dataset.image,
      meta: button.dataset.meta,
    };

    if (!item.id) return;

    const items = readFavorites();
    const exists = items.some((favorite) => favorite.id === item.id);
    const nextItems = exists ? items.filter((favorite) => favorite.id !== item.id) : [item, ...items];
    writeFavorites(nextItems);
    renderFavorites();
  };

  if (menuToggle && siteNav) {
    menuToggle.addEventListener("click", () => {
      const isOpen = siteNav.classList.toggle("is-open");
      menuToggle.setAttribute("aria-expanded", String(isOpen));
    });
  }

  authOpeners.forEach((button) => button.addEventListener("click", () => openModal(authModal)));
  authClosers.forEach((button) => button.addEventListener("click", () => closeModal(authModal)));
  drawerOpeners.forEach((button) => button.addEventListener("click", () => {
    loadAccountFavorites();
    renderFavorites();
    openModal(drawer);
  }));
  drawerClosers.forEach((button) => button.addEventListener("click", () => closeModal(drawer)));

  document.addEventListener("click", (event) => {
    const favoriteButton = event.target.closest(".js-favorite-toggle");
    if (favoriteButton) {
      toggleFavorite(favoriteButton);
    }

    const removeButton = event.target.closest(".js-remove-favorite");
    if (removeButton) {
      writeFavorites(readFavorites().filter((item) => item.id !== removeButton.dataset.id));
      renderFavorites();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") return;
    closeModal(authModal);
    closeModal(drawer);
  });

  loadAccountFavorites();
  renderFavorites(false);
})();
