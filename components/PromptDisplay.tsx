
import React from 'react';

interface PromptDisplayProps {
  prompt: string;
}

const PromptDisplay: React.FC<PromptDisplayProps> = ({ prompt }) => {
  return (
    <div className="bg-slate-800 rounded-xl shadow-lg p-6 h-[85vh] overflow-y-auto">
      <h2 className="text-lg font-bold text-sky-400 mb-4">Prompt & Requirements</h2>
      <pre className="text-slate-300 text-sm whitespace-pre-wrap font-sans">
        {prompt}
      </pre>
    </div>
  );
};

export default PromptDisplay;
