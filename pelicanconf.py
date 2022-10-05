AUTHOR = 'Romain Pellerin'
DESCRIPTION = 'Romain Pellerin\'s personal website: blog posts, guitar tabs, contact information.'
SITENAME = 'Romain Pellerin\'s Blog'
SITEURL = ''

PATH = 'content'

TIMEZONE = 'Europe/Berlin'

DEFAULT_LANG = 'en'
LOCALE = 'en_US.utf8'

YEAR_ARCHIVE_SAVE_AS = 'posts/{date:%Y}/index.html'
MONTH_ARCHIVE_SAVE_AS = 'posts/{date:%Y}/{date:%-m}/index.html'

# Feeds
FEED_DOMAIN = '//romainpellerin.eu'

# RSS
FEED_RSS = None
FEED_ALL_RSS = 'feeds/rss.xml'
RSS_FEED_SUMMARY_ONLY = False
AUTHOR_FEED_RSS = None
CATEGORY_FEED_RSS = 'feeds/categories/{slug}/rss.xml'
TRANSLATION_FEED_RSS = None

# Atom
FEED_ATOM = None
FEED_ALL_ATOM = 'feeds/atom.xml'
AUTHOR_FEED_ATOM = None
CATEGORY_FEED_ATOM = 'feeds/categories/{slug}/atom.xml'
TRANSLATION_FEED_ATOM = None

# Blogroll
LINKS = (#('Pelican', 'https://getpelican.com/'),
         #('Python.org', 'https://www.python.org/'),
         #('Jinja2', 'https://palletsprojects.com/p/jinja/'),
         #('You can modify those links in your config file', '#'),
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


# Default value for MARKDOWN, only `markdown.extensions.toc` is an addition of mine
MARKDOWN = {
    'extension_configs': {
        'markdown.extensions.codehilite': {'css_class': 'highlight'},
        'markdown.extensions.extra': {},
        'markdown.extensions.meta': {},
        'markdown.extensions.toc': {},
    },
    'output_format': 'html5',
}
