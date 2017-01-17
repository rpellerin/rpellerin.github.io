Title: The Art Of Writing
Date: 2016-01-27 18:15
Category: general
Tags: writing, english, latex, cv, resume, cover lover, report
Slug: the-art-of-writing
Authors: Romain Pellerin
Summary: How to write reports, CVs, cover letter, the proper way, with the most perfectly suited tool, LaTeX.

Writing any kind of profesionnal document in English is not a straightforward thing, especially if it's not something you do on a regular basis. There are rules to stick to, a formatting to respect, mistakes to avoid. Readers must be able to find information quickly, at the right place. Might it be either in a glossary, a footnote, an appendix, and so on., you must follow a specific order.

This year, I decided to write my internship report in English and thus stick to what English readers are used to. In general, thoses rules are almost the same as in French, almost. However, there are a few significant differences you'd better know.

I'm going to first explain how I would write a technical report — it works for an internship report as well, since it's supposed to be technical.

# Writing a report

I decided to base my report on [a book design](https://en.wikipedia.org/wiki/Book_design). However, you can find [here a complete guide about profesionnal and technical writing](https://en.wikibooks.org/wiki/Professional_and_Technical_Writing/Table_of_Contents).

The report is done in **a two-sided printing style**. Here is the plan I would follow for an internship or a technical report:

- Cover (1 page)
- *Blank page*
- Acknowledgments (1 page)
- *Blank page*
- Table of contents
- Table of figures and tables
- Foreword (including a technical summary) ([what is the difference between a preface, a foreword, and an introduction?](http://www.writersandeditors.com/preface__foreword__or_introduction__57375.htm))
- **Introduction - BODY MATTER - Conclusion**
- Glossary and index if needed (on the same page)
- Bibliography
- Appendices (must start on a right-hand (odd-numbered) page, so add a blank page right before if needed): 1 page containing the title "*Appendices*", and then the actual appendices

Do you need a **glossary**? I will mostly depends on your audience. Glossaries are meant to define complex or unsual words. Glossaries should also contain the definitions of any acronym used. One rule of thumb is pretty simple: who is your audience? Who will read your report. If the answer is people like you, who have the same knowledge, and who will likely understand the report well, then no need for a glossary. On the contrary, if it's intended to be read by anyone, then you likely need a glossary.

**[An index](https://en.wikipedia.org/wiki/Index_(publishing))** is mostly encountered within novels but you might add it in the glossary (add the page numbers after the definitions). It's generally not mandatory at all.

**Footnotes** are rather different. There are meant to add short pieces of information not necessarly related to the main content (like anecdotes, complementary information, web links). In regular books, you might encounter notes at the end of them, which purpose is approximatively the same. However, it's a bit annoying, as it interrupts the flow of reading. It's better suited for long footnotes that wouldn't fit at the bottom of a page. Personally, I don't write notes at the end of a document, I would rather write footnotes. And sometimes, when footnotes are extremely short, you might want to use either [a dash or a semicolon to add complementary information](http://english.stackexchange.com/questions/250492/what-is-the-difference-between-a-dash-and-a-semicolon/250494#250494).

**Appendices** are for any other document you haven't produced yourself, or big ones, like charts, graphs or emails. They should not be an important part, the reader must be able to understand your point without seeing the appendix. Otherwise, it must go in the main content.

Pay attention to **headings**, there are [different rules regarding capitalization](http://titlecapitalization.com/).

# Writing a formal business letter

- [How to format it (the layout)](http://www.dailywritingtips.com/how-to-format-a-us-business-letter/) (**read the comment as well**)
- The Closing: use "Yours sincerely" when you know your recipient's name, otherwise use "Yours faithfully". If you know the person quite well, you can use "Best regards" or "Kind regards".


# Using modern tools

There are great tools out there to write beautiful reports, not to mention Microsoft Word, Pages or LibreOffice Writer. But these ones are too focused on the form instead of the content. People spend way too much time formatting, adding colors, setting the margins, and so forth, instead of... writing the actual content. Moreover, between two given versions of the same program (let's say Word 2003 and Word 2007), there might be huge differences introducing compatibility issues, not easily solvable.

So, is there some sort of an ultimate solution? The answer is **YES**. Let me introduce LaTeX.

## LaTeX

Basically and quickly, it's an open source tool written a while ago which allows you to write any kind of document and export them as PDF (and some other formats). You can write books, reports, CVs, slides, anything! You write it as plain text surrounded with some sort of code (it's a markup language actually). It's available on Linux, Mac and Windows. As it's plain old text, there's no compatibility issue **at all**. What is more, LaTeX doesn't evolve that much over years so. Should it does some day, they will always ensure backward compatibility. When you're done with it, you just compile it and you're good: a beautiful PDF is procuded. There are plenty of tools available for each platform. For instance, I recommend `pdflatex` on Linux. And if you're too lazy to install any of them, use [ShareLaTeX](https://www.sharelatex.com/).

[See this tutorial to install the latest version of LaTeX.](https://github.com/scottkosty/install-tl-ubuntu/blob/master/install-tl-ubuntu). Otherwise, you might install it using your system package manager:

    :::bash
    sudo aptitude install texlive-full biber # biber is for the bibliography

Then, make sure LaTeX's package manager is working:

    :::bash
    sudo aptitude install xzdec
    tlmgr init-usertree
    tlmgr update --list

In the following lines, I will give some pieces of advice regarding LaTeX and how to use it properly but I will assume you already have some knowledge about it. My purpose is not to write a tutorial about LaTeX at all. These are mainly reminders for myself.

You can find some examples of CVs, cover letters, reports and slideshows on [my GitHub account](https://github.com/rpellerin/LaTeX-templates). For advice about CVs, [see my article "How Not To Get Hired"]({filename}/how-not-to-get-hired.md)

### The commands `\input{}` and `\include{}`

`\input{}` doesn't do `\newpage`, unlike `\include{}`. [More info](http://tex.stackexchange.com/questions/246/when-should-i-use-input-vs-include).

### Splitting files

Is a best practice. I personally try to have "conf" files and "content" files. Then I gather them in the main Tex file (thanks to `\input{}`), which will be compiled eventually.

### Bibliography

It's a really important part of any report or publication. Basically, there are two engines to compile the ".bib" file: `bibtex` and `biber`. As well, there are two LaTeX packages to format citations and bibliographies: `natbib` and `biblatex`. I personally recommend using **`biber`** and **`biblatex`**. [Read here why.](http://tex.stackexchange.com/a/25702/96790).

### Going further with LaTeX

- [Tout ce que vous avez toujours voulu savoir sur LATEX sans jamais oser le demander](http://framabook.org/docs/latex/framabook-versionenligne_v1_5.pdf)
- [Everything You Wanted To Know About TeX, But Were Too Afraid To Ask](http://xoph.co/20111024/latex-tutorial/)
- [Documentation Ubuntu FR](http://doc.ubuntu-fr.org/latex)
- [How to remove everything related to TeX Live for fresh install on Ubuntu?](http://tex.stackexchange.com/questions/95483/how-to-remove-everything-related-to-tex-live-for-fresh-install-on-ubuntu/95502#95502)

## Unix tools

Now that you started using LaTeX (I'm glad you did!), you might wonder how to check spelling, grammar, conjugation, etc.? Actually, there are dozens of tools for Linux.

### Grammar and style

[Here are two old fashionned tools to check grammar and style](http://dsl.org/cookbook/cookbook_15.html#SEC220), installable with

    :::bash
    sudo apt-get install diction

`diction` and `style` are both great tools but don't trust them completely, they tend to be really strict.

### Spelling

There are three main tools really wide spread among the Linux community

- `aspell`
- `ispell`
- `hunspell`

They mostly do the same job. However, I would rather recommand `aspell` as it's the preferred tool for programmers ([source](http://blog.binchen.org/posts/what-s-the-best-spell-check-set-up-in-emacs.html)).

    :::bash
    aspell -l en-US -c -t <file.tex>

### General tools

#### TextLint

Another awesome tool to check the style, mostly.

    :::bash
    git clone https://github.com/DamienCassou/textlint
    cd textlint
    ./textlint.bash \
        <file.tex> \
        ./Linux32/pharo \
        ./TextLint.tmbundle/Support/TextLint.image

They also offer a [web application](http://textlint.lukas-renggli.ch/).

#### LanguageTool

Finally, here is a general word/grammer checker (probably one of the best): [languagetool.org](https://languagetool.org/). According to their [official documentation regarding LaTeX](http://wiki.languagetool.org/checking-la-tex-with-languagetool), the best way to check any file is as follows. You will need Java version 8 or higher.

    :::bash
    sudo add-apt-repository ppa:openjdk-r/ppa
    sudo apt-get update
    sudo apt-get install openjdk-8-jdk
    sudo update-alternatives --config java
    sudo update-alternatives --config javac
    java -version # Make sure it worked

Now, [download languagetool (stand-alone version for Desktop)](https://languagetool.org/#download). Then:

    :::bash
    detex <file.tex> |java -jar languagetool-commandline.jar -l en-US -c UTF-8 |less

`detex` will turn your Tex file into a plain text one (it will remove the LaTeX formatting). You can disable rules. Simply add the argument `-d` with the rules' IDs (as many as you want), for example:

    :::bash
    java -jar languagetool-commandline.jar -l en-US -c UTF-8 -d 'EN_QUOTES,UPPERCASE_SENTENCE_START' <file.tex>


# Books

To conclude, here a two famous books to produce high-quality documents in English:

- [The Chicago Manual of Style](https://en.wikipedia.org/wiki/The_Chicago_Manual_of_Style): the ultimate book to learn how to write documents in English and be the perfect writer. Best suited for journalists.
- [The Elements of Style](https://en.wikipedia.org/wiki/The_Elements_of_Style): a short and yet excellent book to know the basic rules and avoid common mistakes and pitfalls.

# Great articles

- [How to Write a Great Statement of Purpose](http://www.uni.edu/~gotera/gradapp/stmtpurpose.htm)
