import { Fragment } from 'react';
import { GLOSSARY } from '@/data/glossary';
import GlossaryTerm from './GlossaryTerm';

/**
 * Recorre un texto y envuelve la primera aparición de cada término del
 * glosario en <GlossaryTerm>, dejando el resto del texto intacto.
 *
 * Construye una sola expresión regular con todos los términos canónicos,
 * priorizando los más largos para que "SSL Wildcard" gane sobre "SSL".
 */

// Lista de términos a buscar (nombre canónico), ordenados por longitud desc.
const TERMS: { canonical: string; key: string }[] = Object.entries(GLOSSARY)
  .map(([key, entry]) => ({ canonical: entry.term, key }))
  .sort((a, b) => b.canonical.length - a.canonical.length);

function escapeRegExp(s: string): string {
  return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

const PATTERN = new RegExp(
  `\\b(${TERMS.map((t) => escapeRegExp(t.canonical)).join('|')})\\b`,
  'gi',
);

export default function LinkedText({ text }: { text: string }) {
  const seen = new Set<string>();
  const parts: React.ReactNode[] = [];
  let lastIndex = 0;
  let match: RegExpExecArray | null;

  // Reiniciar el lastIndex de la regex global entre renders.
  PATTERN.lastIndex = 0;

  while ((match = PATTERN.exec(text)) !== null) {
    const matched = match[0];
    const lower = matched.toLowerCase();
    const entry = TERMS.find((t) => t.canonical.toLowerCase() === lower);

    // Solo enlazar la primera aparición de cada término.
    if (entry && !seen.has(lower)) {
      seen.add(lower);
      if (match.index > lastIndex) {
        parts.push(<Fragment key={`t-${lastIndex}`}>{text.slice(lastIndex, match.index)}</Fragment>);
      }
      parts.push(
        <GlossaryTerm key={`g-${match.index}`} termKey={entry.key}>
          {matched}
        </GlossaryTerm>,
      );
      lastIndex = match.index + matched.length;
    }
  }

  if (lastIndex < text.length) {
    parts.push(<Fragment key={`t-end`}>{text.slice(lastIndex)}</Fragment>);
  }

  return <>{parts}</>;
}
