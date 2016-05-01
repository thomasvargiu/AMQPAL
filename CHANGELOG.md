# CHANGELOG

## v0.3.1 (2016-05-01)

### Added:

- Added `QueueInterface::getOptions()`
- Added `ExchangeInterface::getOptions()`

### Changed:

*Nothing*

### Removed

*Nothing*


## v0.3.0 (2016-04-30)

### Added:

- Added `ConnectionInterface::createChannel()`

### Changed:

- Adapters no long implements `createChannel` method. Moved to `ConnectionInterface`

### Removed

*Nothing*


## v0.2.0 (2016-03-26)

### Added:

- `AdapterFactory`
- It's now possible to cancel and stop a consumer returning `null`
- Ability to create queues and exchanges with an array instead of the options object only
- Functional tests

### Changed:

- `QueueInterface::consume()` signature has changed

### Removed:

- Useless `$noWait` property from `QueueOptions` and `ExchangeOptions`
- Useless `$routingKeys` property from `QueueOptions`