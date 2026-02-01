This folder contains migration files that have been merged into their respective `create_*` migrations and are archived to reduce the number of active migrations.

Why archive instead of delete?
- Preserves history and git blame for audits or reversions.
- Prevents accidental removal of past migration intent while keeping the active migration set clean for fresh installs.

If you need to restore a migration, move it back to `database/migrations/` and commit.
