# Steam OpenId authentification using Passport.js, Express.js and Next.js

## About

### Purpose

For its purpose, this project is very bloated. I wanted to use this oppurtunity to try out Passport.js and Express.js in combination with Next.js. Something I have never done before.

### Project structure

The Passport.js middleware can be found in `passport.js` in the root of the project. All the middleware and routing are set up in `server.js`. All routes that are outside the scope of `/auth` are handled by Next.js which merely acts as a view engine. There is no real logic in the front-end except for a redirect in the case that an unauthenticated user is trying to access the protected `/user-page` route.

## Getting started

First you will need a [Steam API Key](https://steamcommunity.com/dev/). Take a look at the `.env.example` file, (rename it to .env in order for it to work with the application). Then fill out the ENV variables. The port has to be 3000 in order to work with Next.js.  

  * `cd` into the project folder
  * `yarn install` or `npm install`
  * To run the production build:
    * `yarn build`
    * `yarn start`
  * To run the dev build:
    * `yarn dev`
  
Then navigate to [localhost:3000](http://localhost:3000) in order to start using the app.
