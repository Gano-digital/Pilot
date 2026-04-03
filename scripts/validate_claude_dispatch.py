#!/usr/bin/env python3
"""Valida memory/claude/dispatch-queue.json (estructura mínima para cola Claude)."""
from __future__ import annotations

import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
QUEUE_PATH = ROOT / "memory" / "claude" / "dispatch-queue.json"

REQUIRED_TASK_KEYS = frozenset(
    {"id", "title", "priority", "requires_human_after", "paths", "definition_of_done", "instructions"}
)


def main() -> int:
    errors: list[str] = []

    if not QUEUE_PATH.is_file():
        print(f"validate_claude_dispatch: falta {QUEUE_PATH.relative_to(ROOT)}", file=sys.stderr)
        return 1

    data = json.loads(QUEUE_PATH.read_text(encoding="utf-8"))
    if data.get("version") != 1:
        errors.append("raíz: version debe ser 1")
    tasks = data.get("tasks")
    if not isinstance(tasks, list) or not tasks:
        errors.append("raíz: tasks debe ser lista no vacía")
        _print_errors(errors)
        return 1

    seen: set[str] = set()
    for i, t in enumerate(tasks):
        if not isinstance(t, dict):
            errors.append(f"tasks[{i}]: no es objeto")
            continue
        missing = REQUIRED_TASK_KEYS - t.keys()
        if missing:
            errors.append(f"tasks[{i}] id={t.get('id')!r}: faltan claves {sorted(missing)}")
        tid = t.get("id")
        if not isinstance(tid, str) or not tid.strip():
            errors.append(f"tasks[{i}]: id inválido")
        elif tid in seen:
            errors.append(f"id duplicado: {tid}")
        else:
            seen.add(tid)
        for arr_key in ("paths", "definition_of_done", "instructions"):
            v = t.get(arr_key)
            if not isinstance(v, list) or not all(isinstance(x, str) for x in v):
                errors.append(f"{tid or i}: {arr_key} debe ser lista de strings")
        if not isinstance(t.get("requires_human_after"), bool):
            errors.append(f"{tid or i}: requires_human_after debe ser boolean")

    if errors:
        _print_errors(errors)
        return 1

    print(f"validate_claude_dispatch: OK ({len(tasks)} tareas)")
    return 0


def _print_errors(errors: list[str]) -> None:
    print("validate_claude_dispatch: ERRORES", file=sys.stderr)
    for e in errors:
        print(f"  - {e}", file=sys.stderr)


if __name__ == "__main__":
    sys.exit(main())
