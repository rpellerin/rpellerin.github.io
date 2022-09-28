Title: How To Modify PDF Files
Date: 2016-12-10 00:30
Category: Linux
Tags: pdf, linux
Slug: how-to-modify-pdf-files
Authors: Romain Pellerin
Summary: On to extract/edit/merge pages from one or several PDF files

- `gs` is likely already installed.
- `pdftk` can be installed using `sudo aptitude install pdftk`. `pdftk` [is not recommended because of its old and outdated PDF engine](http://askubuntu.com/questions/809800/whats-the-difference-between-gs-and-pdftk-in-merge-pdf-files).
- `pdfjam` is part of the LaTeX distribution (`sudo aptitude install texlive-full`). However, `pdfjam` [is not recommended](https://blog.dbrgn.ch/2013/8/14/merge-multiple-pdfs/) either because hyperlinks are not preserved.

So my advice is to go with `gs`.

Other commands exist such as `pdfseparate` and `pdfunite`. They are very good but the output files are quite heavy, compared to those obtained using `gs`.

# Convert a PDF to multiple PNGs (one image per page)

    :::bash
    convert input.pdf -density 300 -background 'white' -alpha remove output.png

# Add a password to a PDF file

    :::bash
    pdftk input.pdf output output.pdf userpw <password here>

# Reduce size/quality of a PDF file

    :::bash
    ps2pdf -dPDFSETTINGS=/ebook input.pdf output.pdf

`/ebook` can also be replaced with `/screen` for further reduction.

# Extracting pages

    :::bash
    # Extracts all pages from 1 to 5
    gs -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dSAFER -dFirstPage=1 -dLastPage=5 -sOutputFile=output.pdf input.pdf

    # Extracts all pages from 1 to 2 and 4 to the end
    pdftk input.pdf cat 1-2 4-end output output.pdf

    # Extract only pages 1, 2, 4 and 5
    pdftk input.pdf cat 1 2 4 5 output output.pdf

    # Split each page into a file
    pdftk input.pdf burst
    # OR
    file=input.pdf
    pages=$(pdfinfo "$file" | grep "Pages" | awk '{print $2}') 
    echo "Detect $pages in $file";
    filename="${file%.*}";
    for i in $(seq -w 1 "$pages"); do
        pdftk "$file" cat "$i" output "$filename-$i.pdf";
    done;

# Editing one page

Use Gimp (import with 300-dpi setting).

# Merging several PDF files

    :::bash
    gs -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dSAFER -sOutputFile=output.pdf input1.pdf input2.pdf input3.pdf
    pdftk input1.pdf input2.pdf input3.pdf cat output output.pdf
    pdfjam input1.pdf input2.pdf input3.pdf -o output.pdf

# Convert one or several JPG files into a single pdf

First, in `/etc/ImageMagick-6/policy.xml`, comment out the last 6 lines:

    :::xml
    <!-- <policy domain="coder" rights="none" pattern="PS" />
    <policy domain="coder" rights="none" pattern="PS2" />
    <policy domain="coder" rights="none" pattern="PS3" />
    <policy domain="coder" rights="none" pattern="EPS" />
    <policy domain="coder" rights="none" pattern="PDF" />
    <policy domain="coder" rights="none" pattern="XPS" /> -->

Then:

    :::bash
    convert image1.jpg image2.jpg output.pdf

## Set the PDF size to A4 (210 x 297 mm)

Make sure your image has a resolution of 1050x1485 pixels. Then:

    :::bash
    convert input.jpg -density 50 -units pixelspercentimeter output.pdf

If the image is of a different resolution, [use this](https://unix.stackexchange.com/a/20057):

    :::bash
    i=100; convert input.png -compress jpeg -quality 70 \
      -density ${i}x${i} -units pixelspercentimeter \
      -resize $((i*21))x$((i*29.7)) \
      -repage $((i*21))x$((i*29.7)) output.pdf

[Source](https://legacy.imagemagick.org/discourse-server/viewtopic.php?t=33309)
