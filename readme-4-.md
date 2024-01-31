# Homeowner Names - Technical Test

> Please do not spend too long on this test, 2 hours should be more than sufficient. You may
choose to create a full application with a basic front-end to upload the CSV, or a simple class
that loads the CSV from the filesystem.

You have been provided with a CSV from an estate agent containing an export of their
homeowner data. If there are multiple homeowners, the estate agent has been entering both
people into one field, often in different formats.

Our system stores person data as individual person records with the following schema:

### Person

- title - required
- first_name - optional
- initial - optional
- last_name - required

Write a program that can accept the CSV and output an array of people, splitting the name into
the correct fields, and splitting multiple people from one string where appropriate.

For example, the string “Mr & Mrs Smith” would be split into 2 people.

## Example Outputs

Input
`“Mr John Smith”`

Output
```
$person[‘title’] => ‘Mr’,
$person[‘first_name’] => “John”,
$person[‘initial’] => null,
$person[‘last_name’] => “Smith”
```

Input
`“Mr and Mrs Smith”`

Output
```
$person[‘title’] => ‘Mr’,
$person[‘first_name’] => null,
$person[‘initial’] => null,
$person[‘last_name’] => “Smith”
$person[‘title’] => ‘Mrs’,
$person[‘first_name’] => null,
$person[‘initial’] => null,
$person[‘last_name’] => “Smith”
```

Input
`“Mr J. Smith”`

Output
```
$person[‘title’] => ‘Mr’,
$person[‘first_name’] => null,
$person[‘initial’] => “J”,
$person[‘last_name’] => “Smith”
```
