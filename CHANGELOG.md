# Hawkbit Presentation Changelog

## 2.0

### Added

 - Add extensible presentation adapters, for support of different view engines
 - Convert Plates integration into Plates adapter as default adapter
 - Add convenient Psr7 adapter wrapper, which captures render output and inject into psr7 response body

### Altered

 - Remove dependencies to hawkbit
 
### Removed

 - Remove service provider since their is no standard. Hawbit presentation provides no service provider for each derivate of a service provider

## 1.1.0

### Added

 - Add Hawkbit controller constructor injection support for presentation
 - Add presentation service for extensible engine configuration and encapsulated view logic

## 1.0.1

### Added

 - Add PHP 7.1 Support

## 1.0.0

### Added

 - Add Presentation service, service provider and documentation
 