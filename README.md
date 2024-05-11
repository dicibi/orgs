![Test](https://github.com/dicibi/orgs/actions/workflows/ci.yml/badge.svg)
[![codecov](https://codecov.io/gh/dicibi/orgs/graph/badge.svg?token=kYkHRetPu0)](https://codecov.io/gh/dicibi/orgs)


# The Orgs.

This package following standard of corporate structure. It doesn't include the default structure or names, you define it
yourself. This package provide only the functionalities to organize, manage, and doing business process related to job
structure.

---

## Main Components

The orgs. package contains 3 main organizer components.

1. Structure(s)
2. Job Title(s)
3. Office(s)

Each components have nested-set implementation.

## How to Use

Install it via `composer`.

```
composer require dicibi/orgs
```

Use via `app('orgs')`.

```php
app('orgs')->structures(); // Dicibi\Orgs\Resolvers\StructureResolver
app('orgs')->offices(); // Dicibi\Orgs\Resolvers\OfficeResolver
app('orgs')->jobTitles(); // Dicibi\Orgs\Resolvers\JobTitleResolver
```

Or use `orgs` via service binding.

```php
use Dicibi\Orgs\Organizer;

function (Organizer $organizer) {
    $organizer->structures(); // Dicibi\Orgs\Resolvers\StructureResolver
    $organizer->offices(); // Dicibi\Orgs\Resolvers\OfficeResolver
    $organizer->jobTitles(); // Dicibi\Orgs\Resolvers\JobTitleResolver
}
```

Define where the employment will be attached to. It's common to attach it to `users`.

```php

class User extends Model {
    
    use HasEmployment; // add ability to this model have Job Position (historical)
    
}

```

### Use Case - a simple open position

Assign user to Position.

```php
// by inherit this trait
use Dicibi\Orgs\Concerns\HasEmployment;

// it's as simple as to call `assignPosition(position)` to assign a Position
auth()->user()->assignPosition($position);

// to get historically employed/assigned positions, call employments().
// @return QueryBuilder<Dicibi\Orgs\Models\Pivot\Position>
auth()->user()->employments();

// to get current/active position (latest).
// @return Dicibi\Orgs\Models\Pivot\Position
auth()->user()->activePosition();
```

How to open new Job Position (via Office & Job Title).

```php

// create an Office
$newOffice = $organizer->offices()->create('Jakarta Central Office');

// create a Job title
$newTitle = $organizer->titles()->create('Vice President');

// create a position by office
$newPosition = $newOffice->openPositionFor($newTitle, quota: 1);

// or create a position by title (quota is optional)
$newPosition = $newTitle->openPositionIn($newOffice, quota: 1);

// for addition, you might get available/unavailable positions information via Office (TBA)
$newOffice->availablePositions();
$newOffice->unavailablePositions();
$newOffice->positions(); // all positions

// for addition, you might get information about Offices that open for specific Job Title
$customerServiceTitle->availableOffices();
$customerServiceTitle->offices(); // all offices

```

### Use Case - managing hierarchy between Offices

Manage hierarchy between central and branch offices.

```php

// manage via offices resolver
/** @var \Dicibi\Orgs\Resolvers\OfficeResolver $resolver */
$resolver->attach(child: $surabayaBranch, parent: $jakartaCentralOffice);
$resolver->attach(child: $bandungBranch, parent: $jakartaCentralOffice);
$resolver->attach(child: $lampungBranch, parent: $jakartaCentralOffice);
$resolver->tree($jakartaCentralOffice); // get tree

// or manage directly via NodeTrait (kalnoy/nestedset)
/** @var \Dicibi\Orgs\Models\Office|NodeTrait $jakartaCentralOffice */
$jakartaCentralOffice->appendNode($surabayaBranch);
$jakartaCentralOffice->appendNode($bandungBranch);
$jakartaCentralOffice->appendNode($lampungBranch);
$jakartaCentralOffice->tree();

```

### Use Case - managing hierarchy between Job Titles

Manage hierarchy between job titles.

```php

// manage via titles resolver
/** @var \Dicibi\Orgs\Resolvers\JobTitleResolver $resolver */
$resolver->attach(child: $presidentDirector, parent: $presidentCommissioner);
$resolver->attach(child: $financeDirector, parent: $presidentDirector);
$resolver->attach(child: $operationsDirector, parent: $presidentDirector);
$resolver->tree($presidentCommissioner);

// or manage directly via NodeTrait (kalnoy/nestedset)
/** @var \Dicibi\Orgs\Models\Pivot\Position|NodeTrait $presidentCommissioner */
$presidentCommissioner->appendNode($presidentDirector);
$presidentDirector->appendNode($financeDirector);
$presidentDirector->appendNode($operationsDirector);
$presidentCommissioner->tree();

```

### Use Case - building hierarchy with structure template

Instead of managing organization structure in each office, we can manage 'template of structure' that can be assigned to the office. When we had 1 central office, and 30 branches. We do not want to define 31 offices structure each one of it every time.

By using 'structure' template, we can handle that. Let's say in 31 offices, there are office structure that called: Central Office, Branch Office, and Site.

```php

/** @var \Dicibi\Orgs\Resolvers\StructureResolver $structure */
$structure->create('ID Central');
$structure->create('Branch');
$structure->create('Site');

/** @var \Dicibi\Orgs\Resolvers\JobTitleResolver $title */
$title->create('Head', structure: 'ID Central');
  $title->create('Vice-Head', structure: 'ID Central', attachTo: 'Head');
  $title->create('Division Head', structure: 'ID Central', attachTo: 'Head');
    $title->create('Group Head', structure: 'ID Central', attachTo: 'Division Head');

/** @var \Dicibi\Orgs\Resolvers\OfficeResolver $office */
$office->create('Jakarta Central Office', structure: 'ID Central');
$office->create('Aceh Branch Office', structure: 'Branch');
$office->create('Jakarta Branch Office', structure: 'Branch');
$office->create('Surabaya Site', structure: 'Site');
```

Within each structure, we can define unique Job Title structure. In Central structure, we might define Directorate, Division Head, Group Head etc. But in Branch office, that'll be no Directorate, Division or Group, but it contains Head Branch, Vice-Head Branch, Section Head, Manager etc.

It's still possible to setup cross-structure hierarchy, let's say we had Branch Structure, and Central Structure. It's possible that **Head Branch** placed as subordinate of **Division Head of Operations**.












