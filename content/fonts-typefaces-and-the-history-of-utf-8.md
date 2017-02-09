Title: Fonts, Typefaces And The History Of UTF-8
Date: 2016-02-04 19:00
Category: Computers
Tags: font, typeface, utf8, unicode
Slug: fonts-typefaces-and-the-history-of-utf-8
Authors: Romain Pellerin
Summary: Everything about character encodings, fonts and typefaces

First, let's start with the basics. [**What's the difference between a font and a typeface?**](http://www.fastcodesign.com/3028971/whats-the-difference-between-a-font-and-a-typeface)

Briefly, a fond is what we use whereas a typeface is what we see. But still, it's a bit more complex. I'll sum up the original article right below.

In the past, analog printing was done using metal letters rolled in ink and pressed down onto a piece of paper: that was the page layout. For a given typeface (for example Garamond), there were thousands of physical metal blocks, each with the character it was meant to represent, in relief (the type face), existing in different size (10 point, 12, etc.) and different weights (bold, light, medium). Basically, a font is just a subset of a typeface. For example, bolded Garamond in 12 point was considered a different font than normal Garamond in 8 point. But nowadays, both terms can be used interchangeably. However, just in case you would need to know, a reminder could be:

"*The difference between a font and a typeface is the same as that between songs and an album. The former makes up the latter.*"

<hr />

Truth be told, let's now take a close look at UTF-8... Once again, I'll sum up [an article](https://www.smashingmagazine.com/2012/06/all-about-unicode-utf8-character-sets/). But before, just a quick explanation about specific terms (taken from [Wikipedia](https://en.wikipedia.org/wiki/Character_encoding#Code_unit)):

- A **character** is a minimal unit of text that has semantic value.
- A **character set** is a collection of characters that might be used by multiple languages.
    Example: The Latin character set is used by English and most European languages, though the Greek character set is used only by the Greek language.
- A **coded character set** is a character set, where each character is assigned with a unique number.
- A **code point** is a value that can be used in a coded character set. A code point is a 32-bit integer data type, where the lower 21 bits represent a valid code point value and the upper 11 bits are 0.

Now, a story about Unicode and UTF-8...

In the 1960's, the American Standards Association created a 7-bit encoding called *American Standard Code for Information Interchange* (ASCII). Using 7 bits gives 128 possible characters (that was enough for all lower and upper case Latin letters, numerical digits and most punctuation marks; see an [ASCII table](http://www.asciitable.com/)).

A few years later, new microprocessors were created, able to process 8 bits at a time. So they used 8 bits (a byte) to store each character, giving 256 possible values, and thus leaving values from 128 to 255 spare.

Then, in the 80's, Microsoft Windows introduced its own **character set** (also known as *code page*) called **Windows-1251**, whose goal was to fill in those space characters (for example, 224 represents the Cyrillic letter **a**). Furthermore, in the 90's, fifteen other 8 bit character sets were created to cover many different alphabets (called [ISO-8859-1 to ISO-8859-16](https://en.wikipedia.org/wiki/ISO/IEC_8859); ISO-8859-12 was abandoned). Nowadays, in web pages, if you don't provide the charset used, most web browsers will use a default one, depending on your country of residence (ISO-8859-1 will likely be used for English countries).

Nevertheless, in the meantime, a **new standard** called **Unicode** was proposed. The purpose: to assign a unique number (known as a code point) to every letter in every language, in more than 256 slots. At the moment, we have [over 120,000 code points](http://www.babelstone.co.uk/Unicode/unicode.html) and Unicode is also considered a character set itself.

# Unicode

The next few lies were copy-pasted from the original article.

"*The first 128 Unicode code points are the same as ASCII. The range 128-255 contains currency symbols and other common signs and accented characters (aka characters with [diacritical marks](https://en.wikipedia.org/wiki/Diacritic)), and much of it is borrowed ISO-8859-1. After 256 there are many more accented characters. After 880 it gets into Greek letters, then Cyrillic, Hebrew, Arabic, Indic scripts, and Thai. Chinese, Japanese and Korean start from 11904 with many others in between. This is great – no more ambiguity – each letter is represented by its own unique number.*

[...]

*Note that these Unicode code points are officially written in hexadecimal preceded by U+. So the Unicode code point H is usually written as U+0048 rather than 72 (to convert from hexadecimal to decimal: 4\*16+8=72).*"

Having more than 256 characters makes Unicode unable to fit into 8 bits. It could however perfectly fit into a 32 bit encoding. But **Unicode is just a standard**, let's now find out which implementation (or character encoding) was designed.

It's also important to mention that internally, modern web browsers use Unicode. In C or C++ there is a "wide character" (32 bit character called `wchar_t`, an extension of C's 8 bit `char`).

So, now, the remaining problems with Unicode are:

* A lot of existing software and protocols send/receive and read/write 8 bit characters
* Using 32 bits to send/store English text would quadruple the amount of bandwidth/space required

Several attempts were created to solve this problem such as UCS2 and UTF-16 but the winner is UTF-8 (*Universal Character Set Transformation Format 8 bit*). **At the moment and since several years, UTF-8 is the preferred character encoding for the Unicode standard.**

## UTF-8

"*UTF-8 is a clever. It works a bit like the Shift key on your keyboard. Normally when you press the H on your keyboard a lower case “h” appears on the screen. But if you press Shift first, a capital H will appear.*" (copy-pasted from the original article)

UTF-8 treats numbers as follows:

- 0-127 as ASCII
- 128-191 as the **key to be shifted**
- 192-247 as **Shift keys**
    - 192-223 is a simple shift
    - 224-239 are like a double shift
    - 240 and over is a triple shift

Consequently, to represent a character, you have four different ways (either on one, two, three or four bytes):

- 1 byte: a number from 0 to 127, it will represent an ASCII character
- 2 bytes: a sequence of &lt;Shift key from 192 to 223&gt; + &lt;key to be shifted from 128 to 191&gt;
- 3 bytes: &lt;224-239&gt; + &lt;128-191&gt; + &lt;128-191&gt;
- 4 bytes: &lt;240-&gt; + &lt;128-191&gt; + &lt;128-191&gt; + &lt;128-191&gt;

For example, the sequence 191 followed by 224 could never occur. Another example, taken from the original article:

"*For instance, characters 208 and 209 shift you into the Cyrillic range. 208 followed by 175 is character 1071, the Cyrillic Я. [The exact calculation is (208%32)\*64 + (175%64) = 1071](https://en.wikipedia.org/wiki/UTF-8#Examples).*"

"*UTF-8 is therefore a multi-byte variable-width encoding. Multi-byte because a single character like Я takes more than one byte to specify it. Variable-width because some characters like H take only 1 byte and some up to 4.*"

That makes UTF-8 backward compatible with ASCII.

## Avoid most common problems

- Be sure to use UTF-8 on every single page of your website(s).

        :::html
        <meta charset="UTF-8">

    (best when written on top of the page, for performance concerns)

- Choose your font wisely because "*Unicode defines over 110,000 characters*" and "*your browser may not have the correct font to display all of them.*"
- Be careful when dealing with [MySQL to store text](http://blog.tremend.ro/2006/09/26/mysql-php-and-utf8/) ([another article about it in French, also about PHP](http://cahnory.tumblr.com/post/17108999879/utf-8-comment-%C3%A7a-marche))


That's it about Unicode and UTF-8! Hope it was useful.

# Further reading

- [UTF-8 Everywhere](http://utf8everywhere.org/)
- [La maison des horreurs de l’encoding](http://sametmax.com/la-maison-des-horreurs-de-lencoding/)
