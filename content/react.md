Title: React
Date: 2016-01-08 01:00
Category: Code
Tags: code, javascript, react, mobile, android
Slug: react
Authors: Romain Pellerin
Summary: A quick introduction to React.js

First of all, I wish you a Happy New Year. May it be full of happiness and lines of code :-)

Let's now talk about the new hotness, a JavaScript library getting more and more trendy, with an unceasingly growing community: [React](https://facebook.github.io/react/). It's under active development these days, powered by Facebook (and initially created by). Basically, it's meant to build user interfaces in a web context, but recently [they also exported it to mobile development](https://facebook.github.io/react-native/), alloying anyone to write some kind of cross-platform code for both Android and iOS, in JavaScript, just like Appcelerator Titanium.

At the present time, I'm still getting acquinted with it, but here are a few pieces of advice about how to [get started](https://facebook.github.io/react/docs/getting-started.html) **correctly**.

# Must read

1. [react-howto](https://github.com/petehunt/react-howto)
2. [webpack-howto](https://github.com/petehunt/webpack-howto)
3. [Presets for setting up webpack with hotloading react and ES6(2015) using Babel](https://www.npmjs.com/package/hjs-webpack)
4. [Redux](http://rackt.org/redux/index.html)
5. [Smart and Dumb Components](https://medium.com/@dan_abramov/smart-and-dumb-components-7ca2f9a7c7d0#.u601nht6y)
6. [React modules](https://react.parts/native)
7. [Project structure](https://gist.github.com/jnhuynh/86693d8b485f4d335300)
8. [React and Redux Single Page Applications Resources](https://medium.com/@sapegin/react-and-redux-single-page-applications-resources-22cd859b0c1d)

# How to

You have 2 main tools of choice, to compile it into some browser-compliant package: Webpack or Browserify. I personally decided to go with the former, as it's the most popular and offer more possibilites. Briefly, how to start a new project with webpack:

    :::bash
    npm init # Entry point: index.js
    npm install --save react react-dom babel-preset-react babel-preset-es2015
    # The previous line is required to run React with ES6
    npm install webpack --save-dev
    # --save-dev allow us to add it to the package.json
    npm install webpack-dev-server --save-dev
    # To live reload changes in the browser in development
    # MUST NOT BE USED IN PRODUCTION
    npm link webpack # To make the command available as if it was installed globally
    npm link webpack-dev-server
    
    # Install babel
    npm install babel-loader --save-dev
    # OR, in case of problem
    npm install babel-loader@5.x --save-dev

## Configure webpack

There are [two ways](http://webpack.github.io/docs/configuration.html) to pass the configuration object to webpack. However, here we're going to use the CLI way, which uses a conf file. Create ```webpack.config.js``` at the root of the project, containing this:

    :::javascript
    module.exports = {
        // configuration
    };

That's all for now. This article is likely to be updated in a near future, stay tuned!

PS: a friend of mine also gave a talk last year, at our school, about React. He was an avant-gardist ;-)

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/_u3z_kIlXgg?rel=0" frameborder="0" allowfullscreen></iframe>

[Tips for Webpack here](https://medium.com/netscape/webpack-3-react-production-build-tips-d20507dba99a).

# Futher reading

- [Reduce Your bundle.js File Size By Doing This One Thing](https://lacke.mn/reduce-your-bundle-js-file-size/)
- [Mobile, desktop and website Apps with the same code (React native)](https://github.com/benoitvallon/react-native-nw-react-calculator)
- [How to Structure a React Project?](http://reactjsnews.com/structuring-react-projects/)
- [How to Build a Todo App Using React, Redux, and Immutable.js](http://www.sitepoint.com/how-to-build-a-todo-app-using-react-redux-and-immutable-js/)
- [Building a cross-platform desktop app with NW.js, React & Flux](https://meetfinch.com/blog/cross-platform-app-nwjs-react-flux)
- [Universal React](https://24ways.org/2015/universal-react)
- [React.js Best Practices for 2016](https://blog.risingstack.com/react-js-best-practices-for-2016/)
- [React Lessons - Screencast Video Tutorials @eggheadio](https://egghead.io/technologies/react)
- [Redux Lessons #1 - Screencast Video Tutorials @eggheadio](https://egghead.io/lessons/javascript-redux-the-single-immutable-state-tree)
- [Redux Lessons #2 - Screencast Video Tutorials @eggheadio](https://egghead.io/lessons/javascript-redux-simplifying-the-arrow-functions)
- [React Cheat Sheet](http://reactcheatsheet.com/)
- [Inspect, Modify, and Debug React and Redux in Firefox with Add-ons](https://hacks.mozilla.org/2017/07/debug-react-redux-firefox-add-ons/)
- [7 architectural attributes of a reliable React component](https://dmitripavlutin.com/7-architectural-attributes-of-a-reliable-react-component/)
- [Improving a React app performance](https://www.youtube.com/watch?v=6WvSXoYrM5o)
- [React 🎄](https://react.holiday/)
- [React Developer Tools](https://addons.mozilla.org/en-US/firefox/addon/react-devtools/)
- [Redux DevTools](https://addons.mozilla.org/en-US/firefox/addon/remotedev/)
- [react-perf-devtool](https://github.com/nitin42/react-perf-devtool)
- [Best practices with React and Redux web application development](https://developers.redhat.com/blog/2017/11/15/best-practices-react-redux-web-application-development/)

## About Babel

- [Babel Handbook](https://github.com/thejameskyle/babel-handbook/blob/master/translations/en/README.md)

## About Redux

- [A cartoon intro to Redux](https://code-cartoons.com/a-cartoon-intro-to-redux-3afb775501a6)
