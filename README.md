# My blog

## Prerequisites

Reading http://docs.getpelican.com/en/3.6.0/install.html is recommended. According to this website, "Pelican currently runs best on Python 2.7.x; earlier versions of Python are not supported. There is provisional support for Python 3.3+, although there may be rough edges, particularly with regards to optional 3rd-party components.".

1. Install Python 3+ with
```bash
sudo apt-get update
sudo apt-get install python3
```
2. Install pip if not installed yet:
```bash
wget https://bootstrap.pypa.io/get-pip.py
sudo python get-pip.py
```

Alternatively, you can use your OS package manager, but pip may be outdated:
```bash
sudo apt-get install python3-pip
```

Then:
```bash
sudo apt install python3-testresources # Needed for dependencies of setuptools
# Run the following without `sudo`!
pip3 install -U pip # Upgrade
pip3 install -U setuptools # Upgrade
pip3 install virtualenv
```

## Creating the virtual env

Go inside the cloned repository, then:
```bash
virtualenv -p /usr/bin/python3 .env
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
