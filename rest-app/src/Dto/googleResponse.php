<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\SerializedPath;

class googleResponse
{
    #[Ignore]
    public string $place_id;
    #[SerializedPath('[geometry][location][lat]')]
    public float $latitude;
    #[SerializedPath('[geometry][location][lng]')]
    public float $longitude;
    #[SerializedName('name')]
    public string $formatted_address;
}