# Changelog

All notable changes to `laravel-trackable-tasks` will be documented in this file.

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
