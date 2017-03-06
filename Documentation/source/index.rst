.. highlight:: php
.. DigiComp.SettingValidator documentation master file, created by
   sphinx-quickstart on Fri Jul 15 17:46:40 2016.

Welcome to DigiComp.SettingValidator's documentation!
=====================================================

This Package allows to configure Validators for your Action-Arguments or domain model properties to be set by a new
Yaml-File in your Configuration directory.

Lets imagine you have this action-method::

    /**
     * @param Order $order
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", argumentName="$order")
     */
    public function createOrder(Order $order) {...}

Then your :file:`Validation.yaml` can look like this:

.. literalinclude:: /examples/Validation.yaml
   :lines: 1-15

As you see: Nesting is possible ;) That way you can easily construct flexible structures.

The SettingsValidator has an optional option: ``name`` - If you don't give one, it assumes your validation value is an
object and searches in :file:`Validation.yaml` for the FQCN.

Contents:

.. toctree::
   :maxdepth: 2

   usage
