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

# Known bug

- 1000 eur free from commission fee applies for a transaction independent
  from amount withdraw beforehand (same week). Examples:
   
   - a customer withdraw 900 and later 1200.
     only 0.6 fee for the second transaction applies.
   - a customer withdraw 300 and 900 for no commission fee.
     Next withdraw will be charged for full sum - 
     3 eur commission fee for 1000 eur withdraw.
