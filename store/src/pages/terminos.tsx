import { FileText } from 'lucide-react';
import { Link } from 'react-router-dom';
import { Helmet } from '@dr.pogodin/react-helmet';
import LegalLayout, { LegalSection, LegalList, LegalCallout } from '@/components/LegalLayout';

const LAST_UPDATED = '2026-06-20';
const PAGE_TITLE = 'Términos de Servicio — Gano Digital';
const PAGE_DESCRIPTION =
  'Condiciones de uso de los servicios de hosting, dominios, correo y seguridad que Gano Digital revende y acompaña. Derechos, responsabilidades y reglas claras.';
const CANONICAL = 'https://gano.digital/terminos';

export default function TerminosPage() {
  return (
    <>
      <Helmet>
        <title>{PAGE_TITLE}</title>
        <meta name="description" content={PAGE_DESCRIPTION} />
        <link rel="canonical" href={CANONICAL} />
      </Helmet>
      <h1 className="sr-only">Términos de Servicio de Gano Digital</h1>
      <LegalLayout
        slug="terminos"
        title={PAGE_TITLE}
        description={PAGE_DESCRIPTION}
        ogTag="Legal"
        eyebrow="Documentación legal"
        heading="Términos de Servicio"
        headingAs="p"
        intro="Estas condiciones regulan la relación entre Gano Digital y las personas o empresas que contratan nuestros servicios. Las escribimos para que las entiendas, no para esconder cláusulas."
        lastUpdated={LAST_UPDATED}
        icon={FileText}
      >
      <LegalSection num="01" title="Quiénes somos y qué hacemos">
        <p>
          Gano Digital es un estudio de infraestructura digital con sede en Colombia. Operamos como{' '}
          <strong className="text-foreground">revendedor autorizado</strong> de servicios de hosting,
          dominios, certificados SSL, correo profesional y herramientas de seguridad, además de prestar
          servicios propios de diseño web, desarrollo, acompañamiento estratégico y automatización con IA.
        </p>
        <p>
          Como revendedor, parte de la infraestructura técnica (servidores, registro de dominios,
          emisión de certificados) es provista y operada por nuestro proveedor mayorista. Eso significa
          que esos servicios subyacentes también están sujetos a los términos y políticas de uso de dicho
          proveedor, que aplican de forma complementaria a estos términos.
        </p>
      </LegalSection>

      <LegalSection num="02" title="Aceptación de los términos">
        <p>
          Al contratar, pagar o usar cualquiera de nuestros servicios, aceptas estos Términos de Servicio,
          nuestra{' '}
          <Link to="/reembolsos" className="underline hover:text-foreground transition-colors">
            Política de Reembolsos
          </Link>{' '}
          y nuestra{' '}
          <Link to="/privacidad" className="underline hover:text-foreground transition-colors">
            Política de Privacidad
          </Link>
          . Si contratas en nombre de una empresa, declaras tener autorización para obligarla.
        </p>
        <p>
          Podemos actualizar estos términos cuando cambien nuestros servicios o las condiciones de
          nuestro proveedor mayorista. Publicaremos siempre la fecha de última actualización; los cambios
          materiales se comunicarán por los canales de contacto que nos hayas dado.
        </p>
      </LegalSection>

      <LegalSection num="03" title="Tu cuenta y tus responsabilidades">
        <LegalList
          items={[
            'Proporcionar información veraz y mantenerla actualizada (en especial datos de contacto y facturación).',
            'Custodiar tus credenciales de acceso. Eres responsable de la actividad que ocurra bajo tu cuenta.',
            'Notificarnos de inmediato cualquier uso no autorizado o brecha de seguridad que detectes.',
            'Mantener tus propios respaldos cuando el plan contratado no incluya backups gestionados.',
            'Cumplir con la legislación colombiana y con los derechos de terceros en todo el contenido que publiques.',
          ]}
        />
      </LegalSection>

      <LegalSection num="04" title="Uso aceptable">
        <p>Está prohibido usar los servicios para:</p>
        <LegalList
          items={[
            'Distribuir malware, spam, phishing o cualquier contenido fraudulento o engañoso.',
            'Vulnerar, escanear o atacar sistemas ajenos sin autorización.',
            'Alojar material ilegal, que infrinja derechos de autor o de marca, o que sea difamatorio.',
            'Consumir recursos de forma abusiva que degrade el servicio de otros clientes en infraestructura compartida.',
            'Suplantar la identidad de personas, marcas o de Gano Digital.',
          ]}
        />
        <p>
          El incumplimiento del uso aceptable puede derivar en suspensión inmediata, conforme a la
          sección 7.
        </p>
      </LegalSection>

      <LegalSection num="05" title="Precios, facturación y renovaciones">
        <LegalList
          items={[
            'Los precios se muestran en pesos colombianos (COP) salvo que se indique lo contrario, e incluyen los impuestos aplicables según la normativa vigente.',
            'El pago de los servicios de hosting, dominios y SSL se procesa en la pasarela de checkout externa y cifrada de nuestro proveedor; Gano Digital no almacena los datos de tu tarjeta.',
            'Los servicios se prestan por períodos (mensuales o anuales) y se renuevan automáticamente al precio vigente al momento de la renovación, salvo que canceles antes de la fecha de corte.',
            'Te recomendamos revisar la fecha de renovación de cada servicio en tu panel para evitar cargos no deseados.',
          ]}
        />
        <LegalCallout>
          <p>
            <strong className="text-foreground">Dominios:</strong> el registro y la renovación de
            dominios se rigen por las políticas del registro correspondiente (ICANN y el registro de cada
            extensión). Por su naturaleza, los dominios <strong className="text-foreground">no son
            reembolsables</strong> una vez registrados. Consulta los detalles en nuestra{' '}
            <Link to="/reembolsos" className="underline hover:text-foreground transition-colors">
              Política de Reembolsos
            </Link>
            .
          </p>
        </LegalCallout>
      </LegalSection>

      <LegalSection num="06" title="Reembolsos">
        <p>
          Ofrecemos una garantía de satisfacción en los planes de hosting elegibles. El detalle de plazos,
          productos elegibles y el procedimiento para solicitar un reembolso está en nuestra{' '}
          <Link to="/reembolsos" className="underline hover:text-foreground transition-colors">
            Política de Reembolsos
          </Link>
          , que forma parte integral de estos términos.
        </p>
      </LegalSection>

      <LegalSection num="07" title="Suspensión y cancelación">
        <p>
          Podemos suspender o cancelar un servicio, con o sin previo aviso según la gravedad, cuando:
        </p>
        <LegalList
          items={[
            'Exista falta de pago tras la fecha de corte.',
            'Se incumpla el uso aceptable o se ponga en riesgo la seguridad de la infraestructura.',
            'Lo exija una orden legal o una política del proveedor mayorista.',
          ]}
        />
        <p>
          Tú puedes cancelar cualquier servicio en cualquier momento. La cancelación detiene futuras
          renovaciones; los reembolsos por el período en curso se rigen por la Política de Reembolsos.
        </p>
      </LegalSection>

      <LegalSection num="08" title="Disponibilidad del servicio">
        <p>
          Trabajamos sobre infraestructura de alta disponibilidad, pero ningún servicio en internet está
          libre al 100% de interrupciones. No garantizamos un funcionamiento ininterrumpido y no somos
          responsables por mantenimientos programados, fallas del proveedor mayorista, fuerza mayor o
          factores fuera de nuestro control razonable.
        </p>
      </LegalSection>

      <LegalSection num="09" title="Limitación de responsabilidad">
        <p>
          En la máxima medida permitida por la ley, la responsabilidad total de Gano Digital frente a
          cualquier reclamación relacionada con un servicio se limita al monto que hayas pagado por ese
          servicio en los doce (12) meses anteriores al hecho que origina la reclamación.
        </p>
        <p>
          No respondemos por daños indirectos, lucro cesante, pérdida de datos no atribuible a nuestra
          culpa, ni por contenidos o decisiones que tomes con base en nuestro acompañamiento. Esta cláusula
          no excluye los derechos irrenunciables que la ley colombiana reconoce a los consumidores.
        </p>
      </LegalSection>

      <LegalSection num="10" title="Propiedad intelectual">
        <p>
          El contenido, código y materiales que creemos para ti en proyectos de diseño o desarrollo se
          transfieren según lo pactado en cada propuesta. La marca, el sitio y los materiales propios de
          Gano Digital permanecen de nuestra propiedad. Tú conservas la titularidad del contenido que
          subes a tus servicios.
        </p>
      </LegalSection>

      <LegalSection num="11" title="Ley aplicable y resolución de disputas">
        <p>
          Estos términos se rigen por las leyes de la República de Colombia, incluyendo el Estatuto del
          Consumidor (Ley 1480 de 2011). Cualquier controversia se intentará resolver primero de buena fe
          y de forma directa; de no lograrse, se someterá a los jueces competentes de Colombia.
        </p>
        <p>
          Como consumidor, también puedes acudir a la Superintendencia de Industria y Comercio (SIC) en
          ejercicio de tus derechos.
        </p>
      </LegalSection>

      <LegalSection num="12" title="Contacto">
        <p>
          Gano Digital — Colombia. Para asuntos relacionados con estos términos, escríbenos a{' '}
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
          .
        </p>
      </LegalSection>
    </LegalLayout>
    </>
  );
}
