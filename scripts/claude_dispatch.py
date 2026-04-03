#!/usr/bin/env python3
"""
Cola local para tareas Claude: listar, mostrar, siguiente, marcar completada.

Estado en memory/claude/dispatch-state.json (gitignored) — solo progreso local de Diego.
"""
from __future__ import annotations

import argparse
import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
QUEUE_PATH = ROOT / "memory" / "claude" / "dispatch-queue.json"
STATE_PATH = ROOT / "memory" / "claude" / "dispatch-state.json"


def load_queue() -> dict:
    if not QUEUE_PATH.is_file():
        print(f"No existe {QUEUE_PATH.relative_to(ROOT)}", file=sys.stderr)
        sys.exit(1)
    return json.loads(QUEUE_PATH.read_text(encoding="utf-8"))


def load_state() -> dict:
    if not STATE_PATH.is_file():
        return {"completed": []}
    data = json.loads(STATE_PATH.read_text(encoding="utf-8"))
    if "completed" not in data or not isinstance(data["completed"], list):
        return {"completed": []}
    return data


def save_state(data: dict) -> None:
    STATE_PATH.parent.mkdir(parents=True, exist_ok=True)
    STATE_PATH.write_text(
        json.dumps(data, ensure_ascii=False, indent=2) + "\n",
        encoding="utf-8",
    )


def tasks_sorted(queue: dict) -> list[dict]:
    tasks = list(queue.get("tasks", []))
    tasks.sort(key=lambda t: (t.get("priority", 99), t.get("id", "")))
    return tasks


def cmd_list(queue: dict, state: dict) -> None:
    done = set(state.get("completed", []))
    for t in tasks_sorted(queue):
        mark = "✓" if t["id"] in done else " "
        print(f"[{mark}] p{t.get('priority', '?')}  {t['id']}: {t['title']}")


def cmd_show(queue: dict, task_id: str) -> None:
    for t in queue.get("tasks", []):
        if t["id"] == task_id:
            print(f"# {t['title']}\n")
            print(f"id: `{t['id']}`  priority: {t.get('priority')}  human_after: {t.get('requires_human_after')}\n")
            print("## Paths\n")
            for p in t.get("paths", []):
                print(f"- `{p}`")
            print("\n## Definition of done\n")
            for line in t.get("definition_of_done", []):
                print(f"- {line}")
            print("\n## Instructions (for Claude)\n")
            for para in t.get("instructions", []):
                print(para + "\n")
            return
    print(f"Tarea no encontrada: {task_id}", file=sys.stderr)
    sys.exit(1)


def cmd_next(queue: dict, state: dict) -> None:
    done = set(state.get("completed", []))
    for t in tasks_sorted(queue):
        if t["id"] not in done:
            cmd_show(queue, t["id"])
            print("---\nLuego: `python scripts/claude_dispatch.py complete " + t["id"] + "`\n")
            return
    print("Todas las tareas están marcadas completas en dispatch-state.json (o la cola está vacía).")


def cmd_complete(state: dict, task_id: str) -> None:
    done = list(state.get("completed", []))
    if task_id not in done:
        done.append(task_id)
    state["completed"] = sorted(set(done))
    save_state(state)
    print(f"Marcada completada: {task_id}")


def main() -> int:
    parser = argparse.ArgumentParser(description="Cola dispatch Claude (local)")
    sub = parser.add_subparsers(dest="cmd", required=True)

    sub.add_parser("list", help="Listar tareas y estado completado")
    p_show = sub.add_parser("show", help="Mostrar una tarea por id")
    p_show.add_argument("task_id")

    sub.add_parser("next", help="Mostrar la siguiente tarea pendiente (por prioridad)")

    p_done = sub.add_parser("complete", help="Marcar tarea completada en dispatch-state.json")
    p_done.add_argument("task_id")

    args = parser.parse_args()
    queue = load_queue()

    if args.cmd == "list":
        cmd_list(queue, load_state())
        return 0
    if args.cmd == "show":
        cmd_show(queue, args.task_id)
        return 0
    if args.cmd == "next":
        cmd_next(queue, load_state())
        return 0
    if args.cmd == "complete":
        cmd_complete(load_state(), args.task_id)
        return 0

    return 1


if __name__ == "__main__":
    sys.exit(main())
