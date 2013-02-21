<a href="https://github.com/subaddiction/mrkUP">mrkUp is a very simple static website generator based on markdown and written in php.</a>

<a href="https://github.com/michelf/php-markdown">php-markdown library by @michelf</a> is used to parse markdown.

###!WARNING!
this software is NOT intended to be published in online/production environments.
Please review the code and know what are you doing before running this scripts.

####This code does NOT provide any security check, please do NOT upload to your server!

###How to
0) Place the code in a php environment, give to the http server's user or group write permissions to the folder /output/

1) Configure a new website (see /db/default/config)

2) Markdown contents file by file in a folder under /db/ (folder name is the project name, file name is page name, first page must be named "index" or "0_index" - see default template to understand naming and ordering)

3) Visit code directory with browser.

4) Add your own templates.

###Rendering reserved strings
{MENU}

{CONTENT}

###Template reserved strings
{TITLE}

{DESCRIPTION}

{URL}

...(add more in /db/default/config)
