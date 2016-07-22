.. highlight:: yaml
.. _usage:

Place of configuration
======================

The package introduces a new configuration type ``Validation``. The configuration is done through
:file:`Validation.yaml` files.

``allowSplitSource`` is set to true, so it's possible to split large files into smaller ones by
calling them like :file:`Validation.Users.yaml`.

.. _types-of-configuration:

Types of configuration
======================

Inside this files you define the validation rules either for a fully qualified class name, or a
custom name.

Given the following :file:`Validation.yaml`:

.. literalinclude:: /examples/Validation.yaml

One class is configured by it's fully qualified class name
``SuperVendor\SuperPackage\Domain\Model\Order`` and a custom name is configured ``OrderCustomer``.

.. _types-of-configuration-custom-name:

Custom Name
-----------

If you prefer the name, you have to configure the validator to use the specific name:

.. code-block:: php

    /**
     * @param Order $order
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", name="MyCustomName")
     */
    public function createOrder($order) {...}

.. _types-of-configuration-fqcn:

Fully qualified class namespace
-------------------------------

If you provide the fully qualified class name, you don't have to provide the additional
argument, the following code will be enough:

.. code-block:: php

    /**
     * @param Order $order
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings")
     */
    public function createOrder($order) {...}

.. _structure-of-configuration:

Structure of configuration
==========================

Each configured validation consist of an array with validation settings.
Each entry needs at least the following options:

``validator``
   The Validator to use, the same way you would use in usual way.
   E.g. use short names for Framework validators like ``StringLength`` or full path for custom
   validators like ``DigiComp.SettingValidator:Settings``.
``options``
   An array of options to provide for the validator.
   The same as you would have done through the usual way.
   If the validator doesn't take arguments, provide an empty array.

Also there are some optional options:

``property``
   Optional, used to configure validation for a property of the object the validation is applied to.
