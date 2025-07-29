import React from 'react';
import { DownloadIcon, TerminalIcon, PythonIcon } from './icons';

const UsageGuide: React.FC = () => {
  const codeBlockClass = "bg-slate-900/70 text-sky-300 font-mono text-xs px-2 py-1 rounded";

  return (
    <div className="bg-slate-800/80 backdrop-blur-sm border border-slate-700/50 rounded-xl shadow-lg p-5 animate-fade-in">
      <h3 className="text-lg font-bold text-sky-400 mb-4 flex items-center">
        <PythonIcon className="h-6 w-6 mr-2" />
        How to Use This Script
      </h3>
      <ol className="space-y-4 text-slate-300 text-sm">
        
        <li className="flex items-start gap-4">
          <div className="flex-shrink-0 bg-sky-500/10 text-sky-400 rounded-full h-8 w-8 flex items-center justify-center font-bold ring-2 ring-sky-500/30">1</div>
          <div>
            <strong className="font-semibold text-slate-100">Save the Script</strong>
            <p>Click the <strong className="text-slate-100">Copy</strong> button above and save the code into a file named <code className={codeBlockClass}>site_cloner.py</code> on your computer.</p>
          </div>
        </li>

        <li className="flex items-start gap-4">
          <div className="flex-shrink-0 bg-sky-500/10 text-sky-400 rounded-full h-8 w-8 flex items-center justify-center font-bold ring-2 ring-sky-500/30">2</div>
          <div>
            <strong className="font-semibold text-slate-100">Install Dependencies</strong>
            <p>Open your terminal or command prompt and run this command to install the required libraries:</p>
            <pre className="bg-slate-900 rounded-md p-2 mt-2 overflow-x-auto">
              <code className="text-sm font-mono">pip install requests beautifulsoup4</code>
            </pre>
          </div>
        </li>

        <li className="flex items-start gap-4">
          <div className="flex-shrink-0 bg-sky-500/10 text-sky-400 rounded-full h-8 w-8 flex items-center justify-center font-bold ring-2 ring-sky-500/30">3</div>
          <div>
            <strong className="font-semibold text-slate-100">Run from Terminal</strong>
            <p>Navigate to the folder where you saved the file and run the script with a target URL:</p>
            <pre className="bg-slate-900 rounded-md p-2 mt-2 overflow-x-auto">
              <code className="text-sm font-mono">python site_cloner.py --url https://example.com</code>
            </pre>
          </div>
        </li>

      </ol>
      <style>{`
        @keyframes fade-in {
          from { opacity: 0; transform: translateY(10px); }
          to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
          animation: fade-in 0.5s ease-out forwards;
        }
      `}</style>
    </div>
  );
};

export default UsageGuide;
