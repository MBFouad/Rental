#!/bin/bash
export PATH="/home/mbfouad/.nvm/versions/node/v22.14.0/bin:$PATH"
exec npx -y @modelcontextprotocol/server-puppeteer "$@"
