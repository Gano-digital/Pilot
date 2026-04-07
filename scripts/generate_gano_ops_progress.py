#!/usr/bin/env python3
"""
Genera tools/gano-ops-hub/public/data/progress.json desde TASKS.md y la cola Claude.

Uso:
  python scripts/generate_gano_ops_progress.py
  python scripts/generate_gano_ops_progress.py --write   # escribe JSON (default)
  python scripts/generate_gano_ops_progress.py --stdout  # solo imprime
"""
from __future__ import annotations

import argparse
import json
import re
import sys
from datetime import datetime, timezone
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
TASKS_PATH = ROOT / "TASKS.md"
QUEUE_PATH = ROOT / "memory" / "claude" / "dispatch-queue.json"
STATE_PATH = ROOT / "memory" / "claude" / "dispatch-state.json"
OUT_PATH = ROOT / "tools" / "gano-ops-hub" / "public" / "data" / "progress.json"

SECTION_RE = re.compile(r"^##\s+(.+)$")
ITEM_RE = re.compile(r"^\s*-\s+\[([ xX])\]")


def parse_tasks_md(text: str) -> dict:
    sections: list[dict] = []
    current = {"name": "(inicio)", "open": 0, "done": 0}
    total_open = total_done = 0

    for line in text.splitlines():
        msec = SECTION_RE.match(line)
        if msec:
            if current["name"] != "(inicio)" or current["open"] or current["done"]:
                sections.append(current)
            current = {"name": msec.group(1).strip(), "open": 0, "done": 0}
            continue
        mit = ITEM_RE.match(line)
        if mit:
            done = mit.group(1).lower() == "x"
            if done:
                current["done"] += 1
                total_done += 1
            else:
                current["open"] += 1
                total_open += 1

    if current["name"] != "(inicio)" or current["open"] or current["done"]:
        sections.append(current)

    return {
        "total_open": total_open,
        "total_done": total_done,
        "total_items": total_open + total_done,
        "sections": sections,
    }


def load_dispatch() -> dict:
    queue_tasks: list[dict] = []
    if QUEUE_PATH.is_file():
        q = json.loads(QUEUE_PATH.read_text(encoding="utf-8"))
        queue_tasks = list(q.get("tasks", []))

    completed: list[str] = []
    if STATE_PATH.is_file():
        st = json.loads(STATE_PATH.read_text(encoding="utf-8"))
        completed = list(st.get("completed", []))

    pending = [t for t in queue_tasks if t.get("id") not in completed]
    state_present = STATE_PATH.is_file()
    note = None
    if queue_tasks and not state_present:
        note = (
            "Sin dispatch-state.json en este entorno: en CI los pendientes pueden "
            "coincidir con el total de la cola. Localmente, `claude_dispatch.py complete` "
            "actualiza el estado (archivo en .gitignore)."
        )
    return {
        "queue_total": len(queue_tasks),
        "completed_count": len(completed),
        "pending_count": len(pending),
        "dispatch_state_present": state_present,
        "dispatch_note": note,
        "completed_ids": completed[-20:],  # últimos 20, no hinchar JSON
        "next_suggestions": [
            {"id": t["id"], "title": t.get("title", "")}
            for t in sorted(pending, key=lambda x: (x.get("priority", 99), x.get("id", "")))[:5]
        ],
    }


def build_payload() -> dict:
    tasks_block = {"total_open": 0, "total_done": 0, "total_items": 0, "sections": []}
    if TASKS_PATH.is_file():
        tasks_block = parse_tasks_md(TASKS_PATH.read_text(encoding="utf-8"))

    pct = 0.0
    if tasks_block["total_items"] > 0:
        pct = round(100.0 * tasks_block["total_done"] / tasks_block["total_items"], 1)

    return {
        "schema_version": 1,
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "repository": "Gano-digital/Pilot",
        "tasks_md": tasks_block,
        "tasks_completion_pct": pct,
        "dispatch": load_dispatch(),
        "audit_log": [],  # reservado: append eventos desde workflows / agentes
        "links": {
            "issues": "https://github.com/Gano-digital/Pilot/issues",
            "pulls": "https://github.com/Gano-digital/Pilot/pulls",
            "actions": "https://github.com/Gano-digital/Pilot/actions",
            "security": "https://github.com/Gano-digital/Pilot/security",
        },
    }


def main() -> None:
    p = argparse.ArgumentParser()
    p.add_argument("--stdout", action="store_true", help="Imprimir JSON a stdout")
    p.add_argument(
        "--output",
        type=Path,
        default=OUT_PATH,
        help="Ruta del JSON de salida",
    )
    args = p.parse_args()
    payload = build_payload()
    text = json.dumps(payload, ensure_ascii=False, indent=2) + "\n"

    if args.stdout:
        sys.stdout.write(text)
        return

    args.output.parent.mkdir(parents=True, exist_ok=True)
    args.output.write_text(text, encoding="utf-8")
    print(f"Escrito {args.output.relative_to(ROOT)}")


if __name__ == "__main__":
    main()
