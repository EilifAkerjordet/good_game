require("dotenv").config();

const express = require("express");
const http = require("http");
const session = require("express-session");
const passport = require("./passport");
const next = require("next");

const dev = process.env.NODE_ENV !== "production";
const app = next({ dev });
const handle = app.getRequestHandler();

app
  .prepare()
  .then(() => {
    const server = express();
    server.use(
      session({
        secret: process.env.SESSION_SECRET,
        resave: true,
        saveUninitialized: true,
      })
    );
    // Use the passport middleware
    server.use(passport.initialize());
    server.use(passport.session());
    // Validates authentification and redirects to the user-page if successful
    server.get("/auth/validate", validateAuth, (req, res) => {
      res.redirect("/user-page"); // Handled by nextjs
    });
    // Route to log user out and end session
    server.get("/auth/logout", (req, res) => {
      req.logout();
      res.redirect("/"); // Handled by nextjs
    });
    // Route to authenticate the user through steam
    server.get(
      "/auth/steam",
      passport.authenticate("steam", { failureRedirect: "/" }),
      (req, res) => {
        res.redirect("/auth/validate");
      }
    );

    // Use passport.authenticate() as route middleware to authenticate the
    // request. if authentification is successful, it will redirect to /user-page
    // and display user information
    server.get(
      "/auth/steam/return",
      passport.authenticate("steam", { failureRedirect: "/" }),
      (req, res) => {
        res.redirect("/user-page"); // Handled by nextjs
      }
    );
    // Middleware for protected routes
    function validateAuth(req, res, next) {
      if (req.isAuthenticated()) {
        return next();
      }
      res.redirect("/"); // Handled by nextjs
    }

    // Handle everything else with Next.js
    server.get("*", handle);
    http.createServer(server).listen(process.env.PORT, () => {
      console.log(`Express.js server listening on port ${process.env.PORT}`);
    });
  })
  .catch((err) => {
    console.error(err);
    process.exit(1);
  });
