/**
 * Chatbot Page
 *
 * Streaming AI chat interface using native fetch + ReadableStream.
 * Connects to POST /api/chat which streams raw text via AI SDK's
 * pipeTextStreamToResponse (see src/server/api/chat/POST.ts).
 *
 * No @ai-sdk/react dependency needed — uses standard browser APIs only.
 *
 * To add this page to your app, add to src/routes.tsx:
 *   const ChatbotPage = lazy(() => import('./pages/chatbot/ChatbotPage'));
 *   { path: '/chat', element: <ChatbotPage /> }
 */

import { useState, useRef, useEffect, type FormEvent } from 'react';

interface Message {
  id: string;
  role: 'user' | 'assistant';
  content: string;
}

export default function ChatbotPage() {
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const bottomRef = useRef<HTMLDivElement>(null);

  // Auto-scroll to the latest message
  useEffect(() => {
    bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const text = input.trim();
    if (!text || isLoading) return;

    const userMessage: Message = { id: `user-${Date.now()}`, role: 'user', content: text };
    const assistantId = `assistant-${Date.now()}`;

    setMessages((prev) => [...prev, userMessage, { id: assistantId, role: 'assistant', content: '' }]);
    setInput('');
    setIsLoading(true);
    setError(null);

    try {
      // Build history to send — plain { role, content } objects that
      // POST.ts passes directly to streamText as CoreMessage[].
      const history = [...messages, userMessage].map((m) => ({
        role: m.role,
        content: m.content,
      }));

      const response = await fetch('/api/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ messages: history }),
      });

      if (!response.ok) {
        throw new Error(`Server error: HTTP ${response.status}`);
      }
      if (!response.body) {
        throw new Error('No response body');
      }

      // Read the raw text stream chunk by chunk and append to the assistant message.
      const reader = response.body.getReader();
      const decoder = new TextDecoder();

      for (let result = await reader.read(); !result.done; result = await reader.read()) {
        const chunk = decoder.decode(result.value, { stream: true });
        setMessages((prev) =>
          prev.map((m) => (m.id === assistantId ? { ...m, content: m.content + chunk } : m))
        );
      }
    } catch (err) {
      const msg = err instanceof Error ? err.message : 'Unknown error';
      setError(msg);
      // Remove the empty assistant message placeholder on failure
      setMessages((prev) => prev.filter((m) => m.id !== assistantId));
    } finally {
      setIsLoading(false);
    }
  }

  const lastMessage = messages[messages.length - 1];
  const showTypingIndicator = isLoading && lastMessage?.role === 'assistant' && lastMessage.content === '';

  return (
    <div className="flex flex-col h-screen max-w-2xl mx-auto p-4">
      <h1 className="text-2xl font-bold mb-4 text-center">Chat</h1>

      {/* Message list */}
      <div className="flex-1 overflow-y-auto space-y-4 pb-4">
        {messages.map((message) => (
          <MessageBubble key={message.id} message={message} />
        ))}

        {/* Animated typing indicator before first chunk arrives */}
        {showTypingIndicator && (
          <div className="flex justify-start">
            <div className="bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-3">
              <div className="flex gap-1 items-center h-4">
                <span className="w-2 h-2 bg-gray-400 rounded-full animate-bounce [animation-delay:-0.3s]" />
                <span className="w-2 h-2 bg-gray-400 rounded-full animate-bounce [animation-delay:-0.15s]" />
                <span className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" />
              </div>
            </div>
          </div>
        )}

        <div ref={bottomRef} />
      </div>

      {/* Input form */}
      <form onSubmit={handleSubmit} className="flex gap-2 mt-2">
        <input
          value={input}
          onChange={(e) => setInput(e.target.value)}
          type="text"
          placeholder="Type a message..."
          disabled={isLoading}
          className="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-50 disabled:text-gray-400"
          autoComplete="off"
          autoFocus
        />
        <button
          type="submit"
          disabled={isLoading || !input.trim()}
          className="px-5 py-2 bg-blue-600 text-white rounded-full font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          {isLoading ? '...' : 'Send'}
        </button>
      </form>

      {error && (
        <p className="mt-2 text-sm text-red-600 text-center">{error}</p>
      )}
    </div>
  );
}

function MessageBubble({ message }: { message: Message }) {
  const isUser = message.role === 'user';
  if (!message.content) return null;

  return (
    <div className={`flex ${isUser ? 'justify-end' : 'justify-start'}`}>
      <div
        className={`max-w-[75%] rounded-2xl px-4 py-2 text-sm leading-relaxed whitespace-pre-wrap ${
          isUser
            ? 'bg-blue-600 text-white rounded-tr-sm'
            : 'bg-gray-100 text-gray-900 rounded-tl-sm'
        }`}
      >
        {message.content}
      </div>
    </div>
  );
}
