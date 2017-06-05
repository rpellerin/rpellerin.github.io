# My blog

## Prerequisites

Reading http://docs.getpelican.com/en/3.6.0/install.html is recommended. According to this website, "Pelican currently runs best on Python 2.7.x; earlier versions of Python are not supported. There is provisional support for Python 3.3+, although there may be rough edges, particularly with regards to optional 3rd-party components.".

1. Install Python 2.7.x or 3.3+ with
```bash
sudo apt-get update
sudo apt-get install python # This line or the next one
sudo apt-get install python3
```
2. Install pip **or pip3** if not installed yet ("Python 2.7.9 and later (on the python2 series), and Python 3.4 and later include pip by default, so you may have pip already.").
```bash
wget https://bootstrap.pypa.io/get-pip.py
sudo python get-pip.py
```

Alternatively, you can use your OS package manager, but pip may be outdated:
```bash
sudo apt-get install python-pip
sudo apt-get install python3-pip
```

Then:
```bash
sudo pip3 install -U pip # Upgrade
sudo pip install -U setuptools # Upgrade
sudo pip install virtualenv
```

## Creating the virtual env

Go inside the cloned repository, then:
```bash
virtualenv -p /usr/bin/python3.5 .env
source .env/bin/activate
```

Now install pelican and its dependencies:
```bash
pip install -r requirements.txt
```

## Publishing

```bash
pelican content -o output -s pelicanconf.py --ignore-cache -d -r
make publish
ghp-import -m "Commit message" output
git push origin gh-pages:gh-pages -r
git add -A
git commit -m "Commit message"
git push origin master:master
```
