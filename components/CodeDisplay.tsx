import React, { useState, useEffect } from 'react';
import { Prism as SyntaxHighlighter } from 'react-syntax-highlighter';
import { atomDark } from 'react-syntax-highlighter/dist/esm/styles/prism';
import { CopyIcon, CheckIcon, ErrorIcon, SparklesIcon, LoadingSpinnerIcon } from './icons';

interface CodeDisplayProps {
  code: string;
  isLoading: boolean;
  error: string | null;
}

const CodeDisplay: React.FC<CodeDisplayProps> = ({ code, isLoading, error }) => {
  const [isCopied, setIsCopied] = useState(false);

  useEffect(() => {
    if (isCopied) {
      const timer = setTimeout(() => {
        setIsCopied(false);
      }, 2000);
      return () => clearTimeout(timer);
    }
  }, [isCopied]);

  const handleCopy = () => {
    if (code) {
      navigator.clipboard.writeText(code);
      setIsCopied(true);
    }
  };

  const CodeHeader: React.FC = () => (
    <div className="bg-indigo-950/80 px-4 py-2 flex justify-between items-center border-b border-violet-800/40 flex-shrink-0">
      <span className="text-sm font-semibold text-violet-200">Generated Python Script</span>
      {code && !isLoading && !error && (
        <button
          onClick={handleCopy}
          className="bg-violet-800/50 hover:bg-violet-700/60 text-violet-200 border border-violet-700/40 px-3 py-1.5 rounded-md text-xs font-medium flex items-center transition-colors"
        >
          {isCopied ? (
            <>
              <CheckIcon className="h-4 w-4 mr-1.5 text-blue-400" />
              Copied!
            </>
          ) : (
            <>
              <CopyIcon className="h-4 w-4 mr-1.5" />
              Copy
            </>
          )}
        </button>
      )}
    </div>
  );

  const StatusDisplay: React.FC<{ icon: React.ReactNode; title: string; message: string }> = ({ icon, title, message }) => (
    <div className="flex flex-col items-center justify-center h-full text-center p-8 text-slate-400">
      <div className="mb-4">{icon}</div>
      <h3 className="text-lg font-semibold text-slate-200 mb-1">{title}</h3>
      <p className="max-w-md text-sm">{message}</p>
    </div>
  );

  if (error) {
    return (
      <div className="h-full flex flex-col">
        <CodeHeader />
        <StatusDisplay
          icon={<ErrorIcon className="h-16 w-16 text-red-500" />}
          title="Generation Failed"
          message={error}
        />
      </div>
    );
  }

  if (isLoading) {
    return (
      <div className="h-full flex flex-col">
        <CodeHeader />
        <StatusDisplay
          icon={<LoadingSpinnerIcon className="h-16 w-16 text-violet-500 animate-spin" />}
          title="Generating Code..."
          message="The AI is thinking. Please wait a moment."
        />
      </div>
    );
  }

  if (!code) {
    return (
      <div className="h-full flex flex-col">
        <CodeHeader />
        <StatusDisplay
          icon={<SparklesIcon className="h-16 w-16 text-violet-500" />}
          title="Ready to Generate"
          message="Click the 'Generate Python Script' button to create the code."
        />
      </div>
    );
  }

  return (
    <div className="h-full flex flex-col bg-indigo-950/60">
      <CodeHeader />
      <div className="flex-grow overflow-auto">
        <SyntaxHighlighter
          language="python"
          style={atomDark}
          customStyle={{
            margin: 0,
            padding: '1rem',
            height: '100%',
            backgroundColor: '#0d0b1a',
          }}
          codeTagProps={{
            className: 'text-sm'
          }}
        >
          {code}
        </SyntaxHighlighter>
      </div>
    </div>
  );
};

export default CodeDisplay;