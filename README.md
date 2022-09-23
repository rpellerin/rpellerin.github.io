# My blog

## Prerequisites

```bash
# Run the following without `sudo`!
pip3 install -U pip # Upgrade
pip3 install -U setuptools # Upgrade
```

## Creating the virtual env

Go inside the cloned repository, then:

```bash
python3 -m venv .env
source .env/bin/activate
```

Now install pelican and its dependencies:

```bash
pip3 install -r requirements.txt
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
