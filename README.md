# QAI Portal

QAI Portal je prototip raziskovalnega portala za upravljanje znanstvenih raziskav
na področju kvantne umetne inteligence (QAI).

Projekt je zasnovan kot **monorepo**, ki združuje:
- backend (REST API)
- frontend (SPA aplikacijo)

Cilj prototipa je demonstrirati:
- jasno ločitev frontend ↔ backend
- osnovni avtentikacijski tok (DEV)
- administratorski nadzor nad vsebinami
- CRUD operacije nad raziskavami in uporabniki

---

## Namen projekta

QAI Portal služi kot:
- tehnični prototip
- arhitekturni vzorec
- osnova za nadaljnji raziskovalni ali produkcijski razvoj

Projekt je trenutno v **razvojni (DEV) fazi** in ni namenjen produkcijski rabi.

---

## Tehnološki sklad (overview)

- **Backend**: Laravel (PHP, REST API)
- **Frontend**: Vue 3 + Vite (SPA)
- **Komunikacija**: JSON over HTTP (`fetch`)
- **Avtentikacija**: razvojna (brez tokenov, localStorage)
- **Arhitektura**: monorepo

---

## Struktura repozitorija

qai-portal/
├── backend/        # Laravel API (production-ready structure)
├── frontend/       # Vue 3 + Vite frontend
└── README.md
---

## Status projekta

- ✔ Monorepo inicializiran
- ✔ Backend CRUD (Users, Research)
- ✔ Frontend SPA z routerjem
- ✔ Admin vloga (DEV)

---

## Opomba

Projekt je namenoma zasnovan iterativno.
Dokumentacija (README) se bo širila skupaj z razvojem funkcionalnosti.
