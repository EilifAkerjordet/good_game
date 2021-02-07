require('dotenv').config()

const passport = require('passport')
const SteamStrategy = require('passport-steam').Strategy

// Normally, passport needs to serialize users into and deserialize
// users out of sessions. Normally you would query a databse for this.
// In this app there is no DB, so we deserialize and serialize the
// complete profile.
passport.serializeUser((user, done) => {
  done(null, user)
})
passport.deserializeUser((obj, done) => {
  done(null, obj)
})
passport.use(new SteamStrategy({
  // The route that will be returned on successfull authentification
  // This route will check the authentification, then redirect to
  // /auth/account (which responds with user data)
  returnURL: 'http://localhost:3000/auth/steam/return',
  // The base APP URL and API_KEY
  realm: 'http://localhost:3000/',
  apiKey: process.env.STEAM_API_KEY
},
(identifier, profile, done) => {
  process.nextTick(() => {
    profile.identifier = identifier
    return done(null, profile)
  })
}
))

module.exports = passport
