# CiviCRM extension: be.ctrl.benormalised

Everything functionally related to normalisation of contact data
- [Usage](#usage)

## Usage

### Plugin list

(* TODO *)

### Api3

![Screenshot](/images/api.png)

Enter the contact field you want to update, this is based on CiviCRM Api4 "contact" > "get" call.

```
$contacts = \Civi\Api4\Contact::get()
  ->addSelect('first_name')
  ->addWhere('first_name', 'IS NOT EMPTY')
  ->execute();
foreach ($contacts as $contact) {}
```

Enter the plugin Class name you want to use to Normalise. (see list above)

### Scheduled job

![Screenshot](/images/scheduled_job.png)

## Getting Started

(* FIXME *)

## Known Issues

(* FIXME *)
