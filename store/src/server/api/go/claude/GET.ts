import type { Request, Response } from 'express';
import { CLAUDE_REFERRAL_URL } from '../../../../data/aiCurriculum.js';

/**
 * GET /go/claude
 *
 * Redirección de referido a Claude. Mantener el destino dentro del dominio
 * (gano.digital/go/claude) permite generar un QR propio, rastrear clics y
 * cambiar el enlace de destino en un solo lugar sin tocar el QR impreso.
 */
export default function handler(_req: Request, res: Response) {
  res.redirect(302, CLAUDE_REFERRAL_URL);
}
