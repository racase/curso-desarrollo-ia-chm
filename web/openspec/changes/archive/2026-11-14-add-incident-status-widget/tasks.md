# Tasks

## 1. Status summary widget

- [x] 1.1 Generate the Filament stats overview widget with Artisan, render fixed Spanish statistics for open, in-progress, and resolved incidents with zero defaults, disable polling, and verify a Feature test covers labels, ordering, zero values, and exclusion of closed incidents.
- [x] 1.2 Implement one grouped incident query scoped through IncidentPolicy for the authenticated user, guard unauthenticated access, and verify component tests prove exact counts for administrators, technicians, and isolated requester-owned incidents.

## 2. Dashboard integration

- [x] 2.1 Rely on the existing widget discovery to expose the summary on the InfraTicket dashboard for all three roles without links or actions, and verify dashboard Feature tests cover visibility and informational-only rendering.
- [x] 2.2 Recalculate counts on every widget load and verify a Feature test remounts the widget after incident creation or transition and observes the updated distribution.

## 3. Integration verification

- [x] 3.1 Run the project style check with Pint and resolve any reported changes.
- [x] 3.2 Run the complete PHPUnit test suite and resolve every failure.
- [x] 3.3 Validate the OpenSpec change in strict mode and map every requirement and scenario to implementation evidence.

## Workflow follow-up

- Sync the delta spec to the main specifications after verification.
- Archive the completed OpenSpec change.
- Commit with a Conventional Commit referencing issue #6 and push `feat/poc_codex`.
