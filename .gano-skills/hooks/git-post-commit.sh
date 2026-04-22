#!/bin/bash
# Git post-commit hook - Auto-document commits to wiki

WIKI_DIR="wiki/dev-sessions"
COMMIT_LOG="$WIKI_DIR/commits.log"

# Get commit info
COMMIT_HASH=$(git rev-parse --short HEAD)
COMMIT_MSG=$(git log -1 --pretty=%B)
COMMIT_AUTHOR=$(git log -1 --pretty=%an)
COMMIT_DATE=$(git log -1 --pretty=%aI)

# Create directory if needed
mkdir -p "$WIKI_DIR"

# Log commit
{
    echo "[$COMMIT_DATE] $COMMIT_HASH — $COMMIT_AUTHOR"
    echo "Message: $COMMIT_MSG"
    echo "Files changed: $(git diff-tree --no-commit-id --name-only -r HEAD | tr '\n' ',' | sed 's/,$//')"
    echo ""
} >> "$COMMIT_LOG"

echo "✅ Commit logged to $COMMIT_LOG"
