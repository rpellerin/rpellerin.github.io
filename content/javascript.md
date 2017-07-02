Title: JavaScript
Date: 2015-10-21 23:00
Modified: 2017-03-14 22:00
Category: Code
Tags: javascript, code, web, node
Slug: javascript
Authors: Romain Pellerin
Summary: Why Javascript is the new hotness

# Dates

Use [date-fns](https://github.com/date-fns/date-fns). It is [lighter](https://github.com/date-fns/date-fns/issues/275) than Moment.js. And it is [immutable](https://twitter.com/dan_abramov/status/805030922785525760).

# A word on ESlint

ESlint is probably the most popular linter for JavaScript. It is also very convenient and benefits from a large community. That is why I use it.

When I started using, I got confused by two different things: [shareable configs](http://eslint.org/docs/developer-guide/shareable-configs) and [plugins](http://eslint.org/docs/developer-guide/working-with-plugins). This [thread](https://groups.google.com/forum/#!topic/eslint/ttZUG3v7vn0) explains the difference between the two quite well. Here is my version:

> Plugins are like function definitions (a set of custom rules). Configs are like actual calls to thoses functions (pre-defined configuration of rules - like whether they're enabled or not, and how they are configured).

# How to start a new JavaScript project?

If it is a simple project or a NPM package, I recommend having two distinct folders: `src` for sources and `dist` for builds.

1. Now, do:

        :::bash
        git init
        git add remote origin ... # If need be
        npm init
        yarn add --dev babel-cli babel-preset-es2015
        yarn add --dev eslint
        yarn add --dev eslint-config-standard \
                       eslint-plugin-standard \
                       eslint-plugin-promise \
                       eslint-plugin-import \
                       eslint-plugin-node

    We use `babel` to transpile our code, to better support old versions of NodeJS.
    
    Regarding the last line, the config [eslint-config-standard](https://github.com/feross/eslint-config-standard) sets the configuration of these four plugins: `standard`, `promise`, `import` and `node`. That is why we need to install them alongside the shareable config. Of course it is a config for `eslint`, that is why we installed it on the previous line.

2. Do:

        :::bash
        yarn add prettier-eslint-cli

    Normally, [`prettier-eslint`](https://github.com/prettier/prettier-eslint) only operates on strings, not on files. `prettier-eslint-cli` will provide you with a command to apply `prettier-eslint` on files.

    **What is `prettier-eslint` meant for?** Well, under the hood it calls [`prettier`](https://github.com/prettier) (probably the best JavaScript formatter to date) and then `eslint --fix` to format your code. Twice. But there's [a reason for that](https://github.com/prettier/prettier-eslint#the-problem).

    Now we need to configure `eslint` to tell it to use standard as its style.

3. To use the standard style with ESlint, create `.eslint` in the root directory of your project and write:

        :::text
        {
            "extends": "standard"
        }

4. Add a badge to your `README.md`: [badge.fury.io/for/js](https://badge.fury.io/for/js)
5. Configure `package.json`:

        :::json
        ...,
        "scripts": {
            "test": "echo \"Error: no test specified\" && exit 1",
            "build": "babel src --presets babel-preset-es2015 --out-dir dist",
            "format": "prettier-eslint \"src/*.js\"",
            "lint": "eslint src",
            "check": "npm run lint && npm run test"
        },
        ...

All set!

# Resources

## NodeJS

- [Node.js ES2015/ES6, ES2016 and ES2017 support](http://node.green/)
- [Node js comme les grands (Romain Maton)](https://www.youtube.com/watch?v=RIRB2AFrPV8)

## Set-up your environment

- [Getting Started with ESLint](http://eslint.org/docs/user-guide/getting-started)

## Documentation

- [JSDoc](http://usejsdoc.org/index.html)
- [JSDuck](https://github.com/senchalabs/jsduck)

## General

- [Clean Code concepts adapted for JavaScript](https://github.com/ryanmcdermott/clean-code-javascript)
- [JavaScript Equality Table](https://dorey.github.io/JavaScript-Equality-Table/)
- [The Linux Foundation Unites JavaScript Community for Open Web Development](https://js.foundation/announcements/2016/10/17/Linux-Foundation-Unites-JavaScript-Community-Open-Web-Development/)
- [How it feels to learn JavaScript in 2016](https://hackernoon.com/how-it-feels-to-learn-javascript-in-2016-d3a717dd577f)
- [ES6 Overview in 350 Bullet Points](https://ponyfoo.com/articles/es6)
- [Programming JavaScript Applications (book)](http://chimera.labs.oreilly.com/books/1234000000262)
- [Composition over Inheritance](https://www.youtube.com/watch?v=wfMtDGfHWpA)
- [Learning JavaScript Design Patterns (book)](http://addyosmani.com/resources/essentialjsdesignpatterns/book/)
- [Want to learn JavaScript in 2015 / 2016?](https://medium.com/@_cmdv_/i-want-to-learn-javascript-in-2015-e96cd85ad225)
- [You Don't Know JS (book series)](https://github.com/getify/You-Dont-Know-JS)
- [12 Rules for Professional JavaScript in 2015](https://medium.com/@housecor/12-rules-for-professional-javascript-in-2015-f158e7d3f0fc)
- [10 bonnes pratiques JavaScript](http://www.js-attitude.fr/2013/01/21/dix-bonnes-pratiques-javascript)
- [A Deeper Look at Objects in JavaScript](http://www.kirupa.com/html5/a_deeper_look_at_objects_in_javascript.htm)
- [10 Interview Questions Every JavaScript Developer Should Know](https://medium.com/javascript-scene/10-interview-questions-every-javascript-developer-should-know-6fa6bdf5ad95)
- [JavaScript: an acceptable use of double-equals? Just.](http://blog.boyet.com/blog/javascriptlessons/javascript-an-acceptable-use-of-double-equals-just/)
- [Un gros Troll de plus sur Javascript](http://sametmax.com/un-gros-troll-de-plus-sur-javacscript/)
- [Some really good best practices from Airbnb](https://github.com/airbnb/javascript)
- [DevFest Nantes 2015 - Découvrir ES6 par le code](https://www.youtube.com/watch?v=7XZWqF2aHuI)
- [Top 10 ES6 Features Every Busy JavaScript Developer Must Know](http://webapplog.com/es6/)
- [ES6/ES2015 en 24 jours](http://putaindecode.io/fr/evenements/2015/calendrier-avent/)
- [Must See JavaScript Dev Tools That Put Other Dev Tools to Shame](https://medium.com/javascript-scene/must-see-javascript-dev-tools-that-put-other-dev-tools-to-shame-aca6d3e3d925)
- [You Don't Need jQuery](https://github.com/oneuijs/You-Dont-Need-jQuery)
- [How to Schedule Background Tasks in JavaScript](http://www.sitepoint.com/how-to-schedule-background-tasks-in-javascript)
- [INTRODUCING QUEUES IN NODE.JS](http://blog.yld.io/2016/05/10/introducing-queues/)
- [https://github.com/k33g/files/tree/master/javascript](https://github.com/k33g/files/tree/master/javascript)
- [ES6 const is not about immutability](https://mathiasbynens.be/notes/es6-const)
- [ECMAScript 2017: the final feature set](http://www.2ality.com/2016/02/ecmascript-2017.html)
- [Emojis in Javascript](https://medium.com/@thekevinscott/emojis-in-javascript-f693d0eb79fb)
- [Transpiling dependencies with Babel](http://2ality.com/2017/04/transpiling-dependencies-babel.html)
- [Running a Node.js process on Debian as a Systemd Service](https://thomashunter.name/blog/running-a-node-js-process-on-debian-as-a-systemd-service/)

## Some cool node/npm stuff/packages/tools

- [How to Become a Better Node.js Developer in 2016](https://blog.risingstack.com/how-to-become-a-better-node-js-developer-in-2016/)
- [10 Habits of a Happy Node Hacker (2016)](http://blog.heroku.com/archives/2015/11/10/node-habits-2016)
- [node-startup: startup script for Linux-based systems for running node app when rebooting using an /etc/init.d script](https://github.com/chovy/node-startup)
- [peerflix](https://github.com/mafintosh/peerflix)
- [nodegit: manipulating git repositories with Node.js](http://radek.io/2015/10/27/nodegit/)
- [How to Use npm as a Build Tool](http://blog.keithcirkel.co.uk/how-to-use-npm-as-a-build-tool/)
- [Start your own JavaScript library using webpack and ES6](http://krasimirtsonev.com/blog/article/javascript-library-starter-using-webpack-es6)
- [Livedown](https://github.com/shime/livedown)
- [Sequelize: a promise-based ORM for Node.js (PostgreSQL, MySQL, MariaDB, SQLite and MSSQL)](http://docs.sequelizejs.com/en/v3/) + [Epilogue: create flexible REST endpoints and controllers from Sequelize models in your Express app](https://github.com/dchester/epilogue)

## Closures

- [Closures are not magic](http://renderedtext.com/blog/2015/11/18/closures-are-not-magic/)

In loops, they are a common issue. Here is how to solve it:

- [Before ECMAScript 6 (double closures)](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Closures)
- [After ECMAScript 6 (the let keyword)](http://www.sitepoint.com/preparing-ecmascript-6-let-const/)

## Testing

- [Karma](https://karma-runner.github.io/1.0/index.html) with [Jasmine](https://jasmine.github.io/) (**edit March 14, 2017: I'd rather recommend going with Jest instead of Jasmine. Karma is different from Jest or Jasmine, it runs tests within web browsers, whereas plain Jasmine/Jest use Nodejs**)
