#mrkUP
a very simple static website generator based on markdown and written in php.

###!ATTENTION!
this software is NOT intended to be published in online/production environments.
Please review the code and know what are you doing before running this scripts.

####This code does NOT provide any security check, please do NOT upload to your server!

###How to
0) Place the code in a php environment, give to the http server's user or group write permissions to the folder /output/
1) Markdown contents file by file in a folder under /db/ (folder name is the project name, file name is page name, first page must be named "index" - in both folder and file names no spaces, no special characters)
2) Visit code directory with browser.
3) Add your own templates.

###Reserved strings (do not use in contents markdown)
{TITLE}
{DESCRIPTION}
{URL}
{MENU}
{CONTENT}
