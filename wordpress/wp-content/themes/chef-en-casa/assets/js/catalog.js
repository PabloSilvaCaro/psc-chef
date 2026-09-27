document.addEventListener("DOMContentLoaded", () => {
  const filterButtons = [...document.querySelectorAll("[data-catalog-filter]")];
  const catalogItems = [...document.querySelectorAll("[data-catalog-item]")];
  const count = document.querySelector("[data-catalog-count]");
  if (filterButtons.length && catalogItems.length) {
    const params = new URLSearchParams(window.location.search);
    const initial = params.get("tipo") || "all";
    const applyFilter = (filter, updateUrl = true) => {
      let visible = 0;
      catalogItems.forEach((item) => { const show = filter === "all" || item.dataset.types.split(" ").includes(filter); item.hidden = !show; if (show) visible += 1; });
      filterButtons.forEach((button) => button.classList.toggle("is-active", button.dataset.catalogFilter === filter));
      if (count) count.textContent = String(visible);
      if (updateUrl) { const url = new URL(window.location.href); filter === "all" ? url.searchParams.delete("tipo") : url.searchParams.set("tipo", filter); window.history.replaceState({}, "", url); }
    };
    filterButtons.forEach((button) => button.addEventListener("click", () => applyFilter(button.dataset.catalogFilter)));
    applyFilter(filterButtons.some((button) => button.dataset.catalogFilter === initial) ? initial : "all", false);
  }

  const explorer = document.querySelector("[data-dish-explorer]");
  if (!explorer) return;
  const category = explorer.querySelector("[data-dish-category]");
  const dishSelect = explorer.querySelector("[data-dish-select]");
  const options = [...dishSelect.options];
  const panels = [...explorer.querySelectorAll("[data-dish-panel]")];
  const params = new URLSearchParams(window.location.search);

  const showDish = (slug, updateUrl = true, shouldScroll = false) => {
    const panel = panels.find((item) => item.dataset.dishPanel === slug) || panels[0];
    if (!panel) return;
    panels.forEach((item) => { item.hidden = item !== panel; });
    dishSelect.value = panel.dataset.dishPanel;
    if (updateUrl) {
      const url = new URL(window.location.href);
      url.searchParams.set("plato", panel.dataset.dishPanel);
      category.value === "all" ? url.searchParams.delete("tipo") : url.searchParams.set("tipo", category.value);
      window.history.replaceState({}, "", url);
    }
    if (shouldScroll) explorer.querySelector(".dish-picker").scrollIntoView({ behavior: "smooth", block: "start" });
  };
  const applyCategory = (value, keepSelection = false) => {
    options.forEach((option) => { const matches = value === "all" || option.dataset.types.split(" ").includes(value); option.hidden = !matches; option.disabled = !matches; });
    const current = options.find((option) => option.value === dishSelect.value && !option.disabled);
    const next = keepSelection && current ? current : options.find((option) => !option.disabled);
    if (next) showDish(next.value);
  };
  category.addEventListener("change", () => applyCategory(category.value));
  dishSelect.addEventListener("change", () => showDish(dishSelect.value));
  explorer.addEventListener("click", (event) => {
    const suggestion = event.target.closest("[data-dish-suggestion]");
    if (!suggestion) return;
    category.value = "all"; applyCategory("all", true); showDish(suggestion.dataset.dishSuggestion, true, true);
  });
  const initialType = [...category.options].some((option) => option.value === params.get("tipo")) ? params.get("tipo") : "all";
  const initialDish = params.get("plato");
  category.value = initialType; applyCategory(initialType, true);
  if (initialDish && options.some((option) => option.value === initialDish && !option.disabled)) showDish(initialDish, false);
});

document.addEventListener("DOMContentLoaded", () => {
  const explorer = document.querySelector("[data-menu-explorer]");
  if (!explorer) return;
  const category = explorer.querySelector("[data-menu-category]");
  const people = explorer.querySelector("[data-menu-people]");
  const menuSelect = explorer.querySelector("[data-menu-select]");
  const options = [...menuSelect.options];
  const panels = [...explorer.querySelectorAll("[data-menu-panel]")];
  const params = new URLSearchParams(window.location.search);

  const updatePeople = () => {
    const quantity = Number(people.value);
    const label = quantity === 1 ? "1 persona" : quantity + " personas";
    explorer.querySelectorAll("[data-menu-people-label], [data-menu-summary-people]").forEach((item) => { item.textContent = label; });
    explorer.querySelectorAll("[data-menu-nutrient]").forEach((item) => {
      const value = Number(item.dataset.base) * quantity;
      item.textContent = value.toLocaleString("es-CL", { minimumFractionDigits: Number(item.dataset.decimals), maximumFractionDigits: Number(item.dataset.decimals) });
    });
    const url = new URL(window.location.href); url.searchParams.set("personas", String(quantity)); window.history.replaceState({}, "", url);
  };
  const showMenu = (slug, updateUrl = true) => {
    const panel = panels.find((item) => item.dataset.menuPanel === slug) || panels[0];
    if (!panel) return;
    panels.forEach((item) => { item.hidden = item !== panel; });
    menuSelect.value = panel.dataset.menuPanel;
    if (updateUrl) {
      const url = new URL(window.location.href); url.searchParams.set("menu", panel.dataset.menuPanel);
      category.value === "all" ? url.searchParams.delete("tipo") : url.searchParams.set("tipo", category.value);
      window.history.replaceState({}, "", url);
    }
  };
  const applyCategory = (value, preserve = false) => {
    options.forEach((option) => { const matches = value === "all" || option.dataset.type === value; option.hidden = !matches; option.disabled = !matches; });
    const current = options.find((option) => option.value === menuSelect.value && !option.disabled);
    const next = preserve && current ? current : options.find((option) => !option.disabled);
    if (next) showMenu(next.value);
  };

  category.addEventListener("change", () => applyCategory(category.value));
  menuSelect.addEventListener("change", () => showMenu(menuSelect.value));
  people.addEventListener("change", updatePeople);

  const initialType = [...category.options].some((option) => option.value === params.get("tipo")) ? params.get("tipo") : "all";
  const initialPeople = ["1", "2", "4", "6"].includes(params.get("personas")) ? params.get("personas") : "2";
  category.value = initialType; people.value = initialPeople; applyCategory(initialType, true);
  const initialMenu = params.get("menu");
  if (initialMenu && options.some((option) => option.value === initialMenu && !option.disabled)) showMenu(initialMenu, false);
  updatePeople();
});

