.. highlight:: yaml
.. _usage:

Place of configuration
======================

The package introduces a new configuration type ``Validation``. The configuration is done through
:file:`Validation.yaml` files.

``allowSplitSource`` is set to true, so it's possible to split large files into smaller ones by
calling them like :file:`Validation.Users.yaml`.

Types of configuration
======================

Inside this files you define the validation rules either for a fully qualified class name, or a
custom name.

Given the following :file:`Validation.yaml`:

.. literalinclude:: /examples/Validation.yaml

Custom Name
-----------

If you prefer the name, you have to configure the validator to use the specific name:

.. code-block:: php

    /**
     * @param Order $order
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", name="MyCustomName")
     */
    public function createOrder($order) {...}

Fully qualified class namespace
-------------------------------
