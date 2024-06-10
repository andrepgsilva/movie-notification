<?php

namespace App\Payloads;

abstract class AbstractPayload
{
    public function fill(array $data): static
    {
        foreach ($data as $prop => $value) {
            if (! property_exists($this, $prop)) {
                continue;
            }

            $this->{$prop} = $value;
        }

        return $this;        
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}