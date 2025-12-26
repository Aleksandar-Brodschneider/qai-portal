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

#### Struktura repozitorija

```text
qai-portal/
├─ backend/          # Laravel API
├─ frontend/         # Vue 3 + Vite (SPA)
├─ backend_repo/     # subtree source (zgodovina backend-a)
├─ frontend_repo/    # subtree source (zgodovina frontend-a)
└─ README.md Struktura repozitorija
---

## Status projekta

- Monorepo inicializiran
- Backend CRUD (Users, Research)
- Frontend SPA z routerjem
- Admin vloga (DEV)
- Implementiran REST API za entiteto Research
- CRUD operacije (Create, Read, Update, Delete) izvedene prek API-ja
- API rute preverjene in delujoče (`/api/research`, `/api/research/{id}`)
- Delovanje API-ja testirano z orodjem Postman
- Povezava frontend ↔ backend vzpostavljena prek HTTP (JSON)
- Vue 3 frontend integriran z backend API-jem
- Implementiran prikaz seznama raziskav (list view)
- Implementiran prikaz podrobnosti raziskave (`ResearchDetailView.vue`)
- Administratorsko omejene operacije nad vsebinami (razvojna logika)
- Projekt v razvojni (DEV) fazi
---
## Kontekst predmeta (CRUD demonstracija)

Projekt je bil razvit v okviru predmeta **Spletne tehnologije** kot zaključni CRUD projekt.

V skladu z navodili predmeta je poudarek na:
- pravilni zasnovi REST API-ja,
- CRUD operacijah nad izbrano domeno (*Research*),
- jasni ločitvi frontend in backend slojev.

CRUD funkcionalnost je implementirana v backend delu (Laravel REST API) in je preverljiva:
- prek API klicev (npr. Postman),
- ter dodatno prek Vue.js frontend aplikacije, ki API uporablja.

Frontend ni obvezen del naloge, vendar je v tem projektu dodan kot nadgradnja in demonstracija uporabe API-ja.

