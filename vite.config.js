import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
  base: "/plugin-builds/strategy-engine/",
  resolve: {
    dedupe: ["alpinejs"],
  },
  plugins: [
    laravel({
      input: [
        "storage/app/plugins/strategy-engine/resources/css/app.css",
        "storage/app/plugins/strategy-engine/resources/js/app.js",
      ],
      publicDirectory: "public",
      buildDirectory: "plugin-builds/strategy-engine",
      hotFile: "public/plugin-builds/strategy-engine/hot",
      refresh: false,
    }),
  ],

  css: {
    postcss: path.resolve(__dirname, "postcss.config.js"),
  },

  build: {
    outDir: path.resolve(
      __dirname,
      "../../../../public/plugin-builds/strategy-engine",
    ),
    emptyOutDir: true,
    assetsDir: "assets",
  },
});
