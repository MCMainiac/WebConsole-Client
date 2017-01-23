# WebConsole Client by MCMainiac
This is the PHP client of WebConsole.

[Bukkit Link](https://dev.bukkit.org/bukkit-plugins/webconsole/)

# Installation
## Enabling the `php_sockets` extension
Since WebConsole uses TCP sockets to communicate with the server, you might need to enable a php extension called `php_sockets`.

> Sometimes `php_sockets` is already enabled by default. If it is not, WebConsole will inform you.

To enable the `php_sockets` extension:
- open your `php.ini` and go to line 896
- remove the `;` from the beginning of the line to uncomment it
- after you have saved your config, you need to restart your server in order for php to load the extension.
