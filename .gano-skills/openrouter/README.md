# OpenRouter API Wrapper — Gano Digital

**Status:** ✅ Installed, Authenticated, Ready (requires credits for paid models)

---

## Quick Start

### Check Status
```bash
python .gano-skills/openrouter/openrouter-wrapper.py status
```

### List Models
```bash
python .gano-skills/openrouter/openrouter-wrapper.py models
```

### Chat with Claude Sonnet 4.6
```bash
python .gano-skills/openrouter/openrouter-wrapper.py chat "Explain quantum computing" --model claude
```

### Chat with GPT-5.5 Pro
```bash
python .gano-skills/openrouter/openrouter-wrapper.py chat "Explain quantum computing" --model gpt4
```

### Chat with Deepseek V4 Pro
```bash
python .gano-skills/openrouter/openrouter-wrapper.py chat "Explain quantum computing" --model deepseek
```

### Free Model (rate-limited)
```bash
python .gano-skills/openrouter/openrouter-wrapper.py chat "Hello" --model free
```

---

## Model Aliases

| Alias | Full Model ID |
|-------|--------------|
| `claude` | `anthropic/claude-sonnet-4.6` |
| `gpt4` | `openai/gpt-5.5-pro` |
| `llama` | `meta-llama/llama-3.3-70b-instruct` |
| `deepseek` | `deepseek/deepseek-v4-pro` |
| `gemini` | `google/gemini-2.0-pro-exp` |
| `free` | `google/gemma-4-26b-a4b-it:free` |

You can also use any model ID directly:
```bash
python openrouter-wrapper.py chat "Hello" --model mistralai/mistral-7b-instruct
```

---

## Credentials

- **Location:** `~/.openrouter/credentials/openrouter-api.json`
- **Status:** ✅ Valid, free tier
- **Action needed:** Add credits at [openrouter.ai/settings/credits](https://openrouter.ai/settings/credits) to use paid models

---

## What Makes OpenRouter Different

Unlike NVIDIA (which hosts models directly), OpenRouter is a **unified gateway**:
- One API key → 300+ models
- Automatic fallback between providers
- Standardized OpenAI-compatible format
- Cost optimization across providers
