#!/usr/bin/env python3

import glob
import os
import re

articles = glob.glob('content/*.md')

def replace_date(file, datetime):
    with open(file, 'r') as file_descriptor:
        file_content = file_descriptor.read()
        text, counter = re.subn(r'^Modified: \d{4}-\d{2}-\d{2} \d{2}:\d{2}$', f'Modified: {datetime}', file_content, flags=re.MULTILINE)
        if counter == 0:
            # No Modified: line was found, let's add one
            text, counter = re.subn(r'^(Date: \d{4}-\d{2}-\d{2} \d{2}:\d{2})$', rf'\1\nModified: {datetime}', file_content, flags=re.MULTILINE)
            if counter == 0:
                print(f'Could not update the Modified date from {file}')
                return

        with open(file, 'w') as fd:
            fd.write(text)
            print(f'Updated {file} with new datetime {datetime}')

for article in articles:
    number_of_commits = int(str(os.popen(f'git log --oneline -- {article} | wc -l').read()).strip())
    if number_of_commits == 1:
        print(f'Only 1 commit for {article} found, not updating it...')
        continue

    last_edit_datetime = str(os.popen(f'git log -1 --pretty="format:%ci" -- {article} | cut -c 1-16').read()).strip()
    replace_date(article, last_edit_datetime)
