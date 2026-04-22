#!/usr/bin/env python3
"""
GitHub Issue Auto-Close Script
Cierra automáticamente issues que corresponden a PRs ya mergeadas.
Tareas: cd-repo-003 (cierre de issues obsoletos)

Requisitos:
- pip install PyGithub
- GITHUB_TOKEN en env var

Uso:
python github-issue-autoclose.py --repo "Gano-digital/Pilot" --auto-close
"""

import os
import sys
import argparse
from datetime import datetime, timedelta
from github import Github, GithubException

class IssueAutoCloser:
    def __init__(self, repo_name, token=None):
        """Inicializa cliente GitHub"""
        self.token = token or os.getenv('GITHUB_TOKEN')
        if not self.token:
            raise ValueError("GITHUB_TOKEN no encontrado en variables de entorno")

        self.gh = Github(self.token)
        self.repo = self.gh.get_repo(repo_name)
        self.closed_count = 0
        self.skipped_count = 0

    def find_obsolete_issues(self, days_since_update=30):
        """
        Identifica issues obsoletos:
        - No tienen PR asociado y no han sido actualizados en X días
        - Tienen PR pero PR fue cerrado/rechazado
        - Son duplicados (marcados con label 'duplicate')
        """
        obsolete = []
        cutoff_date = datetime.utcnow() - timedelta(days=days_since_update)

        print(f"🔍 Buscando issues inactivos desde {cutoff_date.date()}...")

        issues = self.repo.get_issues(state='open', sort='updated', direction='asc')

        for issue in issues[:100]:  # Limitar a 100 para no abusar de API
            # Skip PRs (son issues pero con pull_request)
            if issue.pull_request:
                continue

            last_update = issue.updated_at

            # Condición 1: Inactivo + sin PR
            if last_update < cutoff_date:
                has_linked_pr = self._check_linked_pr(issue)
                if not has_linked_pr:
                    obsolete.append({
                        'issue': issue,
                        'reason': 'inactivo (sin PR vinculado)',
                        'days_old': (datetime.utcnow() - last_update).days
                    })

            # Condición 2: Duplicate label
            if any(label.name.lower() == 'duplicate' for label in issue.labels):
                obsolete.append({
                    'issue': issue,
                    'reason': 'duplicado',
                    'days_old': (datetime.utcnow() - issue.created_at).days
                })

        return obsolete

    def _check_linked_pr(self, issue):
        """Verifica si un issue tiene PR vinculado en body o eventos"""
        # Buscar PR mencionado en body (ej: "Fixes #123" en PR)
        if issue.body:
            if 'Closes #' in issue.body or 'Fixes #' in issue.body:
                return True

        # Buscar en eventos de timeline
        try:
            for event in issue.get_timeline()[:20]:
                if hasattr(event, 'pull_request') and event.pull_request:
                    return True
        except:
            pass

        return False

    def close_issue(self, issue, reason="Cerrando issue obsoleto"):
        """Cierra un issue con comentario de razonamiento"""
        try:
            # Agregar comentario antes de cerrar
            comment_text = f"""
🤖 **Cierre automático** (cd-repo-003)

**Razón:** {reason}

Este issue ha sido identificado como obsoleto por el sistema de mantenimiento.
Si consideras que esto es un error, puedes reabrirlo.

_Cerrado automáticamente — {datetime.utcnow().strftime('%Y-%m-%d %H:%M:%S UTC')}_
            """.strip()

            issue.create_comment(comment_text)
            issue.edit(state='closed')

            self.closed_count += 1
            print(f"✅ Cerrado #{issue.number}: {issue.title[:50]}")
            return True

        except GithubException as e:
            print(f"❌ Error cerrando #{issue.number}: {e}")
            self.skipped_count += 1
            return False

    def generate_report(self, obsolete_issues):
        """Genera reporte de issues obsoletos encontrados"""
        print("\n" + "="*70)
        print("📋 REPORTE: Issues Obsoletos Encontrados")
        print("="*70)

        if not obsolete_issues:
            print("✅ No hay issues obsoletos detectados")
            return

        # Agrupar por razón
        by_reason = {}
        for item in obsolete_issues:
            reason = item['reason']
            if reason not in by_reason:
                by_reason[reason] = []
            by_reason[reason].append(item)

        # Mostrar por categoría
        for reason, items in by_reason.items():
            print(f"\n🔴 {reason.upper()} ({len(items)} issues):")
            print("-" * 70)

            for item in items:
                issue = item['issue']
                days = item['days_old']
                print(f"  #{issue.number}: {issue.title[:55]}")
                print(f"    └─ Inactivo {days} días | Autor: @{issue.user.login}")

    def run_dry_mode(self, auto_close=False, days=30):
        """Ejecuta en modo dry-run (sin cerrar)"""
        obsolete = self.find_obsolete_issues(days_since_update=days)
        self.generate_report(obsolete)

        if auto_close and obsolete:
            print("\n" + "="*70)
            print("🔄 CERRANDO ISSUES...")
            print("="*70)

            for item in obsolete:
                self.close_issue(item['issue'], f"Obsoleto: {item['reason']}")

            print(f"\n✅ Resumen: {self.closed_count} cerrados, {self.skipped_count} errores")

def main():
    parser = argparse.ArgumentParser(description='Cierre automático de issues GitHub')
    parser.add_argument('--repo', default='Gano-digital/Pilot',
                        help='Repositorio en formato owner/name')
    parser.add_argument('--auto-close', action='store_true',
                        help='Cerrar issues (sin flag: modo dry-run)')
    parser.add_argument('--days', type=int, default=30,
                        help='Días sin actividad para considerar obsoleto')

    args = parser.parse_args()

    try:
        closer = IssueAutoCloser(args.repo)
        closer.run_dry_mode(auto_close=args.auto_close, days=args.days)

    except ValueError as e:
        print(f"❌ Error: {e}")
        sys.exit(1)
    except GithubException as e:
        print(f"❌ Error GitHub: {e}")
        sys.exit(1)

if __name__ == '__main__':
    main()
