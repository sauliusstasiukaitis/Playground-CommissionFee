Playground
==========

Playground to implement [commision fee task](TASK.md)

# Assumptions

- Data in input file:
  - correct
  - ordered by time

# Out of scope

- Validating if input correct
- Handling exceptional cases
- Storing data outside of the system to check if user did not reach weekly cache out limit 
  during several calls (in several input.csv files)
- Adding different exception types for better handling
- Dealing with incoherent data. etc. currency abbreviation expected to be all uppercase.
- 1 cent rounding issue. All currencies being converted to euro to calculate a commission fee.
  Therefore a 1 cnt rounding issue might appear. 
  etc. 30000 JPY would be rounded to 231.61 EUR.
  
# Known issues

- Acceptance tests run only from the file

# Running 

```
    cd PROJECT_DIRECTORY
    php src/bin/getCommissionFee.php tests/acceptance/fixtures/input.csv 
```

# Testing

- Install dependencies:

        composer install

- Run tests:

        vendor/bin/phpunit tests

# Documentation

Read [repository wiki for more details](https://github.com/stasiukaitis-saulius/Playground-CommissionFee/wiki/Documentation)

# Ignore - Git MD test

## Ignore - Git MD test

#### Ignore - Git MD test

##### Ignore - Git MD test

###### Ignore - Git MD test

####### Ignore - Git MD test

####### Ignore - Git MD test


