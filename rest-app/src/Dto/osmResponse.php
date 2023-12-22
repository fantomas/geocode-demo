<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\SerializedName;

class osmResponse
{
    #[Ignore]
    public int $place_id;
    #[SerializedName('latitude')]
    public string $lat;
    #[SerializedName('longitude')]
    public string $lon;
    public string $name;
    #[Ignore]
    public string $display_name;
}