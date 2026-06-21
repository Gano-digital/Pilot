/**
 * Router Hooks — hook exports only
 *
 * Separated from router.ts to satisfy Vite Fast Refresh rules:
 * a module must export only hooks OR only non-hooks, not both.
 */

import { useNavigate as useRouterNavigate, useParams as useRouterParams } from 'react-router-dom';
import { Path, Params } from './routes';

// Export hooks with type safety
export const useNavigate = () => {
  const navigate = useRouterNavigate();
  return (to: Path | number, options?: { replace?: boolean; state?: any }) => {
    if (typeof to === 'number') {
      navigate(to);
    } else {
      navigate(to, options);
    }
  };
};

export const useParams = useRouterParams<Params>;
