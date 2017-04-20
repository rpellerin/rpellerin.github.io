#!/usr/bin/env python
# -*- coding: utf-8 -*- #
from __future__ import unicode_literals

AUTHOR = 'Romain Pellerin'
DESCRIPTION = 'Romain Pellerin\'s personal website: blog posts, guitar tabs, contact information.'
SITENAME = 'Romain Pellerin\'s Blog'
SITEURL = ''

PATH = 'content'

TIMEZONE = 'Europe/Paris'

DEFAULT_LANG = u'en'
LOCALE = "en_US.utf8"

YEAR_ARCHIVE_SAVE_AS = 'posts/{date:%Y}/index.html'
MONTH_ARCHIVE_SAVE_AS = 'posts/{date:%Y}/{date:%-m}/index.html'

# Feeds
FEED_DOMAIN = '//romainpellerin.eu'

# RSS
FEED_RSS = None
FEED_ALL_RSS = 'feeds/rss.xml'
RSS_FEED_SUMMARY_ONLY = False
AUTHOR_FEED_RSS = None
CATEGORY_FEED_RSS = 'feeds/categories/%s/rss.xml'
TRANSLATION_FEED_RSS = None

# Atom
FEED_ATOM = None
FEED_ALL_ATOM = 'feeds/atom.xml'
AUTHOR_FEED_ATOM = None
CATEGORY_FEED_ATOM = 'feeds/categories/%s/atom.xml'
TRANSLATION_FEED_ATOM = None

# Blogroll
LINKS = (#('Pelican', 'http://getpelican.com/'),
         #('Python.org', 'http://python.org/'),
         #('Jinja2', 'http://jinja.pocoo.org/')
        )

# Social widget
SOCIAL = (#('You can add links in your config file', '#'),
          #('Another social link', '#'),
         )

DEFAULT_PAGINATION = False

# Uncomment following line if you want document-relative URLs when developing
RELATIVE_URLS = True

THEME = "./my-theme"

# Used for favicon.ico, robots.txt
STATIC_PATHS = ['extra', 'images', 'videos', 'guitar']
EXTRA_PATH_METADATA = {
    'extra/favicon.ico': {'path': 'favicon.ico'},
    'extra/privacy-policy.html': {'path': 'privacy-policy.html'},
    'extra/redirect-to-archives.html': {'path': 'posts/index.html'},
    'extra/CNAME': {'path': 'CNAME'}
}

# Disable parsing HTML files
READERS = {'html': None}
