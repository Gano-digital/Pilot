import React from 'react';
import { CloseIcon, CloudUploadIcon, GithubIcon, IbmCloudIcon, KeyIcon } from './icons';

interface DeploymentGuideProps {
  onClose: () => void;
}

const DeploymentGuide: React.FC<DeploymentGuideProps> = ({ onClose }) => {
  const codeBlockClass = "bg-violet-950/60 text-violet-300 font-mono text-xs px-2 py-1 rounded-sm";
  const preBlockClass = "bg-[#0d0b1a] border border-violet-900/30 rounded-md p-3 mt-2 overflow-x-auto text-violet-200 text-sm font-mono";

  const Step: React.FC<{ number: number; title: string; icon: React.ReactNode; children: React.ReactNode }> = ({ number, title, icon, children }) => (
    <li className="flex items-start gap-4">
      <div className="flex-shrink-0 bg-violet-500/10 text-violet-300 rounded-full h-8 w-8 flex items-center justify-center font-bold ring-2 ring-violet-500/30">{number}</div>
      <div className="flex-grow">
        <strong className="font-semibold text-slate-100 flex items-center gap-2">
          {icon}
          {title}
        </strong>
        <div className="text-slate-400 text-sm mt-1 space-y-2">{children}</div>
      </div>
    </li>
  );

  return (
    <div 
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm animate-modal-fade-in"
      onClick={onClose}
      role="dialog"
      aria-modal="true"
      aria-labelledby="deploy-guide-title"
    >
      <div
        className="bg-indigo-950 border border-violet-800/40 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col animate-modal-slide-in"
        onClick={e => e.stopPropagation()}
      >
        <header className="flex items-center justify-between p-4 border-b border-violet-800/40 flex-shrink-0">
          <h2 id="deploy-guide-title" className="text-lg font-bold text-white flex items-center gap-3">
            <CloudUploadIcon className="h-6 w-6 text-violet-400" />
            Deploy to IBM Cloud Code Engine
          </h2>
          <button
            onClick={onClose}
            className="p-1 rounded-full text-violet-300/70 hover:bg-violet-800/50 hover:text-white transition-colors"
            aria-label="Close deployment guide"
          >
            <CloseIcon className="h-6 w-6" />
          </button>
        </header>
        
        <main className="p-6 overflow-y-auto">
          <p className="text-sm text-slate-300 mb-6">
            Follow these steps to deploy your own instance of this application. Code Engine makes it easy to run apps from source code without managing servers.
          </p>
          <ol className="space-y-6">
            <Step number={1} title="Push Code to GitHub" icon={<GithubIcon className="h-5 w-5" />}>
              <p>Code Engine builds directly from your Git repository. Get the application source code into your own public or private GitHub repository.</p>
            </Step>

            <Step number={2} title="Create a Code Engine Project" icon={<IbmCloudIcon className="h-5 w-5" />}>
              <p>In your IBM Cloud account, search for <strong className="text-slate-200">Code Engine</strong> and create a new project. This is a logical grouping for your applications.</p>
            </Step>

            <Step number={3} title="Create the Application" icon={<CloudUploadIcon className="h-5 w-5" />}>
              <p>Inside your project, click <strong className="text-slate-200">Create application</strong>. Select <strong className="text-slate-200">Source code</strong> and point it to your GitHub repository URL.</p>
              <p>Code Engine will automatically detect the build strategy using buildpacks, so you don't need a Dockerfile.</p>
            </Step>

            <Step number={4} title="Set the API Key (Crucial!)" icon={<KeyIcon className="h-5 w-5" />}>
              <p>The application needs your Google Gemini API key to function. You must provide it as an environment variable.</p>
              <p>In the Code Engine application settings, find the <strong className="text-slate-200">Environment variables</strong> section and add a <strong className="text-slate-200">Literal value</strong>:</p>
              <div className={preBlockClass}>
                <div>Name: <code className={codeBlockClass}>API_KEY</code></div>
                <div className="mt-1">Value: <code className={codeBlockClass}>your-google-gemini-api-key-here</code></div>
              </div>
            </Step>
          </ol>
        </main>
        
        <footer className="p-4 border-t border-violet-800/40 bg-indigo-950/80 flex-shrink-0">
            <p className="text-center text-xs text-slate-400">Once you click 'Create', Code Engine will build and deploy your app to a public URL.</p>
        </footer>
      </div>
    </div>
  );
};

export default DeploymentGuide;
