# Currency Converter

## Installation
Copy the folder `CurrencyConverter` into Statamic's addon folder.
## Installation
Copy the CurrencyConverter folder into the addons folder of your site. After that, go into the control panel -> Addons and set the API Key for your site. You can get a key [here](https://free.currencyconverterapi.com/free-api-key).

## Usage
### Template
```
{{ currency_converter from="USD" to="EUR" amount="99.85" }}
    <div class="bg-grey-darkest text-grey-lighest p-2 mx-4 shadow-inner">
        <span>≈ {{ currency }} {{ converted_amount }}</span>
    </div>
{{ /currency_converter }}`
```  

Also, using a variable you can pass a list of currencies to have multiple conversions. Just like that:
`{{ currency_converter from="USD" :to="currencies_list" amount="99.85" }}`

### API
Use `public function getConversionRate(string $from, string $to)` to get the conversion rate  
Use `public function convert(number $amount, string $from, string|array $to)` to get converted values
