# Tasks

## 1. Data model and examples

- [x] 1.1 Generate the Site and Asset models with migrations, factories, and seeders; implement the required fields, unique constraints, soft deletes, and relationships; verify model feature tests cover relations and default query scopes.
- [x] 1.2 Add a migration with a nullable asset reference on incidents, update the Incident and Asset relationships and factories, and verify tests cover legacy incidents without an asset and historical context through soft-deleted records.
- [x] 1.3 Add idempotent example seeders for sites and assets and register them in DatabaseSeeder; verify a feature test proves repeated seeding does not duplicate catalog records.

## 2. Authorization and domain service

- [x] 2.1 Add SitePolicy and AssetPolicy with administrator-only management permissions; verify Gate tests allow administrators and deny technicians and requesters for listing, creating, updating, and deleting.
- [x] 2.2 Implement a catalog service for create, update, and soft-delete operations, including active-site validation for assets; verify tests cover valid mutations and rejection of nonexistent or deleted sites.
- [x] 2.3 Prevent deleting a site that still has active assets and preserve historical relationships after allowed deletions; verify tests cover both rejected and permitted deletion paths.

## 3. Sites in Filament

- [x] 3.1 Generate and configure the Site resource, pages, form, and table with Spanish interface text and without bulk, restore, or force-delete actions; verify access, navigation, listing, and hidden-deleted-record behavior for every role.
- [x] 3.2 Connect site create, edit, and delete actions to the catalog service and validate name, optional address, and uniqueness; verify component tests cover all site scenarios, including the active-assets deletion error.

## 4. Assets in Filament

- [x] 4.1 Generate and configure the Asset resource, pages, form, and table with Spanish interface text, an active-site selector, and no bulk, restore, or force-delete actions; verify access, navigation, listing, and hidden-deleted-record behavior for every role.
- [x] 4.2 Connect asset create, edit, and delete actions to the catalog service and validate code, name, active site, and uniqueness; verify component tests cover all asset scenarios, including a manipulated request that references a deleted site.

## 5. Incident integration

- [x] 5.1 Add a required active-asset selector to incident creation and persist the selected asset; verify component tests cover successful creation, missing asset, nonexistent or deleted asset, and the existing role restrictions.
- [x] 5.2 Display the asset code, name, and site in incident lists and details, with "Sin activo" for legacy incidents; verify all authorized roles can see the context and tests cover soft-deleted assets and sites.
- [x] 5.3 Keep the incident asset immutable and preserve the current visibility and action rules; verify the existing no-edit and authorization tests together with the relevant incident feature tests.

## 6. Integration verification

- [x] 6.1 Run the project style check with Pint and resolve any reported changes.
- [x] 6.2 Run the complete PHPUnit test suite and resolve every failure.
- [x] 6.3 Validate the OpenSpec change in strict mode and map every requirement and scenario to implementation evidence.
