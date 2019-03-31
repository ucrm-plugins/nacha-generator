"use strict";
// Template version: 1.3.1
// see http://vuejs-templates.github.io/webpack for documentation.

const path = require("path");

module.exports = {

    dev: {

        // -------------------------------------------------------------------------------------------------------------
        // PATHS
        // -------------------------------------------------------------------------------------------------------------

        assetsSubDirectory: "static",
        assetsPublicPath: "/",

        // NOTE: We need to proxy requests to /assets and /dist during development to a UCRM server to use the assets!
        proxyTable: {
            "/assets": {
                target: "https://ucrm.dev.mvqn.net/",
                changeOrigin: true
            },
            "/dist": {
                target: "https://ucrm.dev.mvqn.net/",
                changeOrigin: true
            },
            "/public": {
                target: "http://localhost:8080/",
                changeOrigin: false,
                pathRewrite: {
                    "^/public": ""
                }
            }


        },

        // -------------------------------------------------------------------------------------------------------------
        // SERVER SETTINGS
        // -------------------------------------------------------------------------------------------------------------

        host: "localhost", // can be overwritten by process.env.HOST
        port: 8080, // can be overwritten by process.env.PORT, if port is in use, a free one will be determined
        autoOpenBrowser: false,
        errorOverlay: true,
        notifyOnErrors: true,
        poll: false, // https://webpack.js.org/configuration/dev-server/#devserver-watchoptions-

        // -------------------------------------------------------------------------------------------------------------
        // SOURCE MAPS
        // -------------------------------------------------------------------------------------------------------------

        // https://webpack.js.org/configuration/devtool/#development
        devtool: "cheap-module-eval-source-map",

        // If you have problems debugging vue-files in devtools,
        // set this to false - it *may* help
        // https://vue-loader.vuejs.org/en/options.html#cachebusting
        cacheBusting: true,

        cssSourceMap: true
    },


    build: {

        // -------------------------------------------------------------------------------------------------------------
        // PATHS
        // -------------------------------------------------------------------------------------------------------------

        // Template for index.html
        index: path.resolve(__dirname, "../../index.html"),

        assetsRoot: path.resolve(__dirname, "../../"),
        assetsSubDirectory: "static",
        assetsPublicPath: "public/",

        // -------------------------------------------------------------------------------------------------------------
        // SOURCE MAPS
        // -------------------------------------------------------------------------------------------------------------

        productionSourceMap: true,
        // https://webpack.js.org/configuration/devtool/#production
        devtool: "#source-map",

        // Gzip off by default as many popular static hosts such as
        // Surge or Netlify already gzip all static assets for you.
        // Before setting to `true`, make sure to:
        // npm install --save-dev compression-webpack-plugin
        productionGzip: false,
        productionGzipExtensions: ["js", "css"],

        // Run the build command with an extra argument to
        // View the bundle analyzer report after build finishes:
        // `npm run build --report`
        // Set to `true` or `false` to always turn it on or off
        bundleAnalyzerReport: process.env.npm_config_report
    }

};
