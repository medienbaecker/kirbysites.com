import { OldBrowserChecker } from "./components/oldBrowserChecker.js";
import { LazyLoader } from "./components/lazyLoader.js";
import { ViewTransitions } from "./components/viewTransitions.js";
import { ThemeToggle } from "./components/themeToggle.js";

/* -------------------------------------------------- */
/* 🙂 General */
/* -------------------------------------------------- */

const isReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)"
).matches;

/* -------------------------------------------------- */
/* 👵 Check for old browsers */
/* -------------------------------------------------- */

new OldBrowserChecker();

/* -------------------------------------------------- */
/* 🦥 Lazy loading */
/* -------------------------------------------------- */

new LazyLoader();

/* -------------------------------------------------- */
/* 🔄 View transitions */
/* -------------------------------------------------- */

new ViewTransitions();

/* -------------------------------------------------- */
/* 🌙 Theme toggle */
/* -------------------------------------------------- */

new ThemeToggle();
