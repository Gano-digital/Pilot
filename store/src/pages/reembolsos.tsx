import { RotateCcw } from 'lucide-react';
import { Link } from 'react-router-dom';
import { Helmet } from '@dr.pogodin/react-helmet';
import LegalLayout, { LegalSection, LegalList, LegalCallout } from '@/components/LegalLayout';

const LAST_UPDATED = '2026-06-20';
const PAGE_TITLE = 'Política de Reembolsos — Gano Digital';
const PAGE_DESCRIPTION =
  'Garantía de satisfacción de 30 días en hosting anual y 48 horas en mensual. Qué es reembolsable, qué no, y cómo solicitarlo paso a paso. Sin letra pequeña.';
const CANONICAL = 'https://gano.digital/reembolsos';

export default function ReembolsosPage() {
  return (
    <>
      <Helmet>
        <title>{PAGE_TITLE}</title>
        <meta name="description" content={PAGE_DESCRIPTION} />
        <link rel="canonical" href={CANONICAL} />
      </Helmet>
      <h1 className="sr-only">Política de Reembolsos de Gano Digital</h1>
      <LegalLayout
        slug="reembolsos"
        title={PAGE_TITLE}
        description={PAGE_DESCRIPTION}
        ogTag="Reembolsos"
        eyebrow="Documentación legal"
        heading="Política de Reembolsos"
        headingAs="p"
        intro="Queremos que contrates con confianza. Aquí explicamos exactamente qué servicios admiten reembolso, en qué plazo y cómo pedirlo — con reglas claras y sin trampas."
        lastUpdated={LAST_UPDATED}
        icon={RotateCcw}
      >
      <LegalSection num="01" title="Garantía de satisfacción en hosting">
        <p>
          Ofrecemos una garantía de reembolso en los planes de hosting elegibles, contada desde la fecha
          de la compra inicial:
        </p>
        <LegalList
          items={[
            <>
              <strong className="text-foreground">Hosting con facturación anual:</strong> 30 días
              calendario para solicitar el reembolso completo.
            </>,
            <>
              <strong className="text-foreground">Hosting con facturación mensual:</strong> 48 horas
              desde la compra para solicitar el reembolso completo.
            </>,
          ]}
        />
        <p>
          Pasados esos plazos, el servicio se considera consumido para el período en curso y no genera
          reembolso, aunque siempre puedes cancelar la renovación futura.
        </p>
      </LegalSection>

      <LegalSection num="02" title="Derecho de retracto (consumidores en Colombia)">
        <LegalCallout>
          <p>
            Conforme al Estatuto del Consumidor (Ley 1480 de 2011), si contratas como consumidor a través
            de medios no presenciales (ventas a distancia o por internet), tienes derecho de retracto
            dentro de los <strong className="text-foreground">cinco (5) días hábiles</strong> siguientes a
            la contratación, cuando aplique. Este derecho convive con la garantía comercial descrita arriba;
            siempre te reconoceremos el plazo que más te favorezca.
          </p>
          <p>
            El retracto no aplica a servicios que, por su naturaleza, comienzan a ejecutarse o se
            personalizan de inmediato con tu consentimiento expreso (por ejemplo, el registro de un
            dominio), de acuerdo con las excepciones previstas en la misma ley.
          </p>
        </LegalCallout>
      </LegalSection>

      <LegalSection num="03" title="Qué NO es reembolsable">
        <p>
          Algunos servicios, por las políticas de los registros y proveedores subyacentes, no admiten
          reembolso una vez activados:
        </p>
        <LegalList
          items={[
            <>
              <strong className="text-foreground">Dominios</strong> (registro y renovación): una vez
              registrados quedan a tu nombre ante el registro correspondiente y no son reembolsables.
            </>,
            <>
              <strong className="text-foreground">Certificados SSL</strong> ya emitidos.
            </>,
            <>
              <strong className="text-foreground">Renovaciones</strong> procesadas después de su fecha de
              corte (puedes desactivar la renovación automática para evitarlas).
            </>,
            <>
              <strong className="text-foreground">Servicios de diseño, desarrollo o consultoría</strong>{' '}
              ya iniciados o entregados, salvo lo pactado expresamente en cada propuesta.
            </>,
            'Tarifas o productos marcados explícitamente como no reembolsables en el momento de la compra.',
          ]}
        />
      </LegalSection>

      <LegalSection num="04" title="Renovaciones y cancelaciones">
        <p>
          Los servicios se renuevan automáticamente para evitar interrupciones. Si no deseas renovar:
        </p>
        <LegalList
          items={[
            'Desactiva la renovación automática o solicítanos la cancelación antes de la fecha de corte.',
            'Una cancelación detiene cobros futuros, pero no genera reembolso del período ya facturado salvo que estés dentro de la ventana de garantía.',
            'Si una renovación se cobró por error técnico atribuible a nosotros, la corregimos y reembolsamos sin demora.',
          ]}
        />
      </LegalSection>

      <LegalSection num="05" title="Cómo solicitar un reembolso">
        <p>El proceso es directo y lo gestionamos contigo:</p>
        <LegalList
          items={[
            <>
              Escríbenos a{' '}
              <a
                href="mailto:pymes@gano.digital?subject=Solicitud%20de%20reembolso"
                className="underline hover:text-foreground transition-colors"
              >
                pymes@gano.digital
              </a>{' '}
              o por{' '}
              <a
                href="https://wa.me/573135646123?text=Quiero+solicitar+un+reembolso"
                target="_blank"
                rel="noopener noreferrer"
                className="underline hover:text-foreground transition-colors"
              >
                WhatsApp
              </a>{' '}
              indicando el servicio y la fecha de compra.
            </>,
            'Verificamos que esté dentro de la ventana de garantía o del derecho de retracto.',
            'Confirmamos la procedencia y tramitamos la devolución por el mismo medio de pago original.',
            'El tiempo de acreditación depende de tu banco o pasarela; normalmente entre 5 y 15 días hábiles.',
          ]}
        />
      </LegalSection>

      <LegalSection num="06" title="Relación con nuestro proveedor mayorista">
        <p>
          Como revendedor autorizado, algunos reembolsos dependen de las políticas del proveedor que opera
          la infraestructura subyacente. Cuando un servicio esté sujeto a esas condiciones, te lo
          informaremos con transparencia y gestionaremos la solicitud en tu nombre.
        </p>
      </LegalSection>

      <LegalSection num="07" title="Relación con los Términos de Servicio">
        <p>
          Esta política forma parte integral de nuestros{' '}
          <Link to="/terminos" className="underline hover:text-foreground transition-colors">
            Términos de Servicio
          </Link>{' '}
          y se interpreta conjuntamente con ellos. En caso de duda, prevalece siempre la lectura más
          favorable para ti como consumidor, según la ley colombiana.
        </p>
      </LegalSection>
    </LegalLayout>
    </>
  );
}
