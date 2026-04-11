# gano-gemma-finetune

Skill para fine-tunear **Gemma 3n E4B-it** como asistente local de Gano Digital
(`gano-gemma`), con voz/comportamiento del proyecto + capa RAG sobre `memory/`,
`CLAUDE.md`, `TASKS.md` y `.gano-skills/`.

> Plan completo y SOTA: ver `memory/projects/gano-gemma-plan.md`.

## Cuándo usar esta skill
- Preparar dataset SFT a partir de los .md del repo y las 20 páginas SOTA.
- Entrenar adapter LoRA/QLoRA con Unsloth (local o Colab T4).
- Empaquetar GGUF + Modelfile Ollama.
- Mantener la capa RAG (lancedb) sincronizada con el repo.

## Stack
| Capa | Herramienta |
|------|------------|
| Modelo base | `google/gemma-3n-E4B-it` (ya descargado en `~/.cache/huggingface`) |
| Entrenamiento | Unsloth + PEFT + TRL (QLoRA 4-bit, rank=16) |
| Runtime | Ollama (GGUF Q4_K_M) |
| RAG | lancedb + embeddings `nomic-embed-text` (vía Ollama) |
| Orquestación | Skill + dispatch queue (`memory/claude/dispatch-queue.json`) |

## Estructura
```
gano-gemma-finetune/
├── README.md                    ← este archivo
├── datasets/
│   ├── seed/
│   │   ├── 01-voz-gano.jsonl           ← tono, español, identidad
│   │   ├── 02-paginas-sota.jsonl       ← Q&A de las 20 páginas SOTA
│   │   ├── 03-tareas-operativas.jsonl  ← fases, plugins, Reseller
│   │   └── 04-comportamiento.jsonl     ← reglas duras (no borrar plugins, etc.)
│   └── README.md                ← formato + cómo añadir ejemplos
├── scripts/
│   ├── build_dataset.py         ← merge seed + valida formato chat-template
│   ├── train_unsloth.py         ← entrenamiento QLoRA
│   ├── export_gguf.py           ← merge adapter + export GGUF
│   └── rag_index.py             ← indexa repo en lancedb
├── configs/
│   └── train.yaml               ← hiperparámetros LoRA
└── Modelfile                    ← plantilla Ollama (chat template Gemma 3n)
```

## Requisitos (bloqueantes)
- ⚠️ **Python 3.11 o 3.12** (no 3.14 — sin wheels Unsloth/torch estables).
- GPU NVIDIA ≥12 GB **o** Colab T4 free.
- ≥30 GB libres en disco.
- Licencia Gemma aceptada en HF (✅ hecho 2026-04-07).
- `pip install unsloth datasets peft trl bitsandbytes timm`.

## Flujo end-to-end
1. `python scripts/build_dataset.py` → genera `datasets/gano-sft-v1.jsonl`.
2. `python scripts/train_unsloth.py --config configs/train.yaml` → adapter LoRA.
3. `python scripts/export_gguf.py` → `gano-gemma-Q4_K_M.gguf`.
4. `ollama create gano-gemma -f Modelfile`.
5. `python scripts/rag_index.py` → reindexa repo cuando cambia `memory/`.
6. Inferencia: `gano_chat.py` (RAG + ollama localhost:11434).

## Hiperparámetros base (SOTA abril 2026)
- `load_in_4bit=True`
- LoRA `r=16`, `alpha=16`, target_modules=`all-linear`
- `gradient_checkpointing="unsloth"`
- `lr=2e-4`, `epochs=2-3`, `batch=2`, `grad_accum=4`, `ctx=2048`
- Dataset objetivo: **600-1,500 ejemplos curados** (calidad ≫ cantidad)

## Reglas
- **No subir** el modelo base ni los GGUF al repo (`*.gguf`, `*.safetensors` → `.gitignore`).
- Datasets sí versionados (`datasets/seed/*.jsonl`) — son la propiedad intelectual.
- Cada release de adapter va etiquetado: `gano-style-v1`, `v2`, etc.
- Antes de exportar a Ollama, **verificar chat template** (causa #1 de degradación).

## Estado
- 2026-04-07 — skill creada. Modelo base descargado. Pendiente: instalar `timm`,
  validar load, instalar Python 3.12 paralelo, curar dataset semilla.
