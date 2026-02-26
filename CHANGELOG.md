# Changelog

Notable changes to `naija-faker-laravel` will be documented here.

## 2026-02-26 (v1.0.2)

### Added
- **Seeded PRNG**: `seed()` method with Mulberry32 for deterministic, reproducible output
- **Config system**: `config()` method to set default language, gender, and network
- **Custom exception**: `NaijaFakerException` with typed error codes
- **Identity & Financial**: `bvn()`, `nin()`, `bankAccount()` methods
- **Geographic consistency**: `consistentPerson()`, `consistentPeople()` — name ethnicity, state, and LGA all match
- **Records**: `licensePlate()`, `company()`, `university()`, `educationRecord()`, `workRecord()`, `vehicleRecord()`
- **Personal data**: `dateOfBirth()`, `maritalStatus()`, `bloodGroup()`, `genotype()`, `salary()`, `nextOfKin()`
- **Composites**: `detailedPerson()`, `detailedPeople()` — complete identity in one call
- **Export**: `export()` — JSON/CSV output with dot-notation flattening for nested fields
- **Custom providers**: `registerProvider()`, `generate()`, `listProviders()`
- **Data providers**: 9 new data files — banks (26), geo (37 states, LGAs, regions), plates, companies, universities (42), jobs, vehicles, medical, salary bands

### Changed
- `person()` now returns associative `array` instead of `stdClass` object
- `person()` keys updated: `fullname` → `fullName`, added `firstName` / `lastName`
- All randomness routed through internal `_random()` for seed support
- Updated CI workflow to test across PHP 8.1, 8.2, 8.3

### Fixed
- PRNG 32-bit arithmetic on 64-bit PHP systems

## 2023-09-22 (v1.0.0)
- Initial release