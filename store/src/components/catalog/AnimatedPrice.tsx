import { useCountUp } from '@/hooks/useCountUp';
import { formatPrice, billingLabel } from '@/data/catalog';

interface AnimatedPriceProps {
  value: number;
  currency: string;
  billingPeriod: string;
  /** Prefijo opcional, ej. "Desde". */
  prefix?: string;
  className?: string;
}

/**
 * Muestra un precio que cuenta de 0 al valor real cuando entra en viewport.
 * El número se redondea al peso entero mientras anima para evitar decimales.
 */
export default function AnimatedPrice({
  value,
  currency,
  billingPeriod,
  prefix,
  className = '',
}: AnimatedPriceProps) {
  const [ref, current] = useCountUp({ target: value, duration: 1100 });

  return (
    <div ref={ref as React.RefObject<HTMLDivElement>} className={`flex items-baseline gap-1.5 ${className}`}>
      {prefix && (
        <span className="font-body text-xs uppercase tracking-wider text-muted-foreground mr-1">
          {prefix}
        </span>
      )}
      <span className="font-heading text-3xl font-bold text-primary tabular-nums">
        {formatPrice(Math.round(current), currency)}
      </span>
      <span className="font-body text-sm text-muted-foreground">
        {billingLabel(billingPeriod)}
      </span>
    </div>
  );
}
