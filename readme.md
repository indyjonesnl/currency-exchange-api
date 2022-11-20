## Currency exchange API

Internal application which caches currency-exchange rates and is only used internally within our Kubernetes cluster.

#### <ins>What</ins> does this application do?
It delivers an identical API like Fixer.io, as a free and open intermediary, without requiring an API key.
If you have an application that works with Fixer, it will (very likely) work with this API. We use these currency pairs
 to convert the European and African currencies on our platforms.

#### <ins>Why</ins> do we need this application?
In order to stay within the free limits of the free currency API's,
we cannot use the free currency APIs in multiple microservice applications.
Instead, this application caches the API response and serves it to multiple
microservice applications without requiring a change in the data-structure
they're already used to.

#### <ins>How</ins> does this application do things differently?
This application loads a response from the Fixer API once a day and caches this response.
The cached response will remain for 7 days (and hopefully be replaced after 24 hours).
But in case the process of loading new exchange rates doesn't work, the old exchange rates will remain cached up to 7 days.

#### Endpoints
- [/symbols](http://localhost:8000/api/symbols)
- [/latest](http://localhost:8000/api/latest)

#### Currency pairs
Please see [/symbols](http://localhost:8000/api/symbols)

#### Notes:
- This application uses EUR as base currency as a fallback.
- Does not require https endpoints, since this API is only used internally within Kubernetes.
- The Fixer API also provides currency-exchange services, which this application does not. This is because our internal 
 applications use the [moneyphp](https://packagist.org/packages/moneyphp/money) library to convert currency.
- If you want to test this API, you will need to provide a Fixer.io API key (in the .env file). A free (personal) 
 account provides enough API credits for testing purposes.

#### Future expansion ideas:
- [ ] <sup>(WIP)</sup> Implement a Google scraper/parser that creates a currency-pair from a Google search result.
- [ ] Implement other (free) currency APIs.
