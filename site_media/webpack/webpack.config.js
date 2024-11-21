const webpack = require("webpack");
const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TsconfigPathsPlugin = require("tsconfig-paths-webpack-plugin");

module.exports = {
  mode: process.env.NODE_ENV,
  watch: process.env.NODE_ENV !== "production",
  watchOptions: {
    ignored: /node_modules/,
    poll: 500, // Check for changes every second
  },
  performance: {
    maxEntrypointSize: 1024000,
    maxAssetSize: 1024000,
  },
  resolve: {
    extensions: [".tsx", ".ts", ".js"],
    fallback: {
      util: require.resolve("util/"),
    },
    plugins: [new TsconfigPathsPlugin()],
  },

  entry: {
    index: "./src/app/index.tsx",
  },
  output: {
    path: path.resolve(__dirname, "../site/"),
    filename: "js/[name].bundle.js",
    chunkFilename: "js/[name].bundle.[id].js",
  },
  plugins: [
    new webpack.DefinePlugin({
      "process.env": "{}",
      "process.env.NODE_ENV": JSON.stringify(process.env.NODE_ENV),
    }),
    new MiniCssExtractPlugin({
      filename: "css/[name].bundle.css",
      chunkFilename: "css/[name].bundle.[id].css",
    }),
  ],

  module: {
    rules: [
      {
        test: /\.(ts|tsx)$/,
        exclude: /node_modules/,
        use: "babel-loader",
      },
      {
        test: /\.css$/i,
        use: ["style-loader", "css-loader"],
      },
      {
        test: /\.s[ac]ss$/i,
        type: "asset/resource",
        use: [
          // 'style-loader',
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              url: false,
              // sourceMap: true,
            },
          },
          {
            loader: "sass-loader",
            options: {
              // Prefer `dart-sass`
              implementation: require("sass"),
              sourceMap: true,
              // sourceMapContents: false,
              sassOptions: {
                outputStyle: "compressed",
              },
            },
          },
        ],
      },
    ],
  },
};
