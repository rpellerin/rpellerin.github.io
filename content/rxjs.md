Title: RxJS
Date: 2018-05-13 20:00
Category: Code
Tags: javascript, rxjs
Slug: rxjs
Authors: Romain Pellerin
Summary: Cheatsheet for RxJS

*This is a draft article likely to evolve or be deleted or merged with another article in the future.*

# General concepts

- **`Observer`**: when subscribing, get all past emitted values
- **`Subject`**: when subscribing, get only next-to-come emitted values
- **`BehaviorSubject`**: like `Subject` but keeps a current state, which is the last received value or the one passed to the constructor if none received, then emits it immediately when being subscribed to
- **`ReplaySubject`**: when subscribing, get all last X emitted values
- **`AsyncSubject`**: emits the result of an asynchronous operation when the observable completes

# Misc

- `shareReplay`: when subscribing, emits the last value. This helps avoid making a new HTTP request for each new subscription, for instance.

# Resources

- [RxJS Les clefs pour comprendre les observables (T. Chatel)](https://www.youtube.com/watch?v=TrDqaABq-UY)
- [RxViz - Animated playground for Rx Observables](https://rxviz.com/)
- [RxMarbles: Interactive diagrams of Rx Observables](rxmarbles.com)
