#!/usr/bin/env python3
"""Fetches a URL using cloudscraper. Accepts an optional JSON headers argument."""

import sys
import json
import cloudscraper

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Usage: fetch_page.py <url> [headers_json]"}), file=sys.stderr)
        sys.exit(1)

    url = sys.argv[1]
    extra_headers = {}
    if len(sys.argv) >= 3:
        try:
            extra_headers = json.loads(sys.argv[2])
        except json.JSONDecodeError as e:
            print(json.dumps({"error": f"Invalid headers JSON: {e}"}), file=sys.stderr)
            sys.exit(1)

    scraper = cloudscraper.create_scraper(browser={"browser": "chrome", "platform": "linux"})

    try:
        response = scraper.get(url, headers=extra_headers, timeout=30)
        response.raise_for_status()
        print(response.text)
    except Exception as e:
        print(json.dumps({"error": str(e)}), file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    main()
