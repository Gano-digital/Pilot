#!/usr/bin/env python3
"""Valida .github/agent-queue/tasks*.json: estructura, ids únicos, marcador agent-task-id."""
from __future__ import annotations

import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
QUEUE_DIR = ROOT / ".github" / "agent-queue"
FILES = ["tasks.json", "tasks-wave2.json", "tasks-wave3.json"]


def main() -> int:
    all_ids: list[tuple[str, str]] = []
    errors: list[str] = []

    for name in FILES:
        path = QUEUE_DIR / name
        if not path.exists():
            errors.append(f"Falta {path.relative_to(ROOT)}")
            continue
        data = json.loads(path.read_text(encoding="utf-8"))
        tasks = data.get("tasks")
        if not isinstance(tasks, list):
            errors.append(f"{name}: raíz debe tener array 'tasks'")
            continue
        for i, t in enumerate(tasks):
            if not isinstance(t, dict):
                errors.append(f"{name}[{i}]: no es objeto")
                continue
            tid = t.get("id")
            if not tid or not isinstance(tid, str):
                errors.append(f"{name}[{i}]: falta id string")
                continue
            body = t.get("body", "")
            if not isinstance(body, str):
                errors.append(f"{name} id={tid}: body debe ser string")
                continue
            expected = f"<!-- agent-task-id:{tid} -->"
            if expected not in body:
                errors.append(f"{name} id={tid}: body debe contener exactamente `{expected}`")
            for key in ("title", "scope", "labels"):
                if key not in t:
                    errors.append(f"{name} id={tid}: falta campo `{key}`")
            all_ids.append((name, tid))

    seen: dict[str, str] = {}
    for fname, tid in all_ids:
        if tid in seen:
            errors.append(f"id duplicado `{tid}` en {seen[tid]} y {fname}")
        else:
            seen[tid] = fname

    if errors:
        print("validate_agent_queue: ERRORES", file=sys.stderr)
        for e in errors:
            print(f"  - {e}", file=sys.stderr)
        return 1

    print(f"validate_agent_queue: OK ({len(all_ids)} tareas en {len(FILES)} colas)")
    return 0


if __name__ == "__main__":
    sys.exit(main())
