#!/usr/bin/env python3
"""Fetches a single page from the dizilah API using cloudscraper."""

import sys
import json
import cloudscraper

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Usage: fetch_page.py <url>"}), file=sys.stderr)
        sys.exit(1)

    url = sys.argv[1]
    scraper = cloudscraper.create_scraper(browser={"browser": "chrome", "platform": "linux"})

    try:
        response = scraper.get(url, timeout=30)
        response.raise_for_status()
        print(response.text)
    except Exception as e:
        print(json.dumps({"error": str(e)}), file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    main()
