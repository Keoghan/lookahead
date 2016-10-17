# Lookahead

> A proof of concept package

A simple exploratory technique to identify if a method is subsequently chained. Potentially useful when writing a fluent interface.

The test file provides a simple example. But, in the simplest form we just need the check and to enable chaining when required.

```php
    use \Keoghan\Lookahead\IsChained;
    ...
    public function lookingAhead()
    {
        if ((new IsChained)->check()) {
            return $this;
        }

        return 'I was not chained';
    }
```
