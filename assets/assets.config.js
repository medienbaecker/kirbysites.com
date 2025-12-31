import esbuild from "esbuild";
import browserSync from "browser-sync";

const domain = process.cwd().split("/").pop() + ".test";

// BrowserSync
const bs = browserSync.create();

bs.init({
	proxy: domain,
	host: domain,
	reloadOnRestart: true,
	notify: false,
	ui: false,
	ghostMode: false,
});

// esbuild plugin: reload browser on build
const reload = (file) => ({
	name: "reload",
	setup(build) {
		build.onEnd((result) => {
			if (result.errors.length === 0) {
				bs.reload(file);
			}
		});
	},
});

// JavaScript
const jsContext = await esbuild.context({
	entryPoints: ["assets/js/main.js"],
	outdir: "assets/js",
	outExtension: { ".js": ".min.js" },
	minify: true,
	bundle: true,
	sourcemap: true,
	logLevel: "info",
	plugins: [reload()],
});

// CSS
const cssContext = await esbuild.context({
	entryPoints: ["assets/css/style.css"],
	outdir: "assets/css",
	outExtension: { ".css": ".min.css" },
	minify: true,
	bundle: true,
	sourcemap: true,
	logLevel: "info",
	plugins: [reload("*.css")],
	external: ["*.woff2", "*.woff", "*.png", "*.jpg", "*.svg", "*.webp"],
});

// Watch
await jsContext.watch();
await cssContext.watch();

bs.watch([
	"assets/images/**",
	"site/*/**",
	"!site/sessions/**",
	"!site/cache/**",
	"content/**/*.*",
	"!content/**/_changes/**",
]).on("change", (file) => bs.reload(file));
