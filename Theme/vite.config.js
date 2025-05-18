import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";
import fs from "fs";

const hotReloadViews = () => ({
  name: "hot-update-report",
  handleHotUpdate({ file }) {
    if (file.endsWith(".blade.php")) {
      const path = file.split("views/");
      const destination = "../../../resources/themes/licious/views/" + path[1];
      fs.copyFile(file, destination, (err) => {
        if (err) throw err;
      });
    }
  },
});

export default defineConfig(({ mode }) => {
  const envDir = "../../../";

  Object.assign(process.env, loadEnv(mode, envDir));

  return {
    build: {
      emptyOutDir: true,
    },

    envDir,

    server: {
      host: process.env.VITE_HOST || "localhost",
      port: process.env.VITE_PORT || 5173,
    },

    plugins: [
      vue(),

      laravel({
        hotFile: "../../../public/shop-licious-vite.hot",
        publicDirectory: "../../../public",
        buildDirectory: "themes/shop/licious/build",
        input: [
          "src/Resources/assets/css/app.css",
          "src/Resources/assets/js/app.js",
        ],
        refresh: true,
      }),
      hotReloadViews(),
    ],

    experimental: {
      renderBuiltUrl(filename, { hostId, hostType, type }) {
        if (hostType === "css") {
          return path.basename(filename);
        }
      },
    },
  };
});
