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
    <div className="bg-slate-900 px-4 py-2 flex justify-between items-center border-b border-slate-700 flex-shrink-0">
      <span className="text-sm font-semibold text-slate-300">Propuesta Digital Generada</span>
      {code && !isLoading && !error && (
        <button
          onClick={handleCopy}
          className="bg-slate-700 hover:bg-slate-600 text-slate-300 px-3 py-1.5 rounded-md text-xs font-medium flex items-center transition-colors"
        >
          {isCopied ? (
            <>
              <CheckIcon className="h-4 w-4 mr-1.5 text-green-400" />
              ¡Copiado!
            </>
          ) : (
            <>
              <CopyIcon className="h-4 w-4 mr-1.5" />
              Copiar
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
          title="Error al generar"
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
          title="Generando propuesta..."
          message="La IA está elaborando tu estrategia digital. Por favor, espera un momento."
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
          title="Lista para generar"
          message="Haz clic en 'Generar Propuesta Digital' para crear tu estrategia personalizada con IA."
        />
      </div>
    );
  }

  return (
    <div className="h-full flex flex-col bg-slate-800">
      <CodeHeader />
      <div className="flex-grow overflow-auto">
        <SyntaxHighlighter
          language="markdown"
          style={atomDark}
          customStyle={{
            margin: 0,
            padding: '1rem',
            height: '100%',
            backgroundColor: '#1e293b', // slate-800
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