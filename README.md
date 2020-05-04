DigiComp.SettingValidator
-------------------------

This Package allows configuring Validators for your Action-Arguments or domain model properties to be set by a new
Yaml-File in your Configuration directory.

Let's imagine you had this action-method:

    /**
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings", argumentName="order")
     * @param Order $order
     */
    public function createOrder($order) {...}

Then your Validation.yaml could look like this:

    Vendor\Package\Domain\Model\Order:
      # validates the complete object
      self:
        'Vendor.Package:SomeOtherValidator': []
      # validates properties of the object
      properties:
        price:
          NumberRange:
            maximum: 20
            minimum: 10
        customer:
          'DigiComp.SettingValidator:Settings':
            name: 'OrderCustomer'

    OrderCustomer:
      properties:
        firstName:
          StringLength:
            minimum: 3
            maximum: 20

As you see: Nesting is possible ;) That way you can easily construct flexible structures.

The SettingsValidator has an optional option: "name" - If you don't give one, it assumes your validation value is an
object and searches in Validation.yaml for the FQCN.
