Title: Designing An Efficient REST API
Date: 2015-09-24 18:00
Modified: 2016-01-09 19:13
Category: Code
Tags: javascript, code, api, http, rest, express, json, web
Slug: designing-an-efficient-rest-api
Authors: Romain Pellerin
Summary: How to write beautiful and efficient REST APIs

**TL;DR**: see the bottom.

Most of the time, the only purpose of an API is to give clients (a mobile application, a web site, a software program, a web browser, etc) access to a remote database (thus a server), for storing data (or making complex calculations sometimes).
**The scheme is very simple: the server waits for incoming requests and responds accordingly. On the other hand, clients occasionally send requests, either to perform an action (saving, updating or deleting data), or to retrieve data.**

When it comes to designing and implementing an (REST) API, you might be seeking a standard to follow ([some people are trying to create a standard](http://jsonapi.org/)) or some [best practices](http://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api) to apply. You might also wonder which language and which framework to use (don't [reinvent the wheel](https://en.wikipedia.org/wiki/Reinventing_the_wheel) dude!). Actually, there are plenty of them. From the "*do it from scratch in Java*" to the "*use this all-in-one Javascript framework*", you have a lot of choices.

I've experienced real API development with two languages so far, **Javascript** and **Scala**. With the former, I used the well-known [Express framework](http://expressjs.com/). With the latter, I used the equally well known [Play Framework](https://www.playframework.com/) in its version 2.  
I also used [PHP](https://github.com/rpellerin/php-mvc-base) and Java without any framework.

But that's not our purpose in this article. Let's move on to the big question, "*How to design an efficient REST API*". Then, we'll have a quick look at [Express](http://expressjs.com/), a famous Javascript framework and one of the best for APIs, in my opinion.

In this article, I'll try to follow what [someone once wrote beautifully](http://martinfowler.com/articles/richardsonMaturityModel.html), in order to introduce REST APIs, and gradually explain how to improve them. I assume you - the reader - have some knowledge of the HTTP protocol.

[It goes without saying that you **must use HTTPS** to ensure privacy. This way, everything will be encrypted and anyone sniffing the network won't be able to see the content of your requests or responses.](https://www.ssllabs.com/) Let's dive into REST APIs now!

# You said "*REST*"?

In "*REST API*", "*REST*" stands for "*Representational State Transfer*", which is the software architectural style of the web. Basically, this means that any REST API relies on the HTTP protocol (and by extension HTTPS). As a consequence, the first step is to understand all the strengths and weaknesses of HTTP in order to use everything it has to offer. I will only be referring to HTTP version 1.1 in this article, since version 2 is still hardly used overall, and quite badly supported.

# It's all about resources

URLs (*Uniform Resource Locator*) represent resources. When designing an API, think about it as resource containers. Every end-point should represent either **a resource** or **a list of resources**. Following this, there must be **two-end points per resource**, the resource collection, and an individual resource within the collection:

    :::text
    /myresource
    /myresource/{id}

If our API is hosted at [https://my-api.com](https://my-api.com), then we've just created the end-points [https://my-api.com/myresource](https://my-api.com/myresource) and [https://my-api.com/myresource/{id}](https://my-api.com/myresource/id). But let's try to avoid abstraction and use real examples.

We are developing a website where registered users can upload pictures. So, we need two end-points for our two entities:

    :::text
    /users
    /pictures

Those two end-points will then allow us to get the list of either all the users, or all the pictures. Now, what if we want to know the categories of the pictures? We also need to access individual resources. Let's create other end-points:

    :::text
    /users/{id}
    /pictures/{id}
    /pictures/categories
    /pictures/categories/{id}


The important thing here, is to understand that ```{id}``` is a resource's unique identifier. For users, we retrieved these unique identifiers thanks to the ```/users``` end-point (which had given us the list of users). So, ```/users/{id}``` will give us all the details about ONE resource, the targeted user. The same rule applies for any other collection of resources.

Also, ```/pictures/categories``` represent a list of resources, in our case a list of categories. But this list belongs to a "bigger" resource, a container, ```/pictures```. It's a **hierarchy**. This way, we are going to get all the possible categories for any picture.

Now, let's add a feature: users can send messages to each other. How do we get all the messages sent by a specific user? And a specific message sent by a specific user? Like this:

    :::text
    /users/{userId}/messages
    /users/{userId}/messages/{messageId}

## Naming convention

To remain consistent, try to use the plural form of every word used for your end-points, it will make things easier. [*camelCase*](https://en.wikipedia.org/wiki/CamelCase) is also a good practice, when necessary (but we always try to use simple single words).

# Methods (also called verbs)

One of the biggest strengths of HTTP is [its verbs](http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html). The most popular are GET and POST. The latter is mostly used for asynchronous calls (AJAX) or forms in web pages, whereas the former is used every time a page is accessed or reloaded from a browser.

So, what are the equivalent verbs for our old friend called *CRUD* (Create, Read, Update and Delete)? **PUT**, **GET**, **POST** and **DELETE**. Consequently, any well designed REST API must make use of at least these four verbs. And yes, HTTP has more [than four verbs](http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html).

For example, this is how to create, get, update and delete a specific user:

    :::bash
    POST   /users      # Create
    PUT    /users/{id} # Create
    GET    /users/{id} # Get
    PUT    /users/{id} # Update
    DELETE /users/{id} # Delete

Here, the important thing is that ```PUT``` is used to create a **specific resource** (a user), whose identifier has been chosen by the client. Then, the second ```PUT``` updates the newly created resource. **```POST``` is only dedicated to creating resources**, unlike ```PUT``` that will mostly be used to update, but can also be used to create. When you want to create a resource without specifying its identifier, use ```POST```, like in the first line, where a user is signing up (then the API will create an identifier for this user). Logging in would be achieved like this:

    :::text
    POST /users/login

[More information about verbs](http://www.restapitutorial.com/).

# Understanding HTTP

At this point, I need to add some explanation about the HTTP protocol. Every HTTP transaction (request or response) is made of three main parts:

- An initial line
- Some headers
- The body, separated from the headers by a blank line

For requests, the initial line is made of a verb, a URL and the HTTP version used. For instance:

    :::text
    GET /users HTTP/1.1

On the other hand, for responses, it's a bit different: the HTTP version, a response status code and an English reason phrase describing the status code, like in the following example:

    :::text
    HTTP/1.1 404 Not Found

A typical simple request would look like this:

    :::text
    POST /users/123 HTTP/1.1
    Host: my-api.com
    Content-Type: application/x-www-form-urlencoded

    name=Jon&lastName=Snow

And a (non-related) response (I chose a random header):

    :::text
    HTTP/1.1 200 OK
    Expires: Thu, 24 Sep 2015 19:36:25 GMT

    Hello world

That said, let’s get back to APIs.

In the previous sections we learned the correct use of the HTTP verbs, so now let's talk about the body and the status code. Headers will come later.

# Transmitting data

How can a client update a user on the server? What if someone wants to update their biography for example? Or wants to mention their age? Likewise, how does the server respond and send data back to clients? Here come the status codes and the body.

Basically, clients need to send **one type of information** in the body: data to be put into the remote database, via a **PUT** or **POST** request. It can be either to create a new resource or to update it (it depends on the HTTP verb used).

On the other hand, the server can send different types of information:

- Via the status code:
    - How the request was handled
- In headers:
    - Metadata
- In the body:
    - **A resource** or **a list of resources**, in response to a **GET** request.
    - **Some details about a resource** newly created, in response to a **PUT** or **POST** request. For example, the resource's unique identifier or URL, to access this resource.

As you can see, the body is only dedicated to the requested resources(s). That's all. All other useful information **must** be transmitted via the HTTP status code or headers.

## The status code

Want to tell the client the resource has been created? Use the code 201. Want to say something went wrong? 500. Forbidden? 403. Resource not found? 404. [Choose the right one](https://en.wikipedia.org/wiki/List_of_HTTP_status_codes).

Here are the most used status codes for an API:

| Status code               | Used in response to                     |
| ------------------------- | --------------------------------------- |
| 200 OK                    | GET                                     |
| 201 Created               | PUT (when creating a resource), POST    |
| 202 Accepted              | PUT, POST                               |
| 204 No Content            | PUT, DELETE                             |
| 301 Moved Permanently     | GET, PUT, POST                          |
| 400 Bad request           | Any                                     |
| 401 Unauthorized          | Any                                     |
| 403 Forbidden             | Any                                     |
| 404 Not Found             | GET, PUT (when updating), DELETE        |
| 500 Internal Server Error | Any                                     |

The HTTP protocol is very flexible, allowing us to use any non existing code if needed and relevant, when none of them fits our needs. For example, the range 550-599 can be used freely, the way you want.

[Need help in choosing the correct status code?](http://i.stack.imgur.com/whhD1.png)

## The body

Sending parameters, from HTML forms, is pretty easy, it's a key-value thing, where parameters are separated by "&", like this:

    :::text
    firstName=Jon&lastName=Snow

Here, there's no recommended convention. I used camelCase (as shown above) without any particular reason. Choose one and be consistent.

However, out of the context of HTML forms, how to format the body? Well, one could say we could use the same key-value format, but actually, it's not a good idea. Let's see what options we have.

### A historic battle: XML vs. JSON or SOAP vs. REST

You have two main options: XML or JSON. Nowadays, a lot of people use JSON. I won't try to defend JSON here, please [read this comparison](http://www.json.org/xml.html) or [the official description](http://www.json.org/).

To briefly sum it up, JSON brings efficiency, lightness and high-readability in a single format. Additionally, it's extremely easy to parse with any language, thanks to a great integration and a lot of support. It’s definitely the most popular format when dealing with APIs. You can easily create a single object or an array of objects.

Just keep in mind that JSON is probably your best ally.

## A particular header

Earlier on, we saw that the second part of every HTTP request is the headers section. Let me introduce one of them, probably the most important one: ```Content-Type```.

Its only purpose is to specify what kind of data we are sending. Consequently, in our case, this header should be like:

    :::text
    Content-Type: application/json

Basically, its value is just a [MIME type](https://en.wikipedia.org/wiki/Media_type).

# Real world example

A **POST** request sent by the client:

    :::text
    POST /users HTTP/1.1
    Host: my-api.com
    Content-Type: application/json
    
    {
        "name": "Jon Snow",
        "bad_guys_killed": 200,
        "skills":
            [
                {"name": "Warrior"},
                {"name": "Member of the Night's Watch"}
            ],
        "is_a_badass": true
    }

Basically, its value is just a MIME type. To such a request, the response the server would send back:

    :::text
    HTTP/1.1 201 Created
    Content-Type: application/json

    {
        "id": 123
    }

We can see that the server created the resource (thanks to the status code). It also gives us its ID, JSON-formatted. Then, we can do:

    :::text
    GET /users/123 HTTP/1.1
    Host: my-api.com

We would get:

    :::text
    HTTP/1.1 200 OK
    Content-Type: application/json

    {
        "id": 123,
        "name": "Jon Snow",
        "bad_guys_killed": 200,
        "skills":
            [
                {"name": "Warrior"},
                {"name": "Member of the Night's Watch"}
            ],
        "is_a_badass": true,
        "created_at": "2015-09-02T17:05:22.996Z",
        "updated_at": "2015-09-02T17:05:22.996Z"
    }

---

At this point, our API is able to do the four basic operations we needed ([CRUD](https://en.wikipedia.org/wiki/Create,_read,_update_and_delete), remember?), on any kind of entity, easily, thanks to a pretty format, JSON. End-points are semantic and hierarchically ordered. Everything is perfect.

<div style="text-align: center">
<img src="{filename}/images/api-unicorn.gif" alt="A gif showing a unicorn" style="width: 150px; height: auto" />
</div>

But... (there's always a *but*).

We don't take advantage of all the strengths of HTTP. Headers, you remember? And what if we want to apply some filters to our **GET** requests? Sorting? What about pagination? Getting your 1M users in a single response body isn't a good idea, trust me.

More importantly, what about security? Authentication? But first, let's talk about HATEOAS...

# [HATEOAS, the Holy Grail](http://putaindecode.fr/posts/api/hateoas/)

Most of the time, to access a specific resource, you need to:

1. Get the list of resources, e.g. ```GET /users```
2. Find the unique identifier of the targeted resource in this list
3. Construct its URL, often https://&lt;domain&gt;/&lt;list-of-resources&gt;/&lt;id&gt;, e.g. ```https://my-api.com/users/123```
4. Finally, do ```GET /users/123```

Another approach would be providing directly every resource's URL in the list (among other links if necessary), like this:

    :::text
    [
        {
            "id": 123,
            name: "Jon Snow",
            "links":
                [
                    {
                        "rel": "self",
                        "href": "https://my-api.com/users/123"
                    },
                    {
                        "ref": "list",
                        "href": "https://my-api.com/users"
                    }
                ],
            "bad_guys_killed": 200,
            ...
        },
        {
            "id": 124,
            ...
        }
    ]

Instead of providing the full URL, you might as well only provide the path name:

    :::text
    "links":
        [
            {
                "rel": "self",
                "href": "/users/123"
            },
            ...
        ]

I'm not a huge fan of HATEOAS. I believe that an API should be consistent in its manner of accessing resources. I like to assume that, as soon as I know a resource's unique identifier, I can access it by constructing its URL, as I explained above. So, I don't need the links, I can construct them by myself.

**But I'm wrong**. This is definitely the best way to do. It adds a layer of abstraction. If one day you change the way to access a resource (not with a unique identifier anymore, but with a token, or an email address, or anything), if you always provided the link for each resource, the change would be transparent for your users.

# Headers are metadata

A great thing to consider is that the body **must** only contain the requested resource(s). Any other piece of information must go to the headers. Furthermore, you can create any header you want.

## Authentication

There are two major concepts under the name of *authentication*.

- Sign up/Log in/Log out: **user management**
- Authorization: **restricting the access to the API**

User management can be be achieved with cookies. This way, you can restrict some end-points to logged in users only.

In parallel, you might want to restrict the entire API to specific clients. One could say "*Use basic authentication*", but that doesn't really fit our need since it's not the original purpose of basic auth.

What you can do instead is quite easy: create as many HTTP headers as you want and send (secret) keys on every request. For example, let's create a key to make sure we're accessing the right app, and another one to define the access level granted:

    :::text
    POST /users HTTP/1.1
    Host: my-api.com
    Content-Type: application/json
    Security-APP-ID: 123456
    Security-Access-Level: 3

    {
        "name": "Jon Snow"
    }

Obviously, as I said in the beginning of this article, all of this doesn't make sense at all if you don't use HTTPS (and force it!). You really don't want to expose such keys to anyone on the network.

[More information about authentication](http://restcookbook.com/Basics/loggingin/) and about [security in general](http://blog.slaks.net/2015-10-13/web-authentication-arms-race-a-tale-of-two-security-experts/).

## Location

Another useful header is ```Location```. A good practice is to use it after creating a resource with **POST**, to give back the location of the resource newly created. This way, no body is needed and your API can respond with a status code *201 Created*, without a body. For example, after creating the user *Jon Snow*, we would get:

    :::text
    HTTP/1.1 201 Created
    Location: https://my-api.com/users/123

The protocol and domain are optional, we could just send ```/users/123```.

## Other useful piece of information

Basically, you can create as many custom headers as you want. A good practice with end-points returning a list of resources, is to give the total number of records in the database when relevant, via a header. You may wonder why, since we can programmatically count the number of results. But, as I will explain it in the next section, you are never going to return the full list of results in a single response.

So, this is an example of a header specifying the number of rows. The request:

    :::text
    GET /users HTTP/1.1
    Host: my-api.com

The response:

    :::text
    HTTP/1.1 200 OK
    Content-Type: application/json
    Total-Rows: 7897

    [
        {
            id: 1,
            ...
        },
        ...
    ]
# Pagination

**When returning a list of resources**, above a certain number or results, you should split your response in pages. It's a good practice for reducing waiting time and the weight of responses. Moreover, you might not need the whole list at once. Depending on the amount of details provided for each resource, a general rule would be returning between 100 and 1000 results per request as a maximum.

Requesting a page is as simple as providing a query parameter:

    :::text
    GET /users?page=3

On the server side, you would just skip the *&lt;number-of-results-per-page&gt;\*&lt;number-of-page&gt;* first users. Pages start at 0.

For instance, if you return 100 users per request, with page 0 you would skip no one and simply returns the first 100 users (from 0 to 99). On page 1, you skip the first 100 users from your database and return the users from 100 to 199, and so on.

But there's a problem...

Let's say you have 10 users in your database. Every ```GET /users``` returns 3 users at most, sorted by age.

In SQL, this would be something like:

    :::sql
    SELECT id, name, age FROM users ORDER BY AGE LIMIT 3 OFFSET x;
    /* where x equals 3*<number of page> */

You'll then have 3 pages.

| Page 0                      | Page 1                | Page 2               |
| ----------------------------| --------------------- | -------------------- |
| **45,** Jon, 20             | **55,** Alice, 30     | **99,** Pete, 37     |
| **87,** Laura, 23           | **9,** Bob, 33        | **1,** Cindy, 39     |
| **3,** Jean, 25             | **2,** Helen, 36      | **78,** Max, 40      |

Here is a scenario:

1. ```GET /users?page=0```: fine, you get results from Jon (id 45) to Jean (id 3).
2. A new user (Mary) signs up on your website/mobile application, aged of 24.
3. ```GET /users?page=1```: you get results from Jean to Bob. You got the same person twice (Jean), this is a problem.

## What happened?

As Mary is 24 and the API returns results sorted by age, Mary would be returned on page 0, which shifts Jean to page 1.

A good way to solve this problem is to create a pagination based on an entity (here, a user). Instead of requesting a particular page, you would say to the API "*give me results after this user*" or "*before this user*". Let me explain it by replaying our scenario:

1. ```GET /users```: no pagination specified, fine, you get results from Jon (id 45) to Jean (id 3)
2. A new user (Mary) signs up on your website/mobile application, aged of 24.
3. ```GET /users?pageAfter=3```: since the last person of our previous results had an id of 3, we request the users **after** that ID. This way, we make sure we won't get duplicated results.

To achieve this, you can either write a more complex SQL query of simply do the pagination programmatically.

The only problem that still remains is that, by doing this, we have no way to retrieve Mary, except if we request page 0 again, by doing this, for example:

    :::text
    GET /users?pageBefore=3

Two last things:

- Always enable pagination, even if your entire collection is small and will never grow. Like this, you API's users won't get lost. The API remains consistent.
- If a page returns this entire collection, use the status code 200. Otherwise, use the status code 206 (*Partial Content*)

[More information about pagination](http://metabates.com/2012/02/22/adding-pagination-to-an-api/).

# Filtering and sorting

Every end-point that returns a list of resource should allow filtering on fields, as well as sorting. The following request should be possible:

    :::text
    GET /users?country=US&age=21&sortBy=name

We request all the users from the US, aged of 21, sorted by name. The inner mechanism is up to the developers.

---

We're done! Now, you know how to build efficient and robust APIs.

Building an efficient API involves many aspects to consider, and most of all, requires a good knowledge of HTTP. It's time-consuming at first sight, but once you know what you're doing, what your needs are in terms of features, it's pretty straightforward. Considering all aspects right at the beginning, like security or the database architecture and how you would like to expose it, is key. That's what is called designing an architecture, and one should pay heed to it. When properly and carefully done, the API implementation job is fairly easy.

What are the next steps?

# Add a documentation for other developers who use your API

- [apidoc](http://apidocjs.com/) (I really like this one, very easy)
- [Swagger.io](http://swagger.io/) (good reputation, altough I never tried it)
- [A full list of most common API editors](https://medium.com/@orliesaurus/a-review-of-all-most-common-api-editors-6a720dc4f4e6).

# Going further: create a CLI

Make you API public! Create a documentation and a NPM package to ease the work for developers. [Read how Clever Cloud did it](https://www.clever-cloud.com/blog/features/2015/09/21/Public-API-available/).

[cliparse](https://www.npmjs.com/package/cliparse)

# Quick example with Express.js

[Express](http://expressjs.com/) ease the process of developing APIs with Javascript so much! You should definitely give it a try!

    :::bash
    npm init
    npm install -g express # Globally, to be able to use the CLI of express
    npm install -S express # Locally, added as a dependency

Here is how to define a little API with Express (I only wrote the most important lines). Every controller defines many functions to handle the end-points (storing data into the database, etc.).

In this example, the access to all the end-points is restricted, with a [middleware](http://expressjs.com/guide/using-middleware.html) called ```auth```. The clients need to provide specific headers in order to perfom actions with the API.

    :::javascript
    var express = require('express');

    // Controllers
    var usersController       = require('cloud/controllers/users.js'),
        categoriesController  = require('cloud/controllers/category.js');

    // Configuration
    var app  = express(),
        auth = require('cloud/functions/security');
    
    app.use(express.methodOverride()); // Allow PUT and DELETE

    /*** API ***/
    // Users
    app.get('/users',             auth.users, usersController.all);
    app.get('/users/:id',         auth.users, usersController.get);
    app.post('/users',            auth.users, usersController.signup);
    app.post('/users/login',      auth.users, usersController.login);
    app.put('/users/:id',         auth.users, usersController.edit);
    app._delete('/users/:id',     auth.users, usersController._delete);

    // In case of failure
    app.use(function(err, req, res, next) {
        // Some logging
        console.log(err);
        res.status(err.code).json({error: err.message});
    });

    // Let it beee
    app.listen();

That's it! With only a few lines, you can easily get a working API.

This is now the end of this article, hope it will help you! Any feedback appreciated ;)

---

# TL;DR

Here is a little recap of everything above:

- End-points represent either an individual resource or a collection of resources
- Use HTTPS verbs: **GET**, **PUT**, **POST** and **DELETE**
- Use HTTP status codes (those already existing or yours)
- Use JSON
- The body must only contain the requested information. It can be either:
    - **A resource or partial details about a resource** (JSON object)
    - **A list of resources** (JSON array): also provide full link with each resource ([https://my-api/users/123](https://my-api/users/123) for example)
- Metadata must be put inside headers
    - Number of rows when returning a list of results (added by the server)
    - Secret keys for authentication/restricting access to the API (added by the client)
    - Cookies for user management
    - Use the ```Location``` header (with the status code *201 Created*, but without a body) after creating a resource with **POST**, to give the resource's link
- Use query parameters
    - Filtering: ```GET /users?country=US&age=21```
    - Sorting: ```GET /users?sortBy=name```
    - Pagination: ```GET /users?pageAfter=3```
        - Always enable pagination on any collection, even for the small ones
        - Pagination must be based on "*after a resource's identifier*" or "*before a resource's identifier*" to avoid duplicate content across pages
        - Use the status code 200 when the entire collection fits in one page, otherwise 206

# Going further

- [Designing HTTP Interfaces and RESTful Web Services (SFLiveParis2012 2012-06-08)](https://speakerdeck.com/dzuelke/designing-http-interfaces-and-restful-web-services-sfliveparis2012-2012-06-08)
- [Serious Security: How to store your users' passwords safely](https://nakedsecurity.sophos.com/2013/11/20/serious-security-how-to-store-your-users-passwords-safely/)
- [securitychecklist.org](https://securitychecklist.org/)
- [[DevFest Nantes 2015] REST from zero to hero in 45 minutes](https://www.youtube.com/watch?v=_k60dxlMjZ4)
- [JSON Web Tokens (JWT) vs Sessions](https://float-middle.com/json-web-tokens-jwt-vs-sessions/)
- [JSON Web Tokens vs. Session Cookies: In Practice](https://ponyfoo.com/articles/json-web-tokens-vs-session-cookies)
- [Everything you need to know about HTTP security headers](https://blog.appcanary.com/2017/http-security-headers.html)
- [Things to Use Instead of JWT](https://kev.inburke.com/kevin/things-to-use-instead-of-jwt/)
