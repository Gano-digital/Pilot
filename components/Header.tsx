import React from 'react';
import { PythonIcon, CloudUploadIcon } from './icons';

interface HeaderProps {
  onDeployClick: () => void;
}

const Header: React.FC<HeaderProps> = ({ onDeployClick }) => {
  return (
    <header className="bg-slate-800/50 backdrop-blur-sm border-b border-slate-700/50 sticky top-0 z-10">
      <div className="container mx-auto px-4 md:px-6 lg:px-8 py-4">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-4">
            <PythonIcon className="h-10 w-10 text-sky-400" />
            <div>
              <h1 className="text-xl md:text-2xl font-bold text-white">Python SiteCloner Generator</h1>
              <p className="text-sm text-slate-400">AI-Powered Python Script Generation</p>
            </div>
          </div>
          <button
            onClick={onDeployClick}
            className="bg-slate-700 hover:bg-slate-600 text-slate-300 px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors"
          >
            <CloudUploadIcon className="h-5 w-5" />
            Deploy App
          </button>
        </div>
      </div>
    </header>
  );
};

export default Header;