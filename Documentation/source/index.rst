.. highlight:: yaml
.. DigiComp.SettingValidator documentation master file, created by
   sphinx-quickstart on Fri Jul 15 17:46:40 2016.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Welcome to DigiComp.SettingValidator's documentation!
=====================================================

This Package allows to configure Validators for your Action-Arguments or domain model properties to be set by a new
Yaml-File in your Configuration directory.

Lets imagine you had this action-method:

.. code-block:: php

    /**
     * @param Order $order
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings")
     */
    public function createOrder($order) {...}

Then your :file:`Validation.yaml` could look like this:

.. literalinclude:: /examples/Validation.yaml

As you see: Nesting is possible ;) That way you can easily construct flexible structures.

The SettingsValidator has an optional option: "name" - If you don't give one, it assumes your validation value is an
object and searches in Validation.yaml for the FQCN.

Contents:

.. toctree::
   :maxdepth: 2

   usage
