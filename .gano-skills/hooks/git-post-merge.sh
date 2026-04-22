#!/bin/bash
# Git post-merge hook - Document merge events to wiki

WIKI_DIR="wiki/dev-sessions"
MERGE_LOG="$WIKI_DIR/merges.log"

mkdir -p "$WIKI_DIR"

# Get merge info
MERGE_BRANCH=$(git rev-parse --abbrev-ref HEAD)
MERGE_DATE=$(date -Iseconds)

{
    echo "[$MERGE_DATE] Merged into: $MERGE_BRANCH"
    echo "HEAD: $(git rev-parse --short HEAD)"
    echo "Remote status: $(git status --porcelain | wc -l) files changed"
    echo ""
} >> "$MERGE_LOG"

echo "✅ Merge event logged"
