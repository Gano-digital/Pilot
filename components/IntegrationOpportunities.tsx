import React, { useState } from 'react';
import { CloseIcon, PuzzleIcon, GlobeIcon, CloudUploadIcon, KeyIcon, SparklesIcon, TerminalIcon } from './icons';

interface IntegrationOpportunitiesProps {
  onClose: () => void;
}

interface Opportunity {
  id: number;
  title: string;
  description: string;
  details: string[];
  complexity: 'Low' | 'Medium' | 'High';
  icon: React.ReactNode;
}

const OPPORTUNITIES: Opportunity[] = [
  {
    id: 1,
    title: 'Domain Portfolio Dashboard',
    description: 'A React UI that connects to the GoDaddy Reseller API to display and manage all registered domains in one place.',
    details: [
      'List all domains with status, expiry date, and auto-renewal flag',
      'Filter and sort by TLD, status, or expiry date',
      'Toggle auto-renewal from the dashboard',
      'Export the portfolio to CSV for reporting',
    ],
    complexity: 'Medium',
    icon: <GlobeIcon className="h-5 w-5" />,
  },
  {
    id: 2,
    title: 'Automated DNS Provisioning on Deploy',
    description: 'Extend the existing CI/CD pipeline to automatically configure DNS records every time a new environment is deployed.',
    details: [
      'Create or update A/CNAME records pointing to the deployed server after each push',
      'Environment profiles: production vs. staging DNS targets',
      'DNS rollback if the post-deploy health check fails',
      'Subdomain creation per feature branch (e.g., feature-auth.gano.digital)',
    ],
    complexity: 'Low',
    icon: <CloudUploadIcon className="h-5 w-5" />,
  },
  {
    id: 3,
    title: 'Blue-Green Deployments via DNS',
    description: 'Use DNS-based traffic routing to switch between two identical production environments with zero downtime.',
    details: [
      'Maintain two server instances; flip the A record to switch traffic',
      'Gradual rollout by routing a percentage of traffic to the new version',
      'Instant rollback by re-pointing the A record to the previous server',
    ],
    complexity: 'Medium',
    icon: <CloudUploadIcon className="h-5 w-5" />,
  },
  {
    id: 4,
    title: 'Domain Availability Checker & Registration',
    description: 'Extend the existing check-domain workflow into a full interactive search and purchase tool.',
    details: [
      'Real-time bulk availability check across multiple TLDs (.com, .digital, .mx, etc.)',
      'Price comparison across TLDs',
      'TLD suggestion engine: given a keyword, propose available domain variants',
      'One-click registration of an available domain from the UI',
    ],
    complexity: 'Medium',
    icon: <GlobeIcon className="h-5 w-5" />,
  },
  {
    id: 5,
    title: 'Email Infrastructure Automation',
    description: 'Auto-provision the DNS records required to operate professional email (MX, SPF, DKIM, DMARC) whenever a new domain or environment is set up.',
    details: [
      'Templates for popular email providers: Google Workspace, Zoho Mail, Microsoft 365',
      'Apply a provider template to a domain in a single workflow dispatch',
      'Verify all required records are live after provisioning',
      'SPF/DMARC report parsing and alerting',
    ],
    complexity: 'Low',
    icon: <KeyIcon className="h-5 w-5" />,
  },
  {
    id: 6,
    title: 'Multi-Tenant Client Domain Portal',
    description: 'A white-label portal where Gano Digital clients can manage their own domains without accessing GoDaddy directly.',
    details: [
      'Client sign-up flow that registers a domain on their behalf via the Reseller API',
      'Per-client DNS management panel (add/edit/delete records)',
      'Automated invoicing integration triggered on domain registration or renewal',
      'Domain transfer workflow: accept transfer codes and track progress',
    ],
    complexity: 'High',
    icon: <PuzzleIcon className="h-5 w-5" />,
  },
  {
    id: 7,
    title: 'Domain Expiry Monitoring & Alerts',
    description: 'A scheduled workflow that monitors all domains for upcoming expiry and sends notifications.',
    details: [
      'Daily cron job that flags domains expiring within 30/7/1 days',
      'Slack/email alerts with direct renewal links',
      'Auto-renew domains below a configurable expiry threshold',
      'Dashboard widget showing upcoming renewals in priority order',
    ],
    complexity: 'Low',
    icon: <SparklesIcon className="h-5 w-5" />,
  },
  {
    id: 8,
    title: 'DNS Change Auditing & Security Monitoring',
    description: 'Track every DNS record modification to detect unauthorized changes or misconfigurations.',
    details: [
      'Snapshot DNS state to a file or database after each successful deploy',
      'Diff snapshots to detect unexpected record changes between runs',
      'Alert on changes not triggered by the CI/CD pipeline',
      'DNSSEC status checking',
    ],
    complexity: 'Low',
    icon: <KeyIcon className="h-5 w-5" />,
  },
  {
    id: 9,
    title: 'Python Script Generator Extension',
    description: 'Extend the existing Gemini AI script generator to produce domain-management automation scripts powered by the GoDaddy API.',
    details: [
      'New prompt: "Generate a Python script that checks domain availability and registers the cheapest available TLD"',
      'New prompt: "Generate a Python script that syncs DNS records from a YAML config file to GoDaddy"',
      'New prompt: "Generate a Python script that audits all domains for expiry and emails a report"',
      'Generated scripts use the same credential pattern already established in godaddyService.ts',
    ],
    complexity: 'Low',
    icon: <TerminalIcon className="h-5 w-5" />,
  },
  {
    id: 10,
    title: 'WordPress / GoDaddy Hosting Integration',
    description: 'Combine the existing WordPress hosting setup with the Reseller API for fully automated site provisioning.',
    details: [
      'When a new WP multisite sub-site is created, auto-create the corresponding subdomain DNS record',
      'Auto-configure DNS for WP plugins that require custom records (SendGrid, Mailchimp)',
      'Provision a new GoDaddy sub-account + domain for each client website',
      'Post-migration DNS cutover script that updates A + MX records atomically',
    ],
    complexity: 'High',
    icon: <PuzzleIcon className="h-5 w-5" />,
  },
];

const complexityColors: Record<Opportunity['complexity'], string> = {
  Low: 'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/30',
  Medium: 'bg-amber-500/10 text-amber-400 ring-1 ring-amber-500/30',
  High: 'bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/30',
};

const IntegrationOpportunities: React.FC<IntegrationOpportunitiesProps> = ({ onClose }) => {
  const [expandedId, setExpandedId] = useState<number | null>(null);

  const toggleExpanded = (id: number) => {
    setExpandedId(prev => (prev === id ? null : id));
  };

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm animate-modal-fade-in"
      onClick={onClose}
      role="dialog"
      aria-modal="true"
      aria-labelledby="integration-opportunities-title"
    >
      <div
        className="bg-slate-800 border border-slate-700/50 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col animate-modal-slide-in"
        onClick={e => e.stopPropagation()}
      >
        <header className="flex items-center justify-between p-4 border-b border-slate-700 flex-shrink-0">
          <h2 id="integration-opportunities-title" className="text-lg font-bold text-white flex items-center gap-3">
            <PuzzleIcon className="h-6 w-6 text-sky-400" />
            Reseller API — Integration Opportunities
          </h2>
          <button
            onClick={onClose}
            className="p-1 rounded-full text-slate-400 hover:bg-slate-700 hover:text-white transition-colors"
            aria-label="Close integration opportunities"
          >
            <CloseIcon className="h-6 w-6" />
          </button>
        </header>

        <main className="p-4 overflow-y-auto space-y-2">
          <p className="text-sm text-slate-300 mb-4">
            The following features and automations can be built directly on top of the GoDaddy Reseller API integration already in this repository. Click any item to expand its details.
          </p>

          {OPPORTUNITIES.map(op => (
            <div
              key={op.id}
              className="border border-slate-700/50 rounded-lg overflow-hidden"
            >
              <button
                onClick={() => toggleExpanded(op.id)}
                className="w-full flex items-center gap-3 p-3 text-left hover:bg-slate-700/40 transition-colors"
                aria-expanded={expandedId === op.id}
              >
                <span className="flex-shrink-0 bg-sky-500/10 text-sky-400 rounded-full h-7 w-7 flex items-center justify-center text-xs font-bold ring-1 ring-sky-500/30">
                  {op.id}
                </span>
                <span className="flex-grow font-semibold text-slate-100 text-sm">{op.title}</span>
                <span className={`flex-shrink-0 text-xs font-medium px-2 py-0.5 rounded-full ${complexityColors[op.complexity]}`}>
                  {op.complexity}
                </span>
                <span className={`flex-shrink-0 text-slate-400 transition-transform duration-200 ${expandedId === op.id ? 'rotate-180' : ''}`}>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-4 w-4" aria-hidden="true">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                  </svg>
                </span>
              </button>

              {expandedId === op.id && (
                <div className="px-4 pb-4 bg-slate-900/30 border-t border-slate-700/50">
                  <p className="text-slate-300 text-sm mt-3 mb-3">{op.description}</p>
                  <ul className="space-y-1.5">
                    {op.details.map((detail, i) => (
                      <li key={i} className="flex items-start gap-2 text-slate-400 text-sm">
                        <span className="text-sky-500 mt-0.5 flex-shrink-0">›</span>
                        {detail}
                      </li>
                    ))}
                  </ul>
                </div>
              )}
            </div>
          ))}
        </main>

        <footer className="p-4 border-t border-slate-700 bg-slate-800/50 flex-shrink-0">
          <p className="text-center text-xs text-slate-400">
            Full details in{' '}
            <code className="bg-slate-700 px-1 py-0.5 rounded text-sky-300">docs/reseller-api-integrations.md</code>
            {' '}· Items rated <span className="text-emerald-400">Low</span> complexity are recommended as the next step.
          </p>
        </footer>
      </div>
    </div>
  );
};

export default IntegrationOpportunities;
