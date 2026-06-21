import { ShieldCheck } from 'lucide-react';
import { Link } from 'react-router-dom';
import { Helmet } from '@dr.pogodin/react-helmet';
import LegalLayout, { LegalSection, LegalList, LegalCallout } from '@/components/LegalLayout';

const LAST_UPDATED = '2026-06-20';
const PAGE_TITLE = 'Política de Privacidad — Gano Digital';
const PAGE_DESCRIPTION =
  'Cómo Gano Digital recoge, usa y protege tus datos personales conforme a la Ley 1581 de 2012 de Colombia. Tus derechos como titular y cómo ejercerlos.';
const CANONICAL = 'https://gano.digital/privacidad';

export default function PrivacidadPage() {
  return (
    <>
      <Helmet>
        <title>{PAGE_TITLE}</title>
        <meta name="description" content={PAGE_DESCRIPTION} />
        <link rel="canonical" href={CANONICAL} />
      </Helmet>
      <h1 className="sr-only">Política de Privacidad de Gano Digital</h1>
      <LegalLayout
        slug="privacidad"
        title={PAGE_TITLE}
        description={PAGE_DESCRIPTION}
        ogTag="Privacidad"
        eyebrow="Documentación legal"
        heading="Política de Privacidad"
        headingAs="p"
        intro="Tratamos tus datos con el mismo cuidado con el que protegemos la infraestructura de nuestros clientes. Aquí explicamos qué recogemos, para qué, y qué control tienes sobre ello."
        lastUpdated={LAST_UPDATED}
        icon={ShieldCheck}
      >
      <LegalSection num="01" title="Responsable del tratamiento">
        <p>
          Gano Digital, con sede en Colombia, es responsable del tratamiento de los datos personales que
          nos compartes. Esta política se rige por la Ley Estatutaria 1581 de 2012 y sus decretos
          reglamentarios sobre protección de datos personales.
        </p>
      </LegalSection>

      <LegalSection num="02" title="Qué datos recogemos">
        <LegalList
          items={[
            <>
              <strong className="text-foreground">Datos de contacto:</strong> nombre, empresa, correo y
              número de WhatsApp que ingresas en nuestros formularios de contacto y de diagnóstico.
            </>,
            <>
              <strong className="text-foreground">Datos del diagnóstico operativo:</strong> las respuestas
              que das en nuestra herramienta de diagnóstico, para poder darte recomendaciones.
            </>,
            <>
              <strong className="text-foreground">Datos de facturación:</strong> los necesarios para
              procesar una contratación. El pago se realiza en una pasarela externa cifrada; no almacenamos
              datos de tu tarjeta.
            </>,
            <>
              <strong className="text-foreground">Datos técnicos:</strong> información básica de navegación
              y, si aceptas, cookies de medición. Puedes gestionar tu consentimiento en cualquier momento.
            </>,
          ]}
        />
      </LegalSection>

      <LegalSection num="03" title="Para qué los usamos">
        <LegalList
          items={[
            'Responder tus consultas y darte seguimiento comercial.',
            'Generar y enviarte el resultado de tu diagnóstico operativo.',
            'Prestar, activar y renovar los servicios que contrates.',
            'Cumplir obligaciones legales, contables y de seguridad.',
            'Mejorar nuestro sitio y servicios (solo con datos agregados o con tu consentimiento).',
          ]}
        />
        <p>No vendemos tus datos personales a terceros. Nunca.</p>
      </LegalSection>

      <LegalSection num="04" title="Con quién los compartimos">
        <p>Solo compartimos datos cuando es necesario para prestarte el servicio:</p>
        <LegalList
          items={[
            'Nuestro proveedor mayorista de infraestructura, para activar hosting, dominios, SSL y correo.',
            'La pasarela de pago, para procesar tus transacciones de forma segura.',
            'Proveedores de envío de correo transaccional, para hacerte llegar confirmaciones y respuestas.',
            'Autoridades competentes, cuando exista una obligación legal de hacerlo.',
          ]}
        />
      </LegalSection>

      <LegalSection num="05" title="Cookies y medición">
        <p>
          Usamos cookies técnicas necesarias para que el sitio funcione y, opcionalmente, cookies de
          analítica para entender cómo se usa el sitio. Las cookies de medición solo se activan si das tu
          consentimiento, que puedes cambiar cuando quieras desde el aviso de cookies.
        </p>
      </LegalSection>

      <LegalSection num="06" title="Tus derechos como titular">
        <p>Conforme a la ley colombiana, en cualquier momento puedes:</p>
        <LegalList
          items={[
            'Conocer, actualizar y rectificar tus datos personales.',
            'Solicitar prueba de la autorización que nos otorgaste.',
            'Ser informado del uso que damos a tus datos.',
            'Revocar la autorización y/o solicitar la supresión de tus datos, cuando no exista un deber legal de conservarlos.',
            'Presentar quejas ante la Superintendencia de Industria y Comercio (SIC).',
          ]}
        />
        <LegalCallout>
          <p>
            Para ejercer cualquiera de estos derechos, escríbenos a{' '}
            <a
              href="mailto:pymes@gano.digital?subject=Solicitud%20de%20datos%20personales"
              className="underline hover:text-foreground transition-colors"
            >
              pymes@gano.digital
            </a>
            . Atenderemos tu solicitud en los plazos que establece la ley.
          </p>
        </LegalCallout>
      </LegalSection>

      <LegalSection num="07" title="Seguridad y conservación">
        <p>
          Aplicamos medidas técnicas y organizativas razonables para proteger tus datos contra acceso no
          autorizado, pérdida o alteración. Conservamos los datos solo durante el tiempo necesario para
          las finalidades descritas o mientras exista una obligación legal de conservarlos.
        </p>
      </LegalSection>

      <LegalSection num="08" title="Cambios y contacto">
        <p>
          Podemos actualizar esta política para reflejar cambios legales o de nuestros servicios;
          publicaremos siempre la fecha de última actualización. Para cualquier asunto de privacidad,
          escríbenos a{' '}
          <a
            href="mailto:pymes@gano.digital"
            className="underline hover:text-foreground transition-colors"
          >
            pymes@gano.digital
          </a>{' '}
          o por nuestros canales de{' '}
          <Link to="/contact" className="underline hover:text-foreground transition-colors">
            contacto
          </Link>
          . Esta política se complementa con nuestros{' '}
          <Link to="/terminos" className="underline hover:text-foreground transition-colors">
            Términos de Servicio
          </Link>
          .
        </p>
      </LegalSection>
    </LegalLayout>
    </>
  );
}
