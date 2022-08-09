# Changelog

All notable changes to `laravel-trackable-tasks` will be documented in this file.

## v0.2.0 - 2022-08-09

### What Changed

- Added new trait `ManuallyTrackable` which can be used when tracking a custom object/job
- Added events for trackable task:
- - `TrackableTaskStatusUpdated`: Dispatched when the status of a task is changed, and will contain the whole task object except exceptions and output.
- - `TrackableTaskExceptionAdded`: Dispatched when a new exception is added. It will contain the task ID and the exception message.
- - `TrackableTaskCreated`
- - `TrackableTaskCreating`
- - `TrackableTaskDeleted`
- - `TrackableTaskDeleting`
- - `TrackableTaskForceDeleted`
- - `TrackableTaskReplicating`
- - `TrackableTaskRestored`
- - `TrackableTaskRestoring`
- - `TrackableTaskRetrieved`
- - `TrackableTaskSaved`
- - `TrackableTaskSaving`
- - `TrackableTaskTrashed`
- - `TrackableTaskUpdated`
- - `TrackableTaskUpdating`
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
