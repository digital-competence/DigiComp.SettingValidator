DigiComp.SettingValidator
-------------------------


This Package allows to configure Validators for your Action-Arguments or domain model properties to be set by a new
Yaml-File in your Configuration directory.

Lets imagine you had this action-method:

    /**
     * @param Order $order
     * @Flow\Validate(type="DigiComp.SettingValidator:Settings")
     */
    public function createOrder($order) {...}

Then your Validation.yaml could look like this:

    SuperVendor\SuperPackage\Domain\Model\Order:
      -
        property: price
        validator: NumberRange
        options:
          maximum: 20
          minimum: 10
      -
        validator: SuperVendor.SuperPackage:SomeOtherValidator #validates the complete object
        options: []
      -
        property: customer
        validator: DigiComp.SettingValidator:Settings
        options:
          name: OrderCustomer

    OrderCustomer:
      -
        property: firstName
        validator: StringLength
        options:
          minimum: 3
          maximum: 20


As you see: Nesting is possible ;) That way you can easily construct flexible structures.

The SettingsValidator has an optional option: "name" - If you don't give one, it assumes your validation value is an
object and searches in Validation.yaml for the FQCN.
