# DigiComp.SettingValidator

This package allows configuring validators with a new configuration type.

## Introduction

This package provides the `SettingsValidator` which uses the configuration type `Validation` to resolve the validators
that should be applied to the value. It distinguishes between validators that are applied to the value itself and its
properties.

## Resolving the validation configuration

The `SettingsValidator` has an option `name`. If it is set, the name is used to resolve the validation configuration,
otherwise the type of the value is used, which is mainly useful for objects where the FQCN is used.

### Resolving by option `name`

To resolve the validation configuration by name just use the option `name`.

```php
/**
 * @Flow\Validate(type="DigiComp.SettingValidator:Settings", options={"name"="MyNamedValidator"})
 * @var MyObject
 */
protected MyObject $myObject;
```

The `SettingsValidator` will search for an entry inside the `Validation.yaml` with that name.

```yaml
MyNamedValidator:
  ...
```

### Resolving by type

To resolve the validation configuration by type just do not set the option `name`.

```php
/**
 * @Flow\Validate(type="DigiComp.SettingValidator:Settings")
 * @var MyObject
 */
protected MyObject $myObject;
```

The `SettingsValidator` will search for an entry inside the `Validation.yaml` with the FQCN of `MyObject`.

```yaml
My\Package\Domain\Model\MyObject:
  ...
```

## The validation configuration

### Difference between `self` and `properties`

`self` contains a map of validators that are applied to the value itself. `properties` contains a map with property
names of the value you would like to validate and each entry contains a map of validators that are applied to that
property.

```yaml
MyNamedValidator:
  self:
    ...
  properties:
    myProperty1:
      ...
    myProperty2:
      ...
```

### Configuring a validator

To configure a validator you use the type of the validator as key and the options as entries of that key. If the
validator has no options or all the default values are used, set an empty map as options.

```yaml
MyNamedValidator:
  self:
    'My.Package:SomeValidator':
        myOption: "myOptionValue"
  properties:
    myProperty1:
      'My.Package:SomeOtherValidator': {}
    myProperty2:
      'My.Package:SomeOtherValidator': {}
```

### Disable a validator

To disable a validator you need to set the options to `null`.

```yaml
MyNamedValidator:
  self:
    'My.Package:SomeValidator': ~
```

## Using the `SettingsValidator`

The `SettingsValidator` can be used to reduce the number of `@Flow\Validate` annotations and gives you the possibility
of overwriting existing validation configurations in other packages.

### Using on properties

Old PHP code:

```php
/**
 * @Flow\Validate(type="My.Package:SomeValidator", options={"myOption"="myOptionValue"})
 * @Flow\Validate(type="My.Package:SomeOtherValidator")
 * @var MyObject
 */
protected MyObject $myObject;
```

New PHP code:

```php
/**
 * @Flow\Validate(type="DigiComp.SettingValidator:Settings", options={"name"="MyNamedValidator"})
 * @var MyObject
 */
protected MyObject $myObject;
```

New validation configuration:

```yaml
MyNamedValidator:
  self:
    'My.Package:SomeValidator':
      myOption: "myOptionValue"
    'My.Package:SomeOtherValidator': {}
```

### Using on actions

Old PHP code:

```php
/**
 * @Flow\Validate(argumentName="myObject", type="My.Package:SomeValidator", options={"myOption"="myOptionValue"})
 * @Flow\Validate(argumentName="myObject", type="My.Package:SomeOtherValidator")
 * @param MyObject $myObject
 */
public function myAction(MyObject $myObject)
{
    ...
}
```

New PHP code:

```php
/**
 * @Flow\Validate(argumentName="myObject", type="DigiComp.SettingValidator:Settings", options={"name"="MyNamedValidator"})
 * @param MyObject $myObject
 */
public function myAction(MyObject $myObject)
{
    ...
}
```

New validation configuration:

```yaml
MyNamedValidator:
  self:
    'My.Package:SomeValidator':
        myOption: "myOptionValue"
    'My.Package:SomeOtherValidator': {}
```

### Using inside validator configurations

You can use the `SettingsValidator` inside the validator configuration to easily construct flexible structures.

```yaml
MyNamedValidator:
  properties:
    myProperty1:
      'DigiComp.SettingValidator:Settings':
        name: "MyOtherNamedValidator"

MyOtherNamedValidator:
  self:
    'My.Package:SomeOtherValidator': {}
```

## Providing an empty validator

It can be useful to provide an empty validator in code that is used by many projects. By doing so you can make sure that
a different validation is possible in any project.

```php
/**
 * @Flow\Validate(argumentName="myObject", type="DigiComp.SettingValidator:Settings", options={"name"="MyNamedValidator"})
 * @param MyObject $myObject
 */
public function myAction(MyObject $myObject)
{
    ...
}
```

```yaml
MyNamedValidator: {}
```
