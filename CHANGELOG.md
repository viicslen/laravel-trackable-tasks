# Changelog

All notable changes to `laravel-trackable-tasks` will be documented in this file.

## v13.0.1 - 2026-08-03

### Fix: the database connection and table options now actually apply

Two config keys were read from paths that do not exist in the published config file, so both options were silently ignored:

| Read from | Should have been |
|---|---|
| `trackable-task.database.connection` (singular prefix) | `trackable-tasks.database.connection` |
| `trackable-tasks.table` | `trackable-tasks.database.table` |

Setting either option had no effect — every task was written to `tracked_tasks` on the default connection regardless of configuration.

Both now read the correct paths using a null coalesce rather than a `config()` default. That distinction matters: the published config defines `database.connection` as `null`, and `config()` only falls back to its default argument when a key is **absent**, not when its value is `null`. Reading the corrected key with a `config()` default would have assigned `null` to the manager's `string`-typed `$connection` property and thrown a `TypeError`.

#### ⚠️ Behaviour change

Applications that set either option will now actually get it. If you configured a custom table or connection on an earlier version, you have been writing to `tracked_tasks` on the default connection — after upgrading you will start using the configured target. Check that the table exists there before deploying.

Applications leaving both at their defaults are unaffected.

#### Tests

Adds `ConfigurationTest` covering both options and their fallbacks. The two "uses the configured ..." cases fail on the previous code and pass on this one; with a deterministic test order the suite goes from 16 failed / 19 passed to 14 failed / 21 passed, with no other test changing state.

## v13.0.0 - 2026-08-03

### Laravel 13 support

- Allow `illuminate/contracts` `^13.0`
- Widen the `orchestra/testbench`, `pestphp/pest` and `pestphp/pest-plugin-laravel` dev constraints so the suite installs on current Laravel versions

### Fixes

- **Restore compatibility with `Illuminate\Support\Fluent::make()`.** Laravel added a static `make()` to `Fluent` with an untyped parameter; `TrackedTaskFake` narrowed it to `array`, an incompatible signature that fatals at class load. This affected **Laravel 12.64 as well as 13** — the test suite could not boot on either.

### Test harness (Laravel 13)

- `job_batches` and `jobs` now ship in the framework migrations that `loadLaravelMigrations()` pulls in, so the workbench copies are guarded with `Schema::hasTable()`
- the `tracked_tasks` stub ran from `getEnvironmentSetUp()`, which is wiped by the reset `LazilyRefreshDatabase` performs; it is now a real workbench migration delegating to the same publishable stub

Verified on Laravel 12.64 and 13.23 — both report 12 failed / 19 passed, exact parity. The remaining 12 failures are pre-existing and unrelated: `cannot start a transaction within a transaction`, from the PHP 8.4 branch of `SQLiteConnection::beginTransaction` colliding with `RefreshDatabase`'s open transaction. They reproduce identically on Laravel 12.

## v12.0.1 - 2026-05-22

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v12.0.0...v12.0.1

## v12.0.0 - 2026-05-22

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v11.0.1...v12.0.0

## v11.0.1 - 2025-03-26

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v11.0.0...v11.0.1

## v11.0.0 - 2025-02-20

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v10.1.1...v11.0.0

## v10.1.1 - 2024-11-13

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v10.1.0...v10.1.1

## v10.1.0 - 2024-01-24

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v10.0.1...v10.1.0

## v10.0.1 - 2023-12-12

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v10.0.0...v10.0.1

## v10.0.0 - 2023-12-08

Bump package version to be on par with Laravel

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v1.1.0...v10.0.0

## v1.1.0 - 2023-09-18

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v1.0.0...v1.1.0

## v1.0.0 - 2023-07-31

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.5.0...v1.0.0

## v0.5.0 - 2023-07-20

### What's Changed

- Bump dependabot/fetch-metadata from 1.5.1 to 1.6.0 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/9

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.4.1...v0.5.0

## v0.4.1 - 2023-06-23

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.6 to 1.4.0 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/7
- Bump dependabot/fetch-metadata from 1.4.0 to 1.5.1 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/8

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.4.0...v0.4.1

## v0.4.0 - 2023-03-23

### What's Changed

- Bump ramsey/composer-install from 1 to 2 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/5

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.18...v0.4.0

## v0.3.18 - 2023-01-30

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.5 to 1.3.6 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/6

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.17...v0.3.18

## v0.3.17 - 2023-01-27

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.16...v0.3.17

## v0.3.16 - 2023-01-27

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.15...v0.3.16

## v0.3.15 - 2023-01-27

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.14...v0.3.15

## v0.3.14 - 2023-01-27

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.13...v0.3.14

## v0.3.13 - 2022-12-07

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.12...v0.3.13

## v0.3.12 - 2022-12-07

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.11...v0.3.12

## v0.3.11 - 2022-11-11

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.3 to 1.3.4 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/3
- Bump dependabot/fetch-metadata from 1.3.4 to 1.3.5 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/4

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.10...v0.3.11

## v0.3.10 - 2022-09-20

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.9...v0.3.10

## v0.3.9 - 2022-08-30

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.8...v0.3.9

## v0.3.8 - 2022-08-29

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.7...v0.3.8

## v0.3.7 - 2022-08-26

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.6...v0.3.7

## v0.3.6 - 2022-08-19

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.5...v0.3.6

## v0.3.5 - 2022-08-17

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.4...v0.3.5

## v0.3.4 - 2022-08-11

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.3...v0.3.4

## v0.3.3 - 2022-08-11

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.2...v0.3.3

## v0.3.2 - 2022-08-10

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.1...v0.3.2

## v0.3.1 - 2022-08-10

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.3.0...v0.3.1

## v0.3.0 - 2022-08-10

### What Changed

- **Breaking Change**: `Trackable` trait was changed to `TrackAutomatically`
- **Breaking Change**: `ManuallyTrackable` trait was changed to `TrackManually`
- Added `taskSetStatus` method to the `TrackManually` trait
- Added the option to update the status when updating the message in the `TrackManually` trait
- Added method to refresh the task progress to both traits

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.2.1...v0.3.0

## v0.2.1 - 2022-08-09

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.2.0...v0.2.1

## v0.2.0 - 2022-08-09

### What Changed

- Added new trait `ManuallyTrackable` which can be used when tracking a custom object/job
  
- Added events for trackable task:
  
- - `TrackableTaskStatusUpdated`: Dispatched when the status of a task is changed, and will contain the whole task object except exceptions and output.
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskExceptionAdded`: Dispatched when a new exception is added. It will contain the task ID and the exception message.
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskCreated`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskCreating`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskDeleted`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskDeleting`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskForceDeleted`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskReplicating`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskRestored`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskRestoring`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskRetrieved`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskSaved`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskSaving`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskTrashed`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskUpdated`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- - `TrackableTaskUpdating`
  
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.1.2...v0.2.0

## v0.1.2 - 2022-08-09

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.1.1...v0.1.2

## v0.1.1 - 2022-08-08

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.1 to 1.3.3 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/2

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.1.0...v0.1.1

## v0.1.0 - 2022-05-04

## What's Changed

- Bump dependabot/fetch-metadata from 1.3.0 to 1.3.1 by @dependabot in https://github.com/viicslen/laravel-trackable-tasks/pull/1
- Add progress syncing in job batches by @viicslen

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.0.5...v0.1.0

## v0.0.5 - 2022-04-18

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.0.4...v0.0.5

## v0.0.4 - 2022-04-18

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.0.3...v0.0.4

## v0.0.3 - 2022-04-18

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.0.2...v0.0.3

## v0.0.2 - 2022-04-18

**Full Changelog**: https://github.com/viicslen/laravel-trackable-tasks/compare/v0.0.1...v0.0.2

## v0.0.1 - 2022-04-18

Initial release
