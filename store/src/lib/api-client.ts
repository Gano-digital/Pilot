// API client for communicating with vite-plugin-api endpoints

const API_BASE = '/api';

export async function checkHealth() {
  const response = await fetch(`${API_BASE}/health`);
  if (!response.ok) {
    throw new Error('Health check failed');
  }
  return response.json();
}