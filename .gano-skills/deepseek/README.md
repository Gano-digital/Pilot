# Deepseek API Wrapper — Gano Digital

**Status:** ✅ Installed, Authenticated, Ready (requires balance for usage)

---

## Quick Start

### Check Status
```bash
python .gano-skills/deepseek/deepseek-wrapper.py status
```

### List Models
```bash
python .gano-skills/deepseek/deepseek-wrapper.py models
```

### Chat with Deepseek-V3
```bash
python .gano-skills/deepseek/deepseek-wrapper.py chat "Explain quantum computing"
```

### Chat with Deepseek-R1 (Reasoning)
```bash
python .gano-skills/deepseek/deepseek-wrapper.py chat "Solve this logic puzzle" --model reasoner
```

---

## Models

| Alias | Full ID | Description |
|-------|---------|-------------|
| `chat` | `deepseek-chat` | General chat, coding, V3 model |
| `reasoner` | `deepseek-reasoner` | R1 model with chain-of-thought reasoning |

The `reasoner` model exposes its reasoning process before the final answer.

---

## Credentials

- **Location:** `~/.deepseek/credentials/deepseek-api.json`
- **Status:** ✅ Valid, zero balance
- **Action needed:** Add credits at [platform.deepseek.com](https://platform.deepseek.com/)

---

## Deepseek vs NVIDIA NIM for Deepseek

| Feature | Deepseek Official API | NVIDIA NIM |
|---------|----------------------|------------|
| **Model** | `deepseek-chat`, `deepseek-reasoner` | `deepseek-ai/deepseek-v4-pro` |
| **Reasoning exposed** | ✅ Yes (R1) | ❌ No |
| **Price** | Cheapest (direct) | Standard NVIDIA rates |
| **Speed** | Fast (China/US) | Fast (NVIDIA infra) |
| **Embeddings** | ❌ No | ✅ Yes (NVIDIA) |
| **Balance required** | Yes | Included with NVIDIA key |

**Recommendation:** Use **Deepseek official API** for R1 reasoning tasks. Use **NVIDIA** for everything else (no separate balance needed).
