#!/usr/bin/env python3
"""
Static QA gates for catalog convergence rollout.

Usage:
  python scripts/qa_catalog_release.py --gate uiux
  python scripts/qa_catalog_release.py --gate content
"""

from __future__ import annotations

import argparse
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]


def read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8")


def check_contains(path: Path, needle: str, errors: list[str]) -> None:
    text = read_text(path)
    if needle not in text:
        errors.append(f"Missing `{needle}` in {path.as_posix()}")


def run_uiux_gate() -> int:
    errors: list[str] = []
    check_contains(
        ROOT / "wp-content/themes/gano-child/templates/shop-premium.php",
        "data-gano-catalog",
        errors,
    )
    check_contains(
        ROOT / "wp-content/themes/gano-child/templates/page-ecosistemas.php",
        "data-gano-catalog",
        errors,
    )
    check_contains(
        ROOT / "wp-content/themes/gano-child/templates/page-seo-landing.php",
        "data-gano-catalog",
        errors,
    )
    check_contains(
        ROOT / "wp-content/themes/gano-child/js/gano-catalog-intelligence.js",
        "gano_catalog_mode_change",
        errors,
    )
    check_contains(
        ROOT / "wp-content/themes/gano-child/css/gano-catalog-intelligence.css",
        "gano-catalog-mode-switch",
        errors,
    )

    if errors:
        print("UI/UX gate failed:")
        for item in errors:
            print(f"- {item}")
        return 1

    print("UI/UX gate passed.")
    return 0


def run_content_gate() -> int:
    errors: list[str] = []
    check_contains(
        ROOT / "wp-content/themes/gano-child/templates/page-seo-landing.php",
        "gano_get_reseller_catalog_products",
        errors,
    )
    check_contains(
        ROOT / "wp-content/themes/gano-child/functions.php",
        "sync-missing",
        errors,
    )
    check_contains(
        ROOT / "wp-content/themes/gano-child/functions.php",
        "gano_get_catalog_nav_modes",
        errors,
    )
    check_contains(
        ROOT / "wp-content/themes/gano-child/functions.php",
        "gano_catalog_price_is_valid",
        errors,
    )

    if errors:
        print("Content gate failed:")
        for item in errors:
            print(f"- {item}")
        return 1

    print("Content gate passed.")
    return 0


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument(
        "--gate",
        required=True,
        choices=("uiux", "content"),
        help="Gate to run",
    )
    args = parser.parse_args()

    if args.gate == "uiux":
        return run_uiux_gate()
    return run_content_gate()


if __name__ == "__main__":
    sys.exit(main())
