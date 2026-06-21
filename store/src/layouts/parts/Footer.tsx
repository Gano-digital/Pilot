import { Link } from 'react-router-dom';
import { MessageCircle, ExternalLink } from 'lucide-react';
import { BrandLogo } from '@/components/BrandLogo';
import { ESCAPARATE_URL } from '@/data/catalog';

const navLinks = [
  { label: 'Planes y precios', href: '/catalogo' },
  { label: 'Sala de lectura', href: '/aprende' },
  { label: 'Nuestra filosofía', href: '/filosofia' },
  { label: 'Cómo contratas', href: '/proceso' },
  { label: 'Servicios', href: '/services' },
  { label: 'Portafolio', href: '/portfolio' },
  { label: 'Nosotros', href: '/about' },
  { label: 'Contacto', href: '/contact' },
];

const productLinks = [
  { label: 'Hosting WordPress', href: '/catalogo' },
  { label: 'Diseño web', href: '/disenos' },
  { label: 'Dominios', href: '/catalogo#dominios' },
  { label: 'Correo profesional', href: '/catalogo' },
  { label: 'Seguridad y SSL', href: '/catalogo' },
  { label: 'VPS y servidores', href: '/catalogo' },
];

const WHATSAPP = 'https://wa.me/573135646123?text=Hola+Gano+Digital,+tengo+una+consulta';

export default function Footer() {
  return (
    <footer
      className="border-t"
      style={{ backgroundColor: 'hsl(var(--background))', borderColor: 'hsl(var(--border))' }}
    >
      <div className="max-w-7xl mx-auto px-6 md:px-10 py-12 md:py-16">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-6 mb-12">
          {/* Brand */}
          <div className="md:col-span-1">
            <Link to="/" className="flex items-center w-fit" aria-label="Gano Digital — inicio">
              <BrandLogo className="h-10" priority={false} />
            </Link>
            <p className="mt-4 text-sm text-muted-foreground font-body leading-relaxed">
              Infraestructura digital soberana.<br />
              Hosting, dominios y seguridad con ingeniería curada.
            </p>
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 mt-5 h-10 px-4 rounded-sm font-heading text-xs font-bold uppercase tracking-[0.1em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
            >
              <MessageCircle size={15} aria-hidden="true" /> Escríbenos
            </a>
          </div>

          {/* Productos */}
          <div className="flex flex-col gap-3">
            <p className="text-xs tracking-widest text-muted-foreground uppercase font-body mb-1">Productos</p>
            {productLinks.map((link) => (
              <Link
                key={link.label}
                to={link.href}
                className="text-sm text-foreground/60 hover:text-foreground transition-colors duration-200 font-body w-fit"
              >
                {link.label}
              </Link>
            ))}
            <a
              href={ESCAPARATE_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-1.5 text-sm text-foreground/60 hover:text-primary transition-colors duration-200 font-body w-fit"
            >
              Catálogo completo
              <ExternalLink size={13} aria-hidden="true" />
            </a>
          </div>

          {/* Navegación */}
          <div className="flex flex-col gap-3">
            <p className="text-xs tracking-widest text-muted-foreground uppercase font-body mb-1">Navegación</p>
            {navLinks.map((link) => (
              <Link
                key={link.href}
                to={link.href}
                className="text-sm text-foreground/60 hover:text-foreground transition-colors duration-200 font-body w-fit"
              >
                {link.label}
              </Link>
            ))}
          </div>

          {/* Contacto */}
          <div className="flex flex-col gap-3">
            <p className="text-xs tracking-widest text-muted-foreground uppercase font-body mb-1">Contacto</p>
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="text-sm text-foreground/60 hover:text-primary transition-colors duration-200 font-body w-fit"
            >
              WhatsApp directo
            </a>
            <Link
              to="/contact"
              className="text-sm text-foreground/60 hover:text-primary transition-colors duration-200 font-body w-fit"
            >
              Formulario de contacto
            </Link>
            <span className="text-sm text-muted-foreground font-body">Colombia · LATAM</span>
          </div>
        </div>

        {/* Bottom bar */}
        <div
          className="pt-8 flex flex-col gap-4 border-t"
          style={{ borderColor: '#2A2A2A' }}
        >
          <div className="flex flex-wrap items-center gap-x-6 gap-y-2">
            <Link
              to="/terminos"
              className="text-xs text-foreground/60 hover:text-foreground transition-colors duration-200 font-body"
            >
              Términos de Servicio
            </Link>
            <Link
              to="/reembolsos"
              className="text-xs text-foreground/60 hover:text-foreground transition-colors duration-200 font-body"
            >
              Política de Reembolsos
            </Link>
            <Link
              to="/privacidad"
              className="text-xs text-foreground/60 hover:text-foreground transition-colors duration-200 font-body"
            >
              Política de Privacidad
            </Link>
          </div>
          <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
            <p className="text-xs text-muted-foreground font-body">
              © 2026 Gano Digital · Estudio de infraestructura digital · Partner certificado
            </p>
            <p className="text-xs text-muted-foreground font-body italic">
              Hecho con intención en Colombia.
            </p>
          </div>
        </div>
      </div>
    </footer>
  );
}
