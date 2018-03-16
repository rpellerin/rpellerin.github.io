Title: Ruby and Ruby on Rails from scratch
Date: 2018-03-16 23:50
Category: Code
Tags: code, ruby, rails
Slug: ruby-and-ruby-on-rails-from-scratch
Authors: Romain Pellerin
Summary: How to learn Ruby and Rails

Today I started learning a new language: Ruby! Coming from a JavaScript world, [this article](http://frontendgods.com/getting-started-with-ruby-for/) helped me a lot ([backed-up here, should it ever vanish from the Internet]({filename}/extra/javascript-to-ruby.html)). In this article, I sum up everything I've learned so far. It's a sort of memo for myself.

# Learning resources

## Ruby

- [https://openclassrooms.com/courses/lancez-vous-dans-la-programmation-avec-ruby](https://openclassrooms.com/courses/lancez-vous-dans-la-programmation-avec-ruby) (French)
- [http://tryruby.org](http://tryruby.org): awesome interactive tutoral
- [http://rubykoans.com/](http://rubykoans.com/): learn by doing TDD (Test-Driven Development): make all tests pass

## Rails

- [http://rubyonrails.org/doctrine/](http://rubyonrails.org/doctrine/)

# Install

[Install rbenv](https://github.com/rbenv/rbenv#basic-github-checkout). In short, here is how:

    :::bash
    sudo apt update && sudo apt install openssl libicu-dev libssl-dev libreadline-dev zlib1g-dev
    git clone https://github.com/rbenv/rbenv.git ~/.rbenv
    cd ~/.rbenv && src/configure && make -C src
    echo 'export PATH="$HOME/.rbenv/bin:$PATH"' >> ~/.zshrc
    ~/.rbenv/bin/rbenv init
    # Open a new shell
    mkdir -p "$(rbenv root)"/plugins
    git clone https://github.com/rbenv/ruby-build.git "$(rbenv root)"/plugins/ruby-build
    rbenv install 2.5.0
    rbenv global 2.5.0

    # Check everything is installed and correctly set up
    curl -fsSL https://github.com/rbenv/rbenv-installer/raw/master/bin/rbenv-doctor | bash

Then, add must-have stuff:

    :::bash
    gem install bundler

    # In a fresh new Ruby project folder, create a Gemfile
    rbenv local 2.5.0 # Set version 2.5.0 for this project, same as .nvmrc for Node projects
    bundle init
    bundle install --path vendor --binstubs bin # Set up .bundle config to install packages to the local folder
    # Add vendor and .bundle to your .gitignore file

    # In a Ruby project folder, install all dependencies
    bundle install # or simply `bundle`


# Stuff to know

- `;` is optional
- parentheses optional in `if` statements
- `==` vs `===`:  [https://stackoverflow.com/questions/4467538/what-does-the-operator-do-in-ruby](https://stackoverflow.com/questions/4467538/what-does-the-operator-do-in-ruby)
- `puts` (adds new line) vs `print` (no new line)
- `.each do |item| ... end`
- `10.times do |i| ... end`
- `if`, `elsif`, `else`, `end`
- `array = [1,2]`
- `array[0]`
- When you place a colon in front of a simple word, you get a Ruby symbol: `:a`
- `hash = {a:1,b:2} # creates symbols :a and :b`
- `hash[:a]`
- `hash['ok'] = 'yolo' # ok is not a symbol`
- `return` statement optional, last line is implicitely returned
- `func(a,b)` == `func a, b`
- `obj.func` == `obj.func()`
- `def initialize ... end`
- `@classMemberVariable`
- `def func` == `def func()`
- `return true if ....` == `if ...<line break>return true<line break>end`
- Inheritance: `class Child < Mother`
- Replace first occurrence in string: `string['word'] = 'new word'`
- `ratings = Hash.new(0)`: all new hashes will default to 0 as their value
- `do..end` vs curly braces for blocks in Ruby: [https://stackoverflow.com/questions/5587264/do-end-vs-curly-braces-for-blocks-in-ruby](https://stackoverflow.com/questions/5587264/do-end-vs-curly-braces-for-blocks-in-ruby)

## Ranges

- `x..y` (y included) vs `x...y` (y not included)

## Tests

- `assert_equal x,y`
- `assert_match(/regex/, x)`
