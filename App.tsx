import React, { useState, useCallback } from 'react';
import { generatePythonScript } from './services/geminiService';
import { USER_PROMPT } from './constants';
import Header from './components/Header';
import PromptDisplay from './components/PromptDisplay';
import CodeDisplay from './components/CodeDisplay';
import UsageGuide from './components/UsageGuide';
import DeploymentGuide from './components/DeploymentGuide';
import { SparklesIcon, LoadingSpinnerIcon } from './components/icons';

const App: React.FC = () => {
  const [generatedCode, setGeneratedCode] = useState<string>('');
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);
  const [isServicesOpen, setIsServicesOpen] = useState<boolean>(false);

  const handleGenerateScript = useCallback(async () => {
    setIsLoading(true);
    setError(null);
    setGeneratedCode('');
    try {
      const code = await generatePythonScript(USER_PROMPT);
      setGeneratedCode(code);
    } catch (err) {
      const errorMessage = err instanceof Error ? err.message : 'Error desconocido.';
      setError(`No se pudo generar la propuesta. Verifica tu clave de API y conexión a internet. Detalle: ${errorMessage}`);
    } finally {
      setIsLoading(false);
    }
  }, []);

  return (
    <div className="min-h-screen bg-slate-900 text-slate-200 font-sans flex flex-col">
      <Header onServicesClick={() => setIsServicesOpen(true)} />
      <main className="flex-grow container mx-auto p-4 md:p-6 lg:p-8 grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        <PromptDisplay prompt={USER_PROMPT} />
        
        <div className="flex flex-col gap-6 sticky top-6">
          <button
            onClick={handleGenerateScript}
            disabled={isLoading}
            className="w-full bg-violet-600 hover:bg-violet-500 disabled:bg-slate-600 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-lg hover:shadow-violet-500/50"
          >
            {isLoading ? (
              <>
                <LoadingSpinnerIcon className="animate-spin h-5 w-5 mr-3" />
                Generando propuesta...
              </>
            ) : (
              <>
                <SparklesIcon className="h-5 w-5 mr-3" />
                Generar Propuesta Digital
              </>
            )}
          </button>
          
          <div className="bg-slate-800 rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[calc(80vh-80px)]">
            <CodeDisplay
              code={generatedCode}
              isLoading={isLoading}
              error={error}
            />
          </div>

          {generatedCode && !isLoading && !error && (
            <UsageGuide />
          )}
        </div>
      </main>
      {isServicesOpen && <DeploymentGuide onClose={() => setIsServicesOpen(false)} />}
    </div>
  );
};

export default App;