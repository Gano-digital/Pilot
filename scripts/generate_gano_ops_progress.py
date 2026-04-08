#!/usr/bin/env python3
"""
Genera tools/gano-ops-hub/public/data/progress.json desde TASKS.md y la cola Claude.

Opcional: métricas del GitHub Project @Gano.digital vía GraphQL si existe token
(Workflow 14 pasa ADD_TO_PROJECT_PAT como GITHUB_PROJECT_TOKEN).

Uso:
  python scripts/generate_gano_ops_progress.py
  python scripts/generate_gano_ops_progress.py --stdout  # solo imprime
"""
from __future__ import annotations

import argparse
import json
import os
import re
import sys
import urllib.error
import urllib.request
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

ROOT = Path(__file__).resolve().parents[1]
TASKS_PATH = ROOT / "TASKS.md"
QUEUE_PATH = ROOT / "memory" / "claude" / "dispatch-queue.json"
STATE_PATH = ROOT / "memory" / "claude" / "dispatch-state.json"
OUT_PATH = ROOT / "tools" / "gano-ops-hub" / "public" / "data" / "progress.json"
PROJECT_HUB_CONFIG = ROOT / ".github" / "gano-project-hub.json"

GRAPHQL_URL = "https://api.github.com/graphql"

PROJECT_ITEMS_QUERY = """
query($org: String!, $pn: Int!, $cursor: String) {
  organization(login: $org) {
    projectV2(number: $pn) {
      title
      url
      items(first: 100, after: $cursor) {
        pageInfo { hasNextPage endCursor }
        nodes {
          content {
            __typename
            ... on Issue {
              number
              title
              url
              state
            }
          }
          fieldValues(first: 30) {
            nodes {
              ... on ProjectV2ItemFieldSingleSelectValue {
                name
                field {
                  ... on ProjectV2SingleSelectField {
                    name
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
"""

SECTION_RE = re.compile(r"^##\s+(.+)$")
ITEM_RE = re.compile(r"^\s*-\s+\[([ xX])\]")


def load_project_hub_config() -> dict[str, Any] | None:
    if not PROJECT_HUB_CONFIG.is_file():
        return None
    return json.loads(PROJECT_HUB_CONFIG.read_text(encoding="utf-8"))


def _item_status_name(item: dict[str, Any]) -> str | None:
    for fv in item.get("fieldValues", {}).get("nodes") or []:
        field = fv.get("field") or {}
        if field.get("name") == "Status":
            return fv.get("name")
    return None


def _norm_status(s: str | None) -> str:
    if not s:
        return "unknown"
    key = s.strip().lower()
    if key in ("done",):
        return "done"
    if key in ("in progress", "in_progress", "in-progress"):
        return "in_progress"
    if key in ("todo", "to do", "to-do"):
        return "todo"
    if key in ("blocked",):
        return "blocked"
    return "other"


def fetch_github_project_stats(org: str, project_number: int, token: str) -> dict[str, Any]:
    """Llama a la API GraphQL de Projects v2; devuelve stats o dict con error."""
    headers = {
        "Authorization": f"Bearer {token}",
        "Content-Type": "application/json",
        "User-Agent": "gano-digital-ops-hub-generate",
    }
    counts: dict[str, int] = {
        "done": 0,
        "in_progress": 0,
        "todo": 0,
        "blocked": 0,
        "other": 0,
        "unknown": 0,
    }
    in_progress_preview: list[dict[str, Any]] = []
    cursor: str | None = None
    project_title: str | None = None
    project_url: str | None = None
    total_items = 0

    while True:
        body = json.dumps(
            {
                "query": PROJECT_ITEMS_QUERY,
                "variables": {"org": org, "pn": project_number, "cursor": cursor},
            }
        ).encode("utf-8")
        req = urllib.request.Request(GRAPHQL_URL, data=body, headers=headers, method="POST")
        try:
            with urllib.request.urlopen(req, timeout=60) as resp:
                raw = json.loads(resp.read().decode("utf-8"))
        except urllib.error.HTTPError as e:
            return {"ok": False, "error": f"HTTP {e.code}", "hint": "Token con scope Projects (org) + lectura."}
        except urllib.error.URLError as e:
            return {"ok": False, "error": str(e.reason), "hint": "Red o DNS."}

        if raw.get("errors"):
            msg = "; ".join(str(e.get("message", e)) for e in raw["errors"])
            return {"ok": False, "error": msg, "hint": "Revisa org, project_number y permisos del PAT."}

        org_data = (raw.get("data") or {}).get("organization")
        if not org_data:
            return {"ok": False, "error": "organization not found", "hint": f"¿org '{org}' correcta?"}

        proj = org_data.get("projectV2")
        if not proj:
            return {
                "ok": False,
                "error": "projectV2 not found",
                "hint": f"¿existe el proyecto #{project_number} en {org}?",
            }

        project_title = proj.get("title")
        project_url = proj.get("url")
        conn = proj.get("items") or {}
        nodes = conn.get("nodes") or []
        page = conn.get("pageInfo") or {}

        for item in nodes:
            total_items += 1
            st = _norm_status(_item_status_name(item))
            if st in counts:
                counts[st] += 1
            else:
                counts["other"] += 1

            content = item.get("content")
            if (
                st == "in_progress"
                and content
                and content.get("__typename") == "Issue"
                and len(in_progress_preview) < 10
            ):
                in_progress_preview.append(
                    {
                        "number": content.get("number"),
                        "title": content.get("title"),
                        "url": content.get("url"),
                    }
                )

        if page.get("hasNextPage") and page.get("endCursor"):
            cursor = page["endCursor"]
            continue
        break

    pct = 0.0
    if total_items > 0:
        pct = round(100.0 * counts["done"] / total_items, 1)

    return {
        "ok": True,
        "project_title": project_title,
        "project_url": project_url,
        "total_items": total_items,
        "by_status": counts,
        "done_pct": pct,
        "in_progress_preview": in_progress_preview,
        "fetched_at": datetime.now(timezone.utc).isoformat(),
    }


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

    hub_cfg = load_project_hub_config()
    github_project: dict[str, Any] = {
        "config_present": hub_cfg is not None,
        "config": hub_cfg,
        "live_stats": None,
        "live_stats_note": None,
    }

    token = (os.environ.get("GITHUB_PROJECT_TOKEN") or os.environ.get("ADD_TO_PROJECT_PAT") or "").strip()
    if hub_cfg and token:
        org = hub_cfg.get("org") or "Gano-digital"
        try:
            pn = int(hub_cfg.get("project_number", 2))
        except (TypeError, ValueError):
            pn = 2
        stats = fetch_github_project_stats(org, pn, token)
        if stats.get("ok"):
            github_project["live_stats"] = stats
        else:
            github_project["live_stats_note"] = stats.get("error", "unknown")
            github_project["live_stats_hint"] = stats.get("hint")
    elif hub_cfg and not token:
        github_project["live_stats_note"] = (
            "Sin token en el entorno (GITHUB_PROJECT_TOKEN o ADD_TO_PROJECT_PAT): "
            "no se consultó la API del Project. En Actions, el workflow 14 inyecta ADD_TO_PROJECT_PAT; "
            "en local: exporta GITHUB_PROJECT_TOKEN con un PAT de lectura Projects (org)."
        )

    return {
        "schema_version": 2,
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "repository": "Gano-digital/Pilot",
        "tasks_md": tasks_block,
        "tasks_completion_pct": pct,
        "dispatch": load_dispatch(),
        "github_project": github_project,
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
