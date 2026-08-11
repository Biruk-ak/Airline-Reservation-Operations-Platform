# Contributing to Airline Reservation & Operations Platform

Thank you for contributing. This guide explains how to set up the project locally, make changes safely, and open pull requests against this repository.

Repository: [Biruk-ak/Airline-Reservation-Operations-Platform](https://github.com/Biruk-ak/Airline-Reservation-Operations-Platform)

---

## Code of conduct (short)

- Be respectful in issues, reviews, and discussions.
- Prefer clear, reproducible bug reports and focused feature proposals.
- Do not commit secrets, production credentials, or personal passenger data samples.

---

## Ways to contribute

- Report bugs via [GitHub Issues](https://github.com/Biruk-ak/Airline-Reservation-Operations-Platform/issues)
- Propose enhancements (one concern per issue when possible)
- Improve documentation (`README.md`, `docs/`, this guide)
- Submit pull requests that fix issues or add scoped module improvements
- Improve tests under `backend/tests/Feature/`

---

## Prerequisites

Before you start, install:

- Git
- PHP `>= 8.1` + Composer 2
- Node.js `>= 16` + npm
- MySQL 8.x
- Redis (recommended)

Familiarity with **Laravel** and **React** will help, but is not required for docs-only contributions.

---

## 1. Fork and clone

1. Fork the repository on GitHub.
2. Clone **your fork**:

```bash
git clone git@github.com:<your-username>/Airline-Reservation-Operations-Platform.git
cd Airline-Reservation-Operations-Platform
```

3. Add the upstream remote:

```bash
git remote add upstream git@github.com:Biruk-ak/Airline-Reservation-Operations-Platform.git
git fetch upstream
git checkout main
git pull upstream main
```

---

## 2. Run the project locally

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Create DB `airline_ops`, set `DB_*` / `REDIS_*` in `.env`, then:

```bash
php artisan migrate --seed
php artisan serve
```

API: `http://localhost:8000`

Seeded admin (local only):

- Email: `birukaklilu0110@gmail.com`
- Password: `password`

### Frontend

```bash
cd frontend
npm install
export REACT_APP_API_URL=http://localhost:8000/api   # optional
npm start
```

UI: `http://localhost:3000`

Full install notes: [README.md](README.md#installation)

---

## 3. Find or open an issue

- Search existing issues before opening a new one.
- For bug reports, include:
  - Expected vs actual behavior
  - Steps to reproduce
  - Backend/frontend area (module name if known)
  - OS, PHP/Node versions when relevant
- For features, describe the operator workflow and acceptance criteria.
- Comment on an issue if you intend to work on it to avoid duplicate PRs.

---

## 4. Create a branch

Work from an up-to-date `main`:

```bash
git checkout main
git pull upstream main
git checkout -b feature/short-description
```

Recommended branch prefixes:

| Prefix | Use |
| --- | --- |
| `feature/` | New capability |
| `fix/` | Bug fix |
| `docs/` | Documentation only |
| `refactor/` | Internal cleanup without behavior change |
| `test/` | Tests only |

Examples:

- `feature/gate-conflict-score`
- `fix/checkin-document-expiry`
- `docs/readme-install-steps`

Keep branches focused on **one issue** when practical.

---

## 5. Make changes

### Project conventions

- **Backend modules** live under:
  - `backend/app/Models/<Module>/`
  - `backend/app/Http/Controllers/Api/<Module>/`
  - `backend/app/Services/<Module>/` (+ `Domain/` for workflow engines)
  - `backend/app/Http/Requests/<Module>/`
  - `backend/tests/Feature/<Module>/`
- **Frontend modules** live under `frontend/src/modules/<Module>/`
- Prefer small, reviewable diffs over large unrelated refactors.
- Match existing naming, response shapes (`toOperationalSummary`, statistics payloads), and middleware (`auth:sanctum`, `airline.scope`).
- Do not commit `.env`, keys, or `node_modules/` / `vendor/`.

### Useful docs while coding

- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/MODULES.md`](docs/MODULES.md)
- [`docs/API.md`](docs/API.md)

### Validate locally

```bash
# Backend tests (from backend/)
./vendor/bin/phpunit
# or
php artisan test

# Frontend (from frontend/)
npm test
npm run build
```

Manually smoke-test the affected module page and matching API endpoints when UI/API behavior changes.

---

## 6. Commit guidelines

- Write clear commit messages that explain **why**, not only what.
- Prefer focused commits over one giant mixed commit.
- Author identity for contributions to this project should use your GitHub-linked name/email.
- Do **not** include AI co-author trailers unless the maintainers explicitly ask for them.

Example:

```text
Add mishandled filter to baggage search

Allow ops queues to request mishandled bags via a dedicated query flag
mapped to status and metadata conventions.
```

---

## 7. Push and open a pull request

```bash
git push -u origin HEAD
```

Then open a PR against `Biruk-ak/Airline-Reservation-Operations-Platform` **`main`**.

### PR checklist

- [ ] Linked to an issue (`Closes #123` when it fully resolves one)
- [ ] Summary of changes (1–3 bullets)
- [ ] Test plan (commands run + manual checks)
- [ ] No secrets or generated dependency folders
- [ ] Docs updated if behavior/API changed
- [ ] Feature branch is rebased/updated against latest `main` if needed

### Suggested PR body template

```markdown
## Summary
- …

## Test plan
- [ ] `cd backend && ./vendor/bin/phpunit`
- [ ] Manual check: …

Closes #<issue-number>
```

---

## 8. Review process

- Maintainers may request changes — push follow-up commits to the same branch.
- Keep discussion on the PR thread.
- Once approved and CI (if configured) is green, a maintainer will merge.
- After merge, you can delete your feature branch.

Sync your fork regularly:

```bash
git checkout main
git fetch upstream
git merge upstream/main
git push origin main
```

---

## Module contribution tips

When adding behavior to an existing module:

1. Prefer a small dedicated service/helper under `app/Services/<Module>/` when the change is cross-cutting logic.
2. Extend form requests for new query/body fields.
3. Keep airline scoping intact — never return another airline’s records.
4. Add or update a feature test under `backend/tests/Feature/<Module>/`.
5. If the ops console needs the change, update the matching page/API helper in `frontend/src/modules/<Module>/`.

---

## Security disclosures

If you discover a security-sensitive issue (auth bypass, data leak across airlines, injection, etc.), **do not** open a public issue with exploit details. Contact the repository owner privately via GitHub and wait for guidance before disclosing.

---

## Questions

- Open a GitHub Discussion/Issue with the `question` intent, or
- Comment on the related feature issue/PR

Thanks for helping improve the Airline Reservation & Operations Platform.
