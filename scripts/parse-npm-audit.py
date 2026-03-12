#!/usr/bin/env python3
"""
parse-npm-audit.py
Reads the JSON output of `npm audit --json` from stdin and prints a
human-readable summary with an exit code of 1 if critical vulnerabilities
are found.

Usage:
    npm audit --json | python3 scripts/parse-npm-audit.py
"""
import json
import sys


def main() -> int:
    try:
        data = json.load(sys.stdin)
    except json.JSONDecodeError as exc:
        print(f"Could not parse npm audit JSON: {exc}", file=sys.stderr)
        return 0  # Do not fail the build on parse error

    vulnerabilities = data.get("vulnerabilities", {})
    counts: dict[str, int] = {}
    for vuln in vulnerabilities.values():
        sev = vuln.get("severity", "unknown")
        counts[sev] = counts.get(sev, 0) + 1

    critical = counts.get("critical", 0)
    high = counts.get("high", 0)
    moderate = counts.get("moderate", 0)
    low = counts.get("low", 0)

    print(
        f"Summary: {critical} critical, {high} high, "
        f"{moderate} moderate, {low} low severity vulnerabilities"
    )

    if critical > 0:
        print(
            "ACTION REQUIRED: Critical vulnerabilities found – "
            "update dependencies immediately."
        )
        for name, vuln in vulnerabilities.items():
            if vuln.get("severity") == "critical":
                via = vuln.get("via", [])
                advisories = [
                    v.get("url", "") for v in via if isinstance(v, dict)
                ]
                print(f"  - {name}: {', '.join(advisories) or 'see npm audit'}")
        return 1

    if high > 0:
        print("WARNING: High-severity vulnerabilities found. Review and update soon.")

    return 0


if __name__ == "__main__":
    sys.exit(main())
