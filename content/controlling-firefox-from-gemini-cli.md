Title: Controlling Firefox from Gemini CLI
Date: 2026-05-05 21:00
Category: Linux
Tags: linux, firefox, ai, mcp, playwright
Slug: controlling-firefox-from-gemini-cli
Authors: Romain Pellerin
Summary: A few command lines

# A fresh new session

    :::bash
    firefox --marionette
    gemini mcp add firefox-devtools npx firefox-devtools-mcp --connect-existing --marionette-port 2828
    gemini # Just prompt now and tell it to use the Firefox-Devtools MCP

# An already launched session (with your cookies!)

    :::bash
    gemini mcp add playwright npx -y @playwright/mcp@latest --browser firefox
    gemini # Just prompt now and tell it to use the Playwright MCP
